<?php

namespace App\Domain\Auth\Contracts;

/**
 * Capa Domain: PUERTO para verificar passwords.
 * El Domain dice "necesito verificar", Infrastructure decide cómo (bcrypt, AES, etc).
 */
interface PasswordVerifierInterface
{
    public function verify(string $plainPassword, string $storedValue): bool;
}
