<?php

namespace App\Application\Dashboard;

use App\Domain\Dashboard\Contracts\StageSlaWriterInterface;

/**
 * Capa Application: caso de uso para actualizar los SLA por etapa.
 */
final class UpdateStageSlasUseCase
{
    public function __construct(
        private readonly StageSlaWriterInterface $writer,
    ) {}

    /**
     * @param  array<int, array{stage?:string, sla_days?:int|string}>  $slas
     */
    public function handle(array $slas): void
    {
        foreach ($slas as $row) {
            $stage = trim((string) ($row['stage'] ?? ''));
            $days = (int) ($row['sla_days'] ?? 0);

            if ($stage === '' || $days < 1) {
                continue;
            }

            $this->writer->update($stage, $days);
        }
    }
}
