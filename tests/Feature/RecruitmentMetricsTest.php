<?php

use App\Application\Dashboard\GetDashboardStatsUseCase;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use App\Infrastructure\Dashboard\DbStageSlaProvider;
use App\Infrastructure\Dashboard\EloquentRecruitmentMetricsRepository;
use App\Infrastructure\Dashboard\EloquentVacancyStatsRepository;
use App\Infrastructure\Dashboard\ExternalCatalogRepository;
use App\Models\Candidate;
use App\Models\CandidateStage;
use App\Models\Hire;
use App\Models\HiringCost;
use App\Models\JobOffer;
use App\Models\PositionRequiredStage;
use App\Models\Vacancy;
use Database\Seeders\PositionRequiredStagesSeeder;

function useCaseWithMetrics(): GetDashboardStatsUseCase
{
    return new GetDashboardStatsUseCase(
        new EloquentVacancyStatsRepository,
        new ExternalCatalogRepository,
        new EloquentRecruitmentMetricsRepository,
        new DbStageSlaProvider,
    );
}

function completeStages(Candidate $candidate, string $position, int $startDaysAgo = 20, int $spacingDays = 1): void
{
    $stages = PositionRequiredStage::where('position_title', $position)->pluck('stage');

    foreach ($stages->values() as $index => $stage) {
        CandidateStage::create([
            'candidate_id' => $candidate->id,
            'vacancy_id' => $candidate->vacancy_id,
            'stage' => $stage,
            'completed_at' => now()->subDays($startDaysAgo - ($index * $spacingDays)),
        ]);
    }
}

beforeEach(function () {
    $this->seed(PositionRequiredStagesSeeder::class);
});

test('recruitment metrics compute hiring, offers, retention and cost', function () {
    $driverVacancy = Vacancy::factory()->create([
        'title' => 'Chofer',
        'status' => 'cerrada',
        'region' => 'N1',
        'branch' => 'POZA RICA',
        'created_at' => now()->subDays(140),
        'closed_at' => now()->subDays(120),
    ]);

    $hired = Candidate::create([
        'vacancy_id' => $driverVacancy->id,
        'full_name' => 'Chofer Contratado',
        'phone' => '555',
        'source' => 'Indeed',
        'stage' => 'contratado',
    ]);
    completeStages($hired, 'Chofer');

    JobOffer::create([
        'candidate_id' => $hired->id,
        'vacancy_id' => $driverVacancy->id,
        'status' => 'aceptada',
        'offered_at' => now()->subDays(125),
    ]);

    Hire::create([
        'candidate_id' => $hired->id,
        'vacancy_id' => $driverVacancy->id,
        'hire_date' => now()->subDays(120)->toDateString(),
    ]);

    $salesVacancy = Vacancy::factory()->create([
        'title' => 'Asesor de Ventas',
        'status' => 'activa',
        'region' => 'N1',
        'branch' => 'POZA RICA',
        'created_at' => now()->subDays(30),
    ]);

    $finalist = Candidate::create([
        'vacancy_id' => $salesVacancy->id,
        'full_name' => 'Ventas Finalista',
        'phone' => '555',
        'source' => 'OCC',
        'stage' => 'finalista',
    ]);
    completeStages($finalist, 'Asesor de Ventas', 20);

    JobOffer::create([
        'candidate_id' => $finalist->id,
        'vacancy_id' => $salesVacancy->id,
        'status' => 'rechazada',
        'offered_at' => now()->subDays(5),
    ]);

    HiringCost::create([
        'period' => now()->format('Y-m'),
        'total_investment' => 12000,
        'hires_count' => 1,
    ]);

    $stats = useCaseWithMetrics()->handle(VacancyFilter::fromArray([]))['recruitment'];

    expect($stats['hiring'])->toMatchArray(['contracted' => 1, 'finalists' => 2, 'rate' => 50])
        ->and($stats['offers'])->toMatchArray(['emitted' => 2, 'accepted' => 1, 'rate' => 50])
        ->and($stats['retention'])->toMatchArray(['eligible' => 1, 'retained' => 1, 'rate' => 100])
        ->and($stats['turnover'])->toMatchArray(['hires' => 1, 'early' => 0, 'rate' => 0])
        ->and($stats['sources']['top']['source'])->toBe('Indeed')
        ->and($stats['cost']['per_hire'])->toBe(12000.0)
        ->and($stats['stage_durations'])->not->toBeEmpty();
});

test('recruitment metrics respect the region filter', function () {
    $vacancy = Vacancy::factory()->create([
        'title' => 'Asesor de Ventas',
        'region' => 'G2',
        'branch' => 'CD MENDOZA',
    ]);

    $candidate = Candidate::create([
        'vacancy_id' => $vacancy->id,
        'full_name' => 'Candidato G2',
        'phone' => '555',
        'source' => 'Indeed',
        'stage' => 'contratado',
    ]);
    completeStages($candidate, 'Asesor de Ventas');

    $matching = useCaseWithMetrics()->handle(VacancyFilter::fromArray(['region' => 'G2']))['recruitment'];
    $other = useCaseWithMetrics()->handle(VacancyFilter::fromArray(['region' => 'N1']))['recruitment'];

    expect($matching['hiring']['contracted'])->toBe(1)
        ->and($other['hiring']['contracted'])->toBe(0);
});

function vacancyForStages(): Vacancy
{
    return Vacancy::factory()->create([
        'title' => 'Asesor de Ventas',
        'region' => 'N1',
        'branch' => 'POZA RICA',
    ]);
}

test('stage durations expose sla target and on target result', function () {
    $vacancy = vacancyForStages();

    $fast = Candidate::create([
        'vacancy_id' => $vacancy->id,
        'full_name' => 'Candidato Rápido',
        'phone' => '555',
        'source' => 'Indeed',
        'stage' => 'finalista',
    ]);
    completeStages($fast, 'Asesor de Ventas', 12, 1);

    $durations = collect(useCaseWithMetrics()->handle(VacancyFilter::fromArray([]))['recruitment']['stage_durations']);
    $lucher = $durations->firstWhere('stage', 'test_lucher');

    expect($lucher['sla_days'])->toBe(3)
        ->and($lucher['avg_days'])->toBe(1.0)
        ->and($lucher['on_target'])->toBeTrue();
});

test('stage durations flag stages over sla', function () {
    $vacancy = vacancyForStages();

    $slow = Candidate::create([
        'vacancy_id' => $vacancy->id,
        'full_name' => 'Candidato Lento',
        'phone' => '555',
        'source' => 'OCC',
        'stage' => 'entrevistas',
    ]);
    completeStages($slow, 'Asesor de Ventas', 30, 5);

    $durations = collect(useCaseWithMetrics()->handle(VacancyFilter::fromArray([]))['recruitment']['stage_durations']);
    $lucher = $durations->firstWhere('stage', 'test_lucher');

    expect($lucher['avg_days'])->toBe(5.0)
        ->and($lucher['sla_days'])->toBe(3)
        ->and($lucher['on_target'])->toBeFalse();
});
