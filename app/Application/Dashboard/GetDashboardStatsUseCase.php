<?php

namespace App\Application\Dashboard;

use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
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
            'total' => $this->vacancies->countFiltered($filter),
        ];
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
