<?php

namespace App\Domain\Dashboard\Contracts;

use App\Domain\Dashboard\ValueObjects\VacancyFilter;

/**
 * Capa Domain: puerto de métricas del proceso de reclutamiento.
 * Los cálculos de negocio (porcentajes, finalistas) se resuelven en Application;
 * aquí solo se expone la obtención de datos.
 */
interface RecruitmentMetricsRepositoryInterface
{
    /**
     * @return array{contracted:int, finalists:int}
     */
    public function hiringCounts(VacancyFilter $filter): array;

    /**
     * @return array{emitted:int, accepted:int}
     */
    public function offerCounts(VacancyFilter $filter): array;

    /**
     * Contrataciones con al menos 90 días de antigüedad y cuántas siguen activas.
     *
     * @return array{eligible:int, retained:int}
     */
    public function retentionCounts(VacancyFilter $filter, int $days = 90): array;

    /**
     * @return array{hires:int, early:int}
     */
    public function turnoverCounts(VacancyFilter $filter, int $days = 90): array;

    /**
     * @return array<int, array{source:string, contracted:int, total:int, rank:int}>
     */
    public function sourcesRanking(VacancyFilter $filter, int $limit = 5): array;

    /**
     * @return array{invested:float, hires:int}
     */
    public function costTotals(VacancyFilter $filter): array;

    /**
     * Promedio de días por etapa (a partir de completed_at consecutivos).
     *
     * @return array<int, array{stage:string, avg_days:float, samples:int}>
     */
    public function stageDurations(VacancyFilter $filter): array;
}
