<?php

namespace App\Domain\Auth\Contracts;

use App\Domain\Auth\Entities\ExternalUser;

/**
 * Capa Domain: PUERTO (interfaz).
 * Define QUÉ necesitamos, no CÓMO se conecta.
 * La implementación vive en Infrastructure y se inyecta por DI.
 */
interface ExternalUserRepositoryInterface
{
    /**
     * Busca por `usuario` o `email`. Devuelve null si no existe.
     * No valida password aquí: eso lo hace el caso de uso.
     */
    public function findByLogin(string $login): ?ExternalUser;

    /**
     * Devuelve el hash crudo (`contrasena`) para verificar el password.
     * Se separa de la entidad para no exponer el hash en Domain.
     */
    public function getPasswordHash(string $login): ?string;

    /**
     * Devuelve el modelo autenticable de Laravel para iniciar sesión.
     * Retorna mixed para no contaminar Domain con Eloquent.
     */
    public function findAuthenticatable(string $login): mixed;
}
