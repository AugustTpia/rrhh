<?php

namespace App\Domain\Dashboard\Contracts;

/**
 * Capa Domain: puerto para consultar el objetivo (SLA) en días de cada etapa.
 * La fuente de configuración se resuelve en Infrastructure.
 */
interface StageSlaProviderInterface
{
    public function daysFor(string $stage): ?int;

    /**
     * @return array<string, int> etapa => días objetivo
     */
    public function all(): array;
}
