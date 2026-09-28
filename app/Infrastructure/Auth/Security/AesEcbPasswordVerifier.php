<?php

namespace App\Infrastructure\Auth\Security;

use App\Domain\Auth\Contracts\PasswordVerifierInterface;

/**
 * Capa Infrastructure: ADAPTADOR AES-192-ECB.
 *
 * Replica lo que hace el JavaScript legacy:
 *  - Algoritmo AES, clave 192 bits (24 bytes), modo ECB, relleno PKCS7, salida Base64.
 *  - En PHP: openssl con cipher `aes-192-ecb` + flag OPENSSL_RAW_DATA ya usa PKCS7.
 *
 * Por qué va aquí: conoce openssl, Base64 y la clave. Domain no debe saberlo.
 */
final class AesEcbPasswordVerifier implements PasswordVerifierInterface
{
    public function __construct(
        private readonly string $key,
    ) {}

    public function verify(string $plainPassword, string $storedValue): bool
    {
        $storedValue = trim($storedValue);

        if ($storedValue === '' || $plainPassword === '') {
            return false;
        }

        // 1) Intento principal: descifrar lo guardado y comparar con lo que escribió el usuario.
        $decrypted = $this->decrypt($storedValue);

        if ($decrypted !== null && hash_equals($decrypted, $plainPassword)) {
            return true;
        }

        // 2) Respaldo: cifrar lo que escribió y comparar con lo guardado.
        // En ECB el cifrado es determinista, ambos caminos deben coincidir.
        $encrypted = $this->encrypt($plainPassword);

        return $encrypted !== null && hash_equals($storedValue, $encrypted);
    }

    private function decrypt(string $base64): ?string
    {
        $raw = base64_decode($base64, strict: true);

        if ($raw === false) {
            return null;
        }

        $out = openssl_decrypt($raw, 'aes-192-ecb', $this->normalizeKey(), OPENSSL_RAW_DATA);

        return $out === false ? null : $out;
    }

    private function encrypt(string $plain): ?string
    {
        $raw = openssl_encrypt($plain, 'aes-192-ecb', $this->normalizeKey(), OPENSSL_RAW_DATA);

        return $raw === false ? null : base64_encode($raw);
    }

    /**
     * AES-192 exige exactamente 24 bytes.
     * El JS usa la cadena tal cual en UTF-8: si es más larga se trunca,
     * si es más corta se rellena con bytes nulos. Replicamos eso.
     */
    private function normalizeKey(): string
    {
        $key = $this->key;

        if (strlen($key) > 24) {
            return substr($key, 0, 24);
        }

        return str_pad($key, 24, "\0");
    }
}
