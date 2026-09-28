<?php

namespace App\Domain\Dashboard\Contracts;

use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Capa Domain: PUERTO de estadísticas de vacantes (DB local).
 * Define QUÉ necesita el caso de uso; la implementación Eloquent vive en Infrastructure.
 * Todo trabaja sobre el schema real: folio/title/branch/region/status/created_at/closed_at.
 */
interface VacancyStatsRepositoryInterface
{
    public function countFiltered(VacancyFilter $filter): int;

    public function avgCoverageDays(VacancyFilter $filter): float;

    /** Vacantes abiertas (activa) con >20 días desde created_at, respetando filtros. */
    public function countCritical(VacancyFilter $filter, int $days = 20): int;

    /** Top críticas agrupadas: ['position' => ?array{name,total}, 'branch' => ..., 'region' => ...]. */
    public function topCritical(VacancyFilter $filter, int $days = 20): array;

    public function countMonthly(VacancyFilter $filter, int $month, int $year, string $kind): int;

    public function countYearly(VacancyFilter $filter, int $year, string $kind): int;

    /** ['vacancies'=>int,'hired'=>int,'coverage'=>int] para un año, respetando filtros no temporales. */
    public function historyForYear(VacancyFilter $filter, int $year): array;

    /** IDs distintos para fallback de dropdowns cuando external_auth no responde. */
    public function distinctValues(string $column): array;

    /** Paginado de la tabla detalle con los mismos 8 filtros. */
    public function paginateFiltered(VacancyFilter $filter, int $perPage = 15): LengthAwarePaginator;
}
