<?php

namespace App\Infrastructure\Dashboard;

use App\Domain\Dashboard\Contracts\StageSlaProviderInterface;
use App\Domain\Dashboard\Contracts\StageSlaWriterInterface;
use App\Models\StageSlaConfig;
use Illuminate\Support\Str;

/**
 * Capa Infrastructure: SLA persistidos en BD con respaldo en config/recruitment.php.
 * Normaliza la clave para tolerar mayúsculas/acentos.
 */
final class DbStageSlaProvider implements StageSlaProviderInterface, StageSlaWriterInterface
{
    /** @var array<string, int>|null */
    private ?array $map = null;

    public function daysFor(string $stage): ?int
    {
        return $this->map()[$this->normalize($stage)] ?? null;
    }

    public function all(): array
    {
        return $this->map();
    }

    public function update(string $stage, int $days): void
    {
        $stage = trim($stage);

        StageSlaConfig::updateOrCreate(
            ['stage' => $stage],
            ['sla_days' => max(1, $days)],
        );

        $this->map = null;
    }

    /**
     * @return array<string, int>
     */
    private function map(): array
    {
        if ($this->map === null) {
            $defaults = collect(config('recruitment.stage_sla_days', []))
                ->mapWithKeys(fn ($days, $stage) => [$this->normalize((string) $stage) => (int) $days]);

            $persisted = StageSlaConfig::query()
                ->get(['stage', 'sla_days'])
                ->mapWithKeys(fn (StageSlaConfig $row) => [$this->normalize($row->stage) => (int) $row->sla_days]);

            $this->map = $defaults->merge($persisted)->all();
        }

        return $this->map;
    }

    private function normalize(string $stage): string
    {
        return Str::lower(Str::ascii(trim($stage)));
    }
}
