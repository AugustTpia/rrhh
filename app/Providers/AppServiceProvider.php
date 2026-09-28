<?php

namespace App\Providers;

use App\Domain\Auth\Contracts\ExternalUserRepositoryInterface;
use App\Domain\Auth\Contracts\PasswordVerifierInterface;
use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use App\Domain\Dashboard\Contracts\VacancyStatsRepositoryInterface;
use App\Domain\Portal\Contracts\PortalRepositoryInterface;
use App\Infrastructure\Auth\Repositories\EloquentExternalUserRepository;
use App\Infrastructure\Auth\Security\AesEcbPasswordVerifier;
use App\Infrastructure\Dashboard\EloquentVacancyStatsRepository;
use App\Infrastructure\Dashboard\ExternalCatalogRepository;
use App\Infrastructure\Portal\Repositories\EloquentPortalRepository;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Hexagonal: Puerto (Domain) -> Adaptador (Infrastructure).
        // El caso de uso solo pide la interfaz, Laravel inyecta el Eloquent externo.
        $this->app->bind(
            ExternalUserRepositoryInterface::class,
            EloquentExternalUserRepository::class,
        );

        // Verificador AES legacy: la clave vive en .env, nunca en código.
        $this->app->bind(
            PasswordVerifierInterface::class,
            fn () => new AesEcbPasswordVerifier(
                (string) config('database.connections.external_auth.password_key', ''),
            ),
        );

        // Hexagonal Dashboard: puertos -> adaptadores.
        $this->app->bind(
            VacancyStatsRepositoryInterface::class,
            EloquentVacancyStatsRepository::class,
        );
        $this->app->bind(
            CatalogRepositoryInterface::class,
            ExternalCatalogRepository::class,
        );

        // Hexagonal Portal: la landing y el admin dependen del puerto, no de Eloquent.
        $this->app->bind(
            PortalRepositoryInterface::class,
            EloquentPortalRepository::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
