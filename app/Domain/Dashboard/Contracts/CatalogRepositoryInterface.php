<?php

namespace App\Domain\Dashboard\Contracts;

/**
 * Capa Domain: PUERTO de catálogos (DB_EXTERNAL / external_auth).
 * Solo lectura para dropdowns. Implementación en Infrastructure con fallback local.
 */
interface CatalogRepositoryInterface
{
    /** @return array<int, array{value:string,label:string}> */
    public function regions(): array;

    /** @return array<int, array{value:string,label:string}> */
    public function branches(?string $region = null): array;

    /** @return array<int, array{value:string,label:string}> */
    public function zones(): array;

    /** @return array<int, array{value:string,label:string}> */
    public function cities(): array;

    /** @return array<int, array{value:string,label:string}> */
    public function states(): array;

    /** Puestos distintos desde vacancies locales (title) para el filtro "Oferta de empleo". */
    public function positions(): array;
}
