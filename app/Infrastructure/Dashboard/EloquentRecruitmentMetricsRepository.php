<?php

namespace App\Infrastructure\Dashboard;

use App\Domain\Dashboard\Contracts\RecruitmentMetricsRepositoryInterface;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use App\Models\Candidate;
use App\Models\CandidateStage;
use App\Models\Hire;
use App\Models\HiringCost;
use App\Models\JobOffer;
use App\Models\PositionRequiredStage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Capa Infrastructure: adaptador Eloquent para las métricas del proceso.
 * Aplica los filtros del tablero sobre la vacante asociada y, según la métrica,
 * sobre la fecha del candidato, la oferta o la contratación.
 */
final class EloquentRecruitmentMetricsRepository implements RecruitmentMetricsRepositoryInterface
{
    public function hiringCounts(VacancyFilter $filter): array
    {
        $required = $this->requiredStagesMap();

        $candidates = $this->candidatesQuery($filter)
            ->with(['vacancy', 'stages'])
            ->get();

        $contracted = 0;
        $finalists = 0;

        foreach ($candidates as $candidate) {
            if ($candidate->stage === 'contratado') {
                $contracted++;
            }

            $title = $this->normalize((string) ($candidate->vacancy?->title ?? ''));
            $needed = $required[$title] ?? [];
            if ($needed === []) {
                continue;
            }

            $done = $candidate->stages
                ->pluck('stage')
                ->map(fn ($stage) => $this->normalize((string) $stage))
                ->unique();

            $isFinalist = collect($needed)->every(fn ($stage) => $done->contains($stage));
            if ($isFinalist) {
                $finalists++;
            }
        }

        return ['contracted' => $contracted, 'finalists' => $finalists];
    }

    public function offerCounts(VacancyFilter $filter): array
    {
        $query = JobOffer::query();
        $this->applyVacancyFilters($query, $filter);
        $this->applyDateFilter($query, $filter, 'offered_at');

        return [
            'emitted' => (clone $query)->count(),
            'accepted' => (clone $query)->where('status', 'aceptada')->count(),
        ];
    }

    public function retentionCounts(VacancyFilter $filter, int $days = 90): array
    {
        $cutoff = now()->subDays($days)->startOfDay();

        $query = Hire::query();
        $this->applyVacancyFilters($query, $filter);
        $this->applyDateFilter($query, $filter, 'hire_date');

        $eligible = (clone $query)
            ->whereDate('hire_date', '<=', $cutoff->toDateString())
            ->get(['hire_date', 'termination_date']);

        $retained = $eligible->filter(function (Hire $hire) use ($days) {
            if ($hire->termination_date === null) {
                return true;
            }

            return abs($hire->hire_date->diffInDays($hire->termination_date)) >= $days;
        })->count();

        return ['eligible' => $eligible->count(), 'retained' => $retained];
    }

    public function turnoverCounts(VacancyFilter $filter, int $days = 90): array
    {
        $query = Hire::query();
        $this->applyVacancyFilters($query, $filter);
        $this->applyDateFilter($query, $filter, 'hire_date');

        $hires = (clone $query)->get(['hire_date', 'termination_date']);

        $early = $hires->filter(function (Hire $hire) use ($days) {
            if ($hire->termination_date === null) {
                return false;
            }

            return abs($hire->hire_date->diffInDays($hire->termination_date)) < $days;
        })->count();

        return ['hires' => $hires->count(), 'early' => $early];
    }

    public function sourcesRanking(VacancyFilter $filter, int $limit = 5): array
    {
        $candidates = $this->candidatesQuery($filter)->get(['source', 'stage']);

        $grouped = $candidates->groupBy(fn ($candidate) => trim((string) $candidate->source))
            ->map(fn (Collection $rows, string $source) => [
                'source' => $source !== '' ? $source : 'Sin fuente',
                'contracted' => $rows->where('stage', 'contratado')->count(),
                'total' => $rows->count(),
            ])
            ->values()
            ->sortByDesc('contracted')
            ->values();

        $out = [];
        $rank = 1;
        foreach ($grouped->take($limit) as $row) {
            $out[] = array_merge($row, ['rank' => $rank++]);
        }

        return $out;
    }

    public function costTotals(VacancyFilter $filter): array
    {
        $query = HiringCost::query();

        if ($filter->yearInt() !== null) {
            $query->where('period', 'like', $filter->yearInt().'-%');
        }

        return [
            'invested' => (float) (clone $query)->sum('total_investment'),
            'hires' => (int) (clone $query)->sum('hires_count'),
        ];
    }

    public function stageDurations(VacancyFilter $filter): array
    {
        $candidateIds = $this->candidatesQuery($filter)->pluck('id');

        $rows = CandidateStage::query()
            ->whereIn('candidate_id', $candidateIds)
            ->whereNotNull('completed_at')
            ->orderBy('candidate_id')
            ->orderBy('completed_at')
            ->get(['candidate_id', 'stage', 'completed_at']);

        $durations = [];
        $previous = [];

        foreach ($rows->groupBy('candidate_id') as $stages) {
            $previous = null;
            foreach ($stages as $stage) {
                if ($previous !== null) {
                    $days = abs($previous->completed_at->diffInDays($stage->completed_at));
                    $durations[$stage->stage][] = $days;
                }
                $previous = $stage;
            }
        }

        $out = [];
        foreach ($durations as $stage => $values) {
            $out[] = [
                'stage' => $stage,
                'avg_days' => round(array_sum($values) / count($values), 1),
                'samples' => count($values),
            ];
        }

        usort($out, fn ($a, $b) => $b['avg_days'] <=> $a['avg_days']);

        return $out;
    }

    private function candidatesQuery(VacancyFilter $filter): Builder
    {
        $query = Candidate::query();
        $this->applyVacancyFilters($query, $filter);
        $this->applyDateFilter($query, $filter, 'created_at');

        return $query;
    }

    private function applyVacancyFilters(Builder $query, VacancyFilter $filter): void
    {
        $query->whereHas('vacancy', function (Builder $vacancy) use ($filter) {
            if ($filter->region !== '') {
                $vacancy->where('region', $filter->region);
            }
            if ($filter->branch !== '') {
                $vacancy->where('branch', $filter->branch);
            }
            if ($filter->position !== '') {
                $vacancy->where('title', 'like', '%'.$filter->position.'%');
            }
        });
    }

    private function applyDateFilter(Builder $query, VacancyFilter $filter, string $column): void
    {
        if ($filter->monthInt() !== null) {
            $query->whereMonth($column, $filter->monthInt());
        }
        if ($filter->yearInt() !== null) {
            $query->whereYear($column, $filter->yearInt());
        }
        $range = $filter->validDateRange();
        if ($range !== null) {
            $query->whereDate($column, '>=', $range[0])->whereDate($column, '<=', $range[1]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    private function requiredStagesMap(): array
    {
        return PositionRequiredStage::query()
            ->get(['position_title', 'stage'])
            ->groupBy(fn ($row) => $this->normalize((string) $row->position_title))
            ->map(fn (Collection $rows) => $rows->pluck('stage')->map(fn ($s) => $this->normalize((string) $s))->unique()->values()->all())
            ->all();
    }

    private function normalize(string $value): string
    {
        return Str::lower(Str::ascii(trim($value)));
    }
}
