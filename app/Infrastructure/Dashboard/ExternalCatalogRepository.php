<?php

namespace App\Infrastructure\Dashboard;

use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use App\Models\Vacancy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Capa Infrastructure: ADAPTADOR de catálogos.
 * Lee zona/sucursal/región/ciudad/estado desde external_auth (solo lectura, cache 10 min).
 * Si la externa falla, fallback a DISTINCT local para no romper filtros.
 *
 * Nota: en cache solo se guardan arrays planos (nunca Collections) para evitar
 * formas rancias entre despliegues; las claves llevan versión (v2) para invalidar
 * entradas viejas.
 */
final class ExternalCatalogRepository implements CatalogRepositoryInterface
{
    /**
     * Regiones válidas para el alta y filtros: solo zonas G/N de la tabla `zona`
     * (G1..G4, N1, N2). Directriz del equipo: la tabla `regiones` (ZONA CORDOBA…)
     * NO debe aparecer en el dropdown de Región.
     */
    public function regions(): array
    {
        try {
            $zonas = Cache::remember('catalog.v3.regions', 600, fn () => array_values(array_filter(
                $this->names(
                    DB::connection('external_auth')->table('zona')->orderBy('nombre')->get(['nombre']),
                    'nombre'
                ),
                fn (string $name) => preg_match('/^[GN]\d+$/i', $name) === 1
            )));

            return $this->unique($this->toOptions($zonas));
        } catch (\Throwable $e) {
            Log::warning('Catalog regions fallback local: '.$e->getMessage());

            return $this->toOptions($this->distinctLocal('region'));
        }
    }

    public function branches(?string $region = null): array
    {
        try {
            $region = trim((string) $region);
            $zonaId = null;
            if ($region !== '') {
                $zonaId = DB::connection('external_auth')->table('zona')->where('nombre', $region)->value('zona_id');
            }

            // sucursal no guarda región textual directa; se filtra por zona si coincide.
            $names = Cache::remember(
                'catalog.v2.sucursales.'.md5($region),
                600,
                function () use ($zonaId) {
                    $q = DB::connection('external_auth')->table('sucursal')
                        ->where('estatus', 'AC')
                        ->where('eliminado', 0)
                        ->orderBy('nombre');
                    if ($zonaId) {
                        $q->where('zona_id', $zonaId);
                    }

                    return $this->names($q->get(['nombre']), 'nombre');
                }
            );

            return $this->unique($this->toOptions($names));
        } catch (\Throwable $e) {
            Log::warning('Catalog branches fallback local: '.$e->getMessage());

            return $this->toOptions($this->distinctLocal('branch'));
        }
    }

    public function zones(): array
    {
        try {
            $names = Cache::remember('catalog.v2.zonas.raw', 600, fn () => $this->names(
                DB::connection('external_auth')->table('zona')->orderBy('nombre')->get(['nombre']),
                'nombre'
            ));

            return $this->unique($this->toOptions($names));
        } catch (\Throwable $e) {
            return $this->regions();
        }
    }

    public function cities(): array
    {
        try {
            $names = Cache::remember('catalog.v2.ciudades', 600, fn () => $this->names(
                DB::connection('external_auth')->table('catalogociudades')->orderBy('nombre')->limit(500)->get(['nombre']),
                'nombre'
            ));

            return $this->unique($this->toOptions($names));
        } catch (\Throwable $e) {
            Log::warning('Catalog cities fallback: '.$e->getMessage());

            return [];
        }
    }

    public function states(): array
    {
        try {
            $names = Cache::remember('catalog.v2.estados', 600, fn () => $this->names(
                DB::connection('external_auth')->table('cat_inegi_estados')->orderBy('nom_ent')->get(['nom_ent']),
                'nom_ent'
            ));

            return $this->unique($this->toOptions($names));
        } catch (\Throwable $e) {
            Log::warning('Catalog states fallback: '.$e->getMessage());

            return [];
        }
    }

    public function positions(): array
    {
        return $this->toOptions($this->distinctLocal('title'));
    }

    /**
     * Extrae una columna de texto de filas que pueden venir como objeto, array o
     * escalar (la caché persistente puede devolver formas viejas). Omite vacíos.
     *
     * @return array<int, string>
     */
    private function names(iterable $rows, string $column): array
    {
        $out = [];
        foreach ($rows as $row) {
            $value = null;
            if (is_object($row)) {
                $value = $row->{$column} ?? null;
            } elseif (is_array($row)) {
                $value = $row[$column] ?? null;
            } elseif (is_string($row) || is_numeric($row)) {
                $value = $row;
            }
            if ($value === null) {
                continue;
            }
            $value = trim((string) $value);
            if ($value !== '') {
                $out[] = $value;
            }
        }

        return $out;
    }

    private function distinctLocal(string $column): array
    {
        return Vacancy::query()
            ->distinct()
            ->whereNotNull($column)
            ->orderBy($column)
            ->pluck($column)
            ->filter()
            ->values()
            ->all();
    }

    private function toOptions(array $values): array
    {
        $out = [];
        foreach ($values as $v) {
            $out[] = ['value' => (string) $v, 'label' => (string) $v];
        }

        return $out;
    }

    private function unique(array $options): array
    {
        $seen = [];
        $out = [];
        foreach ($options as $o) {
            if (! isset($seen[$o['value']])) {
                $seen[$o['value']] = true;
                $out[] = $o;
            }
        }

        return $out;
    }
}
