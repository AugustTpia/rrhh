<?php

namespace App\Application\Dashboard;

use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use App\Domain\Dashboard\Contracts\RecruitmentMetricsRepositoryInterface;
use App\Domain\Dashboard\Contracts\StageSlaProviderInterface;
use App\Domain\Dashboard\Contracts\VacancyStatsRepositoryInterface;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;

/**
 * Capa Application: CASO DE USO del tablero.
 * Orquesta repos; no conoce Eloquent ni conexiones. Cálculos de negocio puros aquí.
 * Todos los KPIs respetan los filtros (decisión de producto).
 */
final class GetDashboardStatsUseCase
{
    public function __construct(
        private readonly VacancyStatsRepositoryInterface $vacancies,
        private readonly CatalogRepositoryInterface $catalogs,
        private readonly RecruitmentMetricsRepositoryInterface $recruitment,
        private readonly StageSlaProviderInterface $stageSla,
    ) {}

    /**
     * @return array{metrics:array, catalogs:array, total:int}
     */
    public function handle(VacancyFilter $filter, int $perPage = 15): array
    {
        $now = new \DateTimeImmutable('now');
        $month = (int) $now->format('n');
        $year = (int) $now->format('Y');

        $historyCurrent = $this->vacancies->historyForYear($filter, $year);
        $historyPrev = $this->vacancies->historyForYear($filter, $year - 1);

        $top = $this->vacancies->topCritical($filter);

        return [
            'metrics' => [
                'total' => $this->vacancies->countFiltered($filter),
                'avgCoverageDays' => $this->vacancies->avgCoverageDays($filter),
                'totalCritical' => $this->vacancies->countCritical($filter),
                'topPosition' => $top['position'],
                'topBranch' => $top['branch'],
                'topRegion' => $top['region'],
                'monthlyOpen' => $this->vacancies->countMonthly($filter, $month, $year, 'open'),
                'monthlyHired' => $this->vacancies->countMonthly($filter, $month, $year, 'hired'),
                'monthLabel' => $month,
                'yearLabel' => $year,
                'yearlyActive' => $this->vacancies->countYearly($filter, $year, 'open'),
                'yearlyCovered' => $this->vacancies->countYearly($filter, $year, 'covered'),
                'historyPrev' => array_merge(['year' => $year - 1], $historyPrev),
                'historyCurrent' => array_merge(['year' => $year], $historyCurrent),
            ],
            'catalogs' => [
                'regions' => $this->catalogs->regions(),
                'branches' => $this->catalogs->branches($filter->region !== '' ? $filter->region : null),
                'positions' => $this->catalogs->positions(),
                'years' => $this->availableYears($filter),
            ],
            'recruitment' => $this->recruitmentMetrics($filter),
            'total' => $this->vacancies->countFiltered($filter),
        ];
    }

    /**
     * Métricas del proceso de reclutamiento con porcentajes ya calculados.
     *
     * @return array<string, array<string, mixed>>
     */
    private function recruitmentMetrics(VacancyFilter $filter): array
    {
        $hiring = $this->recruitment->hiringCounts($filter);
        $offers = $this->recruitment->offerCounts($filter);
        $retention = $this->recruitment->retentionCounts($filter);
        $turnover = $this->recruitment->turnoverCounts($filter);
        $costs = $this->recruitment->costTotals($filter);
        $ranking = $this->recruitment->sourcesRanking($filter);

        return [
            'hiring' => [
                'contracted' => $hiring['contracted'],
                'finalists' => $hiring['finalists'],
                'rate' => $this->percent($hiring['contracted'], $hiring['finalists']),
            ],
            'offers' => [
                'emitted' => $offers['emitted'],
                'accepted' => $offers['accepted'],
                'rate' => $this->percent($offers['accepted'], $offers['emitted']),
            ],
            'retention' => [
                'eligible' => $retention['eligible'],
                'retained' => $retention['retained'],
                'rate' => $this->percent($retention['retained'], $retention['eligible']),
            ],
            'turnover' => [
                'hires' => $turnover['hires'],
                'early' => $turnover['early'],
                'rate' => $this->percent($turnover['early'], $turnover['hires']),
            ],
            'sources' => [
                'ranking' => $ranking,
                'top' => $ranking[0] ?? null,
            ],
            'cost' => [
                'invested' => $costs['invested'],
                'hires' => $costs['hires'],
                'per_hire' => $costs['hires'] > 0 ? round($costs['invested'] / $costs['hires'], 2) : 0.0,
            ],
            'stage_durations' => $this->stageDurations($filter),
        ];
    }

    /**
     * Anota el promedio de cada etapa con su SLA y si cumple el objetivo.
     *
     * @return array<int, array{stage:string, avg_days:float, samples:int, sla_days:?int, on_target:?bool}>
     */
    private function stageDurations(VacancyFilter $filter): array
    {
        $durations = $this->recruitment->stageDurations($filter);

        return array_map(function (array $stage) {
            $sla = $this->stageSla->daysFor($stage['stage']);

            return $stage + [
                'sla_days' => $sla,
                'on_target' => $sla !== null ? $stage['avg_days'] <= $sla : null,
            ];
        }, $durations);
    }

    private function percent(int $part, int $total): int
    {
        return $total > 0 ? (int) round(($part / $total) * 100) : 0;
    }

    /** Años con datos (created_at) + año actual, para el dropdown sin quemar 2025/2026. */
    private function availableYears(VacancyFilter $filter): array
    {
        $years = $this->vacancies->distinctValues('year');
        $current = (int) (new \DateTimeImmutable('now'))->format('Y');
        if (! in_array((string) $current, $years, true)) {
            $years[] = (string) $current;
        }
        rsort($years);

        return array_values(array_slice($years, 0, 6));
    }
}
