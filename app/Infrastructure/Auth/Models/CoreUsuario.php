<?php

namespace App\Infrastructure\Auth\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Adaptador de Infraestructura: representa la tabla externa `core_usuario`.
 *
 * Por qué va aquí (y no en Domain):
 * - Conoce detalles técnicos: conexión `external_auth`, nombre de tabla,
 *   primary key no convencional, columnas legacy.
 * - Domain nunca debe importar Eloquent ni saber en qué servidor vive el dato.
 */
class CoreUsuario extends Authenticatable
{
    /** Conexión definida en config/database.php -> connections.external_auth */
    protected $connection = 'external_auth';

    protected $table = 'core_usuario';

    /** PK real de la tabla externa (no es `id`). */
    protected $primaryKey = 'core_usuario_id';

    public $incrementing = true;

    protected $keyType = 'int';

    /** La tabla no tiene created_at / updated_at, solo `actualizado_en`. */
    public $timestamps = false;

    protected $fillable = [
        'usuario',
        'contrasena',
        'nombre',
        'apellidos',
        'email',
    ];

    protected $hidden = [
        'contrasena',
        'huella1',
        'huella2',
        'huella3',
        'huella4',
        'huella5',
    ];

    protected $casts = [
        'vigencia' => 'datetime',
        'actualizado_en' => 'datetime',
        'foto' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | Contrato que Laravel Auth necesita
    |--------------------------------------------------------------------------
    */

    /** Dile a Laravel cuál es tu PK. */
    public function getAuthIdentifierName(): string
    {
        return 'core_usuario_id';
    }

    public function getAuthIdentifier(): mixed
    {
        return $this->getAttribute('core_usuario_id');
    }

    /** Dile a Laravel en qué columna vive el hash. Aquí es `contrasena`. */
    public function getAuthPassword(): string
    {
        return (string) $this->getAttribute('contrasena');
    }

    /** El login puede ser `usuario` o `email`. Este helper normaliza. */
    public function getDisplayName(): string
    {
        return trim((string) $this->getAttribute('nombre').' '.(string) $this->getAttribute('apellidos'));
    }

    /*
    |--------------------------------------------------------------------------
    | Remember-me: la tabla externa NO tiene columna remember_token.
    | Si el usuario marca "Recordar", Laravel intentaría hacer UPDATE y fallaría.
    | Sobrescribimos como no-op para que la sesión siga funcionando sin romper.
    |--------------------------------------------------------------------------
    */
    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // intencionadamente vacío: no persistimos remember_token en core_usuario
    }

    public function getRememberTokenName(): string
    {
        return 'remember_token_disabled';
    }
}
