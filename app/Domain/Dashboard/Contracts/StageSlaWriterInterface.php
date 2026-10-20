<?php

namespace App\Domain\Dashboard\Contracts;

/**
 * Capa Domain: puerto de escritura de los SLA por etapa.
 */
interface StageSlaWriterInterface
{
    public function update(string $stage, int $days): void;
}
