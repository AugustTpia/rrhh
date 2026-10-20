<?php

namespace App\Domain\Vacancies\Contracts;

use App\Domain\Vacancies\VacancySummary;

/**
 * Capa Domain: puerto de persistencia/consulta de vacantes.
 */
interface VacancyRepositoryInterface
{
    /**
     * @return array<int, VacancySummary>
     */
    public function all(string $search = '', string $status = ''): array;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): VacancySummary;

    public function delete(int $id): bool;
}
