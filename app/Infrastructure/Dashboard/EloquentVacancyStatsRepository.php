<?php

namespace App\Infrastructure\Dashboard;

use App\Domain\Dashboard\Contracts\VacancyStatsRepositoryInterface;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Capa Infrastructure: ADAPTADOR Eloquent (DB local sqlite→mysql).
 * Único lugar que conoce nombres de columnas y SQL. Portable: sin julianday(),
 * fechas con whereDate/whereMonth/whereYear y promedios calculados en PHP con Carbon.
 */
final class EloquentVacancyStatsRepository implements VacancyStatsRepositoryInterface
{
    public function countFiltered(VacancyFilter $filter): int
    {
        return $this->baseQuery($filter)->count();
    }

    public function avgCoverageDays(VacancyFilter $filter): float
    {
        $rows = $this->baseQuery($filter)
            ->where('status', 'cerrada')
            ->whereNotNull('closed_at')
            ->get(['created_at', 'closed_at']);

        if ($rows->isEmpty()) {
            return 0.0;
        }

        $total = 0;
        $n = 0;
        foreach ($rows as $row) {
            $from = Carbon::parse($row->created_at);
            $to = Carbon::parse($row->closed_at);
            // abs(): Carbon 3 devuelve diffs con signo por defecto.
            $total += abs($from->diffInDays($to));
            $n++;
        }

        return $n > 0 ? round($total / $n, 1) : 0.0;
    }

    public function countCritical(VacancyFilter $filter, int $days = 20): int
    {
        return $this->criticalQuery($filter, $days)->count();
    }

    public function topCritical(VacancyFilter $filter, int $days = 20): array
    {
        $cutoff = Carbon::now()->subDays($days);

        $byPosition = (clone $this->criticalBase($filter, $cutoff))
            ->selectRaw('title as name, COUNT(*) as total')
            ->groupBy('title')
            ->orderByDesc('total')
            ->first();

        $byBranch = (clone $this->criticalBase($filter, $cutoff))
            ->selectRaw('branch as name, COUNT(*) as total')
            ->groupBy('branch')
            ->orderByDesc('total')
            ->first();

        $byRegion = (clone $this->criticalBase($filter, $cutoff))
            ->selectRaw('region as name, COUNT(*) as total')
            ->groupBy('region')
            ->orderByDesc('total')
            ->first();

        return [
            'position' => $byPosition ? ['name' => $byPosition->name, 'total' => (int) $byPosition->total] : null,
            'branch' => $byBranch ? ['name' => $byBranch->name, 'total' => (int) $byBranch->total] : null,
            'region' => $byRegion ? ['name' => $byRegion->name, 'total' => (int) $byRegion->total] : null,
        ];
    }

    public function countMonthly(VacancyFilter $filter, int $month, int $year, string $kind): int
    {
        $q = $this->baseQuery($filter, skipTemporal: true);

        if ($kind === 'hired') {
            return $q->where('status', 'cerrada')
                ->whereNotNull('closed_at')
                ->whereMonth('closed_at', $month)
                ->whereYear('closed_at', $year)
                ->count();
        }

        return $q->where('status', 'activa')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();
    }

    public function countYearly(VacancyFilter $filter, int $year, string $kind): int
    {
        $q = $this->baseQuery($filter, skipTemporal: true);

        if ($kind === 'covered') {
            return $q->where('status', 'cerrada')
                ->whereYear('closed_at', $year)
                ->count();
        }

        return $q->where('status', 'activa')
            ->whereYear('created_at', $year)
            ->count();
    }

    public function historyForYear(VacancyFilter $filter, int $year): array
    {
        $base = $this->baseQuery($filter, skipTemporal: true);

        $vacancies = (clone $base)->whereYear('created_at', $year)->count();
        $hired = (clone $base)->where('status', 'cerrada')->whereYear('closed_at', $year)->count();
        // Ingresos alternativos si closed_at es nulo: cerradas creadas ese año.
        if ($hired === 0 && $vacancies > 0) {
            $hired = (clone $base)->where('status', 'cerrada')->whereYear('created_at', $year)->count();
        }
        $coverage = $vacancies > 0 ? (int) round(($hired / $vacancies) * 100) : 0;

        return ['vacancies' => $vacancies, 'hired' => $hired, 'coverage' => $coverage];
    }

    public function distinctValues(string $column): array
    {
        if ($column === 'year') {
            return Vacancy::query()
                ->selectRaw('DISTINCT strftime("%Y", created_at) as y')
                ->orderByDesc('y')
                ->pluck('y')
                ->filter()
                ->values()
                ->all();
        }

        $allowed = ['branch', 'region', 'title'];
        if (! in_array($column, $allowed, true)) {
            return [];
        }

        return Vacancy::query()
            ->distinct()
            ->whereNotNull($column)
            ->orderBy($column)
            ->pluck($column)
            ->filter()
            ->values()
            ->all();
    }

    public function paginateFiltered(VacancyFilter $filter, int $perPage = 15): LengthAwarePaginator
    {
        return $this->baseQuery($filter)->latest()->paginate($perPage);
    }

    /** Query base con los 8 filtros. $skipTemporal evita doble filtro mes/año en KPIs mensuales/anuales. */
    private function baseQuery(VacancyFilter $filter, bool $skipTemporal = false): Builder
    {
        $q = Vacancy::query();

        if ($filter->hasStatus()) {
            $q->where('status', $filter->normalizedStatus());
        }
        if ($filter->region !== '') {
            $q->where('region', $filter->region);
        }
        if ($filter->branch !== '') {
            $q->where('branch', $filter->branch);
        }
        if ($filter->position !== '') {
            $q->where('title', 'like', '%'.$filter->position.'%');
        }
        if (! $skipTemporal) {
            if ($filter->monthInt() !== null) {
                $q->whereMonth('created_at', $filter->monthInt());
            }
            if ($filter->yearInt() !== null) {
                $q->whereYear('created_at', $filter->yearInt());
            }
            $range = $filter->validDateRange();
            if ($range !== null) {
                $q->whereDate('created_at', '>=', $range[0])->whereDate('created_at', '<=', $range[1]);
            }
        } else {
            // KPIs mensuales/anuales/históricos respetan región/sucursal/puesto pero aplican
            // su propio periodo; el rango personalizado sí se conserva si es válido.
            $range = $filter->validDateRange();
            if ($range !== null) {
                $q->whereDate('created_at', '>=', $range[0])->whereDate('created_at', '<=', $range[1]);
            }
        }

        return $q;
    }

    private function criticalQuery(VacancyFilter $filter, int $days): Builder
    {
        return $this->criticalBase($filter, Carbon::now()->subDays($days));
    }

    private function criticalBase(VacancyFilter $filter, Carbon $cutoff): Builder
    {
        $q = $this->baseQuery($filter, skipTemporal: true);

        // Si el usuario ya filtró un status distinto de abierta, no hay críticas.
        if ($filter->hasStatus() && $filter->normalizedStatus() !== 'activa') {
            $q->whereRaw('1 = 0');
        } else {
            $q->where('status', 'activa')->where('created_at', '<', $cutoff);
        }

        // El filtro temporal del usuario acota además el universo de críticas.
        if ($filter->monthInt() !== null) {
            $q->whereMonth('created_at', $filter->monthInt());
        }
        if ($filter->yearInt() !== null) {
            $q->whereYear('created_at', $filter->yearInt());
        }
        $range = $filter->validDateRange();
        if ($range !== null) {
            $q->whereDate('created_at', '>=', $range[0])->whereDate('created_at', '<=', $range[1]);
        }

        return $q;
    }
}
