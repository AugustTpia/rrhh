<?php

namespace App\Application\Auth\UseCases;

use App\Domain\Auth\Contracts\ExternalUserRepositoryInterface;
use App\Domain\Auth\Contracts\PasswordVerifierInterface;

/**
 * Capa Application: CASO DE USO.
 * Orquesta: buscar usuario -> validar password -> validar vigencia/estatus.
 * No conoce Eloquent ni MariaDB ni AES, solo habla con puertos (interfaces).
 */
final class AuthenticateExternalUserUseCase
{
    public function __construct(
        private readonly ExternalUserRepositoryInterface $users,
        private readonly PasswordVerifierInterface $passwords,
    ) {}

    /**
     * @return array{ok: bool, reason: string, user_id: ?int, authenticatable: mixed}
     */
    public function handle(string $login, string $plainPassword): array
    {
        $entity = $this->users->findByLogin($login);

        if ($entity === null) {
            return $this->fail('Usuario no encontrado.');
        }

        $stored = $this->users->getPasswordHash($login);

        if ($stored === null || ! $this->passwords->verify($plainPassword, $stored)) {
            return $this->fail('Credenciales inválidas.');
        }

        // Regla mínima pedida: "con que sea válido en core_usuario puede ingresar".
        // Dejamos vigencia como única restricción dura por ahora.
        if ($entity->isExpired(new \DateTimeImmutable())) {
            return $this->fail('Usuario con vigencia expirada.');
        }

        // TODO fase 2 (cuando definamos catálogo de estatus):
        // rechazar estatus inactivo, ej: if ($entity->estatus === 'BA') fail.
        // Hoy lo dejamos permisivo porque no conocemos los valores de `estatus`.

        return [
            'ok' => true,
            'reason' => 'ok',
            'user_id' => $entity->id,
            'authenticatable' => $this->users->findAuthenticatable($login),
        ];
    }

    /** @return array{ok: false, reason: string, user_id: null, authenticatable: null} */
    private function fail(string $reason): array
    {
        return ['ok' => false, 'reason' => $reason, 'user_id' => null, 'authenticatable' => null];
    }
}
