<?php

namespace App\Providers;

use App\Domain\Auth\Contracts\ExternalUserRepositoryInterface;
use App\Domain\Auth\Contracts\PasswordVerifierInterface;
use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use App\Domain\Dashboard\Contracts\RecruitmentMetricsRepositoryInterface;
use App\Domain\Dashboard\Contracts\StageSlaProviderInterface;
use App\Domain\Dashboard\Contracts\StageSlaWriterInterface;
use App\Domain\Dashboard\Contracts\VacancyStatsRepositoryInterface;
use App\Domain\Portal\Contracts\PortalRepositoryInterface;
use App\Domain\Vacancies\Contracts\VacancyRepositoryInterface;
use App\Infrastructure\Auth\Repositories\EloquentExternalUserRepository;
use App\Infrastructure\Auth\Security\AesEcbPasswordVerifier;
use App\Infrastructure\Dashboard\DbStageSlaProvider;
use App\Infrastructure\Dashboard\EloquentRecruitmentMetricsRepository;
use App\Infrastructure\Dashboard\EloquentVacancyStatsRepository;
use App\Infrastructure\Dashboard\ExternalCatalogRepository;
use App\Infrastructure\Portal\Repositories\EloquentPortalRepository;
use App\Infrastructure\Vacancies\EloquentVacancyRepository;
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
        $this->app->bind(
            RecruitmentMetricsRepositoryInterface::class,
            EloquentRecruitmentMetricsRepository::class,
        );
        $this->app->bind(
            StageSlaProviderInterface::class,
            DbStageSlaProvider::class,
        );
        $this->app->bind(
            StageSlaWriterInterface::class,
            DbStageSlaProvider::class,
        );

        // Hexagonal Vacantes: el adaptador Livewire depende del puerto, no de Eloquent.
        $this->app->bind(
            VacancyRepositoryInterface::class,
            EloquentVacancyRepository::class,
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
