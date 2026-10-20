<?php

namespace App\Domain\Vacancies;

/**
 * Capa Domain: DTO de lectura de una vacante para la capa de presentación.
 */
final class VacancySummary
{
    public function __construct(
        public readonly int $id,
        public readonly string $folio,
        public readonly string $title,
        public readonly string $branch,
        public readonly string $region,
        public readonly string $reason,
        public readonly string $type,
        public readonly string $status,
    ) {}
}
