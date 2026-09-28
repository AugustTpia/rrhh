<?php

namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Auth\Contracts\ExternalUserRepositoryInterface;
use App\Domain\Auth\Entities\ExternalUser;
use App\Infrastructure\Auth\Models\CoreUsuario;

/**
 * Capa Infrastructure: ADAPTADOR.
 * Implementa el puerto del Domain usando Eloquent contra `external_auth`.
 * Es la única clase que sabe SQL / conexiones / nombres de columnas legacy.
 */
final class EloquentExternalUserRepository implements ExternalUserRepositoryInterface
{
    public function findByLogin(string $login): ?ExternalUser
    {
        $row = $this->queryByLogin($login);

        if ($row === null) {
            return null;
        }

        return $this->toEntity($row);
    }

    public function getPasswordHash(string $login): ?string
    {
        return $this->queryByLogin($login)?->getAuthPassword();
    }

    public function findAuthenticatable(string $login): mixed
    {
        return $this->queryByLogin($login);
    }

    /**
     * Acepta `usuario` o `email` en el mismo input del formulario.
     * Tu vista actual pide "Correo", pero la tabla tiene ambos UNIQUE/MUL,
     * así soportamos los dos sin cambiar la vista todavía.
     */
    private function queryByLogin(string $login): ?CoreUsuario
    {
        $login = trim($login);

        return CoreUsuario::on('external_auth')
            ->where('usuario', $login)
            ->orWhere('email', $login)
            ->first();
    }

    private function toEntity(CoreUsuario $row): ExternalUser
    {
        $vigencia = $row->getAttribute('vigencia');

        return new ExternalUser(
            id: (int) $row->getAttribute('core_usuario_id'),
            usuario: (string) $row->getAttribute('usuario'),
            email: (string) $row->getAttribute('email'),
            nombre: (string) $row->getAttribute('nombre'),
            apellidos: (string) $row->getAttribute('apellidos'),
            estatus: (string) $row->getAttribute('estatus'),
            esSuper: (string) $row->getAttribute('es_super'),
            vigencia: $vigencia instanceof \DateTimeInterface
                ? \DateTimeImmutable::createFromInterface($vigencia)
                : ($vigencia !== null ? new \DateTimeImmutable((string) $vigencia) : null),
        );
    }
}
