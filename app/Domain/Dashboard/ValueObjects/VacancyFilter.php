<?php

namespace App\Domain\Dashboard\ValueObjects;

/**
 * Capa Domain: VO puro, sin Eloquent ni Laravel.
 * Normaliza los 8 filtros del tablero a valores comparables con el schema local:
 * vacancies(status, region, branch, title, created_at).
 *
 * Mapeo status UI <-> DB local:
 * - UI "abierta"  => DB "activa" (valor real en migración create_vacancies_table)
 * - UI "activa"   => DB "activa" (compatibilidad)
 * - "cerrada"/"pausada" son idénticas en ambos.
 */
final class VacancyFilter
{
    public function __construct(
        public readonly string $status = '',
        public readonly string $region = '',
        public readonly string $branch = '',
        public readonly string $position = '',
        public readonly string $month = '',
        public readonly string $year = '',
        public readonly string $dateFrom = '',
        public readonly string $dateTo = '',
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            status: trim((string) ($data['status'] ?? '')),
            region: trim((string) ($data['region'] ?? $data['region_id'] ?? '')),
            branch: trim((string) ($data['branch'] ?? $data['branch_id'] ?? '')),
            position: trim((string) ($data['position'] ?? '')),
            month: trim((string) ($data['month'] ?? '')),
            year: trim((string) ($data['year'] ?? '')),
            dateFrom: trim((string) ($data['dateFrom'] ?? '')),
            dateTo: trim((string) ($data['dateTo'] ?? '')),
        );
    }

    /** Status normalizado al valor guardado en DB local. */
    public function normalizedStatus(): string
    {
        return match (strtolower($this->status)) {
            'abierta', 'activa', 'activo', 'open' => 'activa',
            'cerrada', 'cerrado', 'closed' => 'cerrada',
            'pausada', 'pausado', 'paused' => 'pausada',
            default => $this->status,
        };
    }

    public function hasStatus(): bool
    {
        return $this->status !== '';
    }

    public function monthInt(): ?int
    {
        if ($this->month === '' || ! ctype_digit($this->month)) {
            return null;
        }
        $m = (int) $this->month;

        return $m >= 1 && $m <= 12 ? $m : null;
    }

    public function yearInt(): ?int
    {
        if ($this->year === '' || ! ctype_digit($this->year)) {
            return null;
        }
        $y = (int) $this->year;

        return $y >= 2000 && $y <= 2100 ? $y : null;
    }

    /** Rango válido solo si ambas fechas son Y-m-d y from <= to. */
    public function validDateRange(): ?array
    {
        if ($this->dateFrom === '' || $this->dateTo === '') {
            return null;
        }
        if (! self::isDate($this->dateFrom) || ! self::isDate($this->dateTo)) {
            return null;
        }
        if ($this->dateFrom > $this->dateTo) {
            return null;
        }

        return [$this->dateFrom, $this->dateTo];
    }

    private static function isDate(string $v): bool
    {
        $d = \DateTimeImmutable::createFromFormat('Y-m-d', $v);

        return $d !== false && $d->format('Y-m-d') === $v;
    }
}
