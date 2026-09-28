<?php

namespace App\Domain\Auth\Entities;

/**
 * Capa Domain: entidad pura, sin Eloquent, sin Laravel.
 * Solo datos + reglas mínimas que no dependen de framework.
 */
final class ExternalUser
{
    public function __construct(
        public readonly int $id,
        public readonly string $usuario,
        public readonly string $email,
        public readonly string $nombre,
        public readonly string $apellidos,
        public readonly string $estatus,
        public readonly string $esSuper,
        public readonly ?\DateTimeImmutable $vigencia,
    ) {}

    public function fullName(): string
    {
        return trim($this->nombre.' '.$this->apellidos);
    }

    /** Regla de negocio pura: ¿la vigencia ya expiró? */
    public function isExpired(\DateTimeImmutable $now): bool
    {
        if ($this->vigencia === null) {
            return false;
        }

        return $this->vigencia < $now;
    }
}
