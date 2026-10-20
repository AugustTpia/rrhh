<?php

use App\Application\Dashboard\GetDashboardStatsUseCase;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use App\Infrastructure\Dashboard\DbStageSlaProvider;
use App\Infrastructure\Dashboard\EloquentRecruitmentMetricsRepository;
use App\Infrastructure\Dashboard\EloquentVacancyStatsRepository;
use App\Infrastructure\Dashboard\ExternalCatalogRepository;
use App\Models\Vacancy;

test('usecase computes kpis from real local data', function () {
    Vacancy::factory()->create([
        'status' => 'activa',
        'title' => 'Chofer',
        'branch' => 'SUC-A',
        'region' => 'REG-A',
        'created_at' => now()->subDays(30),
        'updated_at' => now()->subDays(30),
    ]);
    Vacancy::factory()->create([
        'status' => 'cerrada',
        'title' => 'Chofer',
        'branch' => 'SUC-A',
        'region' => 'REG-A',
        'created_at' => now()->subDays(40),
        'closed_at' => now()->subDays(30),
        'updated_at' => now()->subDays(30),
    ]);

    $useCase = new GetDashboardStatsUseCase(
        new EloquentVacancyStatsRepository,
        new ExternalCatalogRepository,
        new EloquentRecruitmentMetricsRepository,
        new DbStageSlaProvider,
    );

    $stats = $useCase->handle(VacancyFilter::fromArray([]));

    expect($stats['metrics']['total'])->toBe(2)
        ->and($stats['metrics']['totalCritical'])->toBe(1)
        ->and($stats['metrics']['avgCoverageDays'])->toBeGreaterThan(0);

    $filtered = $useCase->handle(VacancyFilter::fromArray(['status' => 'cerrada']));
    expect($filtered['metrics']['total'])->toBe(1)
        ->and($filtered['metrics']['totalCritical'])->toBe(0);
});

test('vacancy filter normalizes status and validates ranges', function () {
    $f = VacancyFilter::fromArray(['status' => 'abierta']);
    expect($f->normalizedStatus())->toBe('activa');

    $ok = VacancyFilter::fromArray(['dateFrom' => '2026-01-01', 'dateTo' => '2026-01-31']);
    expect($ok->validDateRange())->toBe(['2026-01-01', '2026-01-31']);

    $bad = VacancyFilter::fromArray(['dateFrom' => '2026-02-01', 'dateTo' => '2026-01-01']);
    expect($bad->validDateRange())->toBeNull();
});
