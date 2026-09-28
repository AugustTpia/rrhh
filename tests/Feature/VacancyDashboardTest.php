<?php

use App\Livewire\VacancyDashboard;
use App\Models\User;
use App\Models\Vacancy;
use Livewire\Livewire;

test('dashboard filters by status with real data', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Vacancy::factory()->create(['status' => 'activa', 'title' => 'Chofer', 'branch' => 'POZA RICA', 'region' => 'Golfo 1']);
    Vacancy::factory()->create(['status' => 'cerrada', 'title' => 'Chofer', 'branch' => 'POZA RICA', 'region' => 'Golfo 1', 'closed_at' => now()]);

    Livewire::test(VacancyDashboard::class)
        ->assertSee('Chofer')
        ->set('status', 'activa')
        ->assertSee('1 vacantes')
        ->set('status', 'cerrada')
        ->assertSee('1 vacantes');
});

test('dashboard filters by region branch position and resets', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Vacancy::factory()->create(['status' => 'activa', 'title' => 'Asesor de Ventas', 'branch' => 'SUC-A', 'region' => 'REG-A']);
    Vacancy::factory()->create(['status' => 'activa', 'title' => 'Chofer', 'branch' => 'SUC-B', 'region' => 'REG-B']);

    Livewire::test(VacancyDashboard::class)
        ->set('region', 'REG-A')
        ->assertSee('1 vacantes')
        ->set('region', '')
        ->set('branch', 'SUC-B')
        ->assertSee('1 vacantes')
        ->set('branch', '')
        ->set('position', 'Chofer')
        ->assertSee('1 vacantes')
        ->call('resetFilters')
        ->assertSee('2 vacantes');
});

test('dashboard month year and date range filters', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Vacancy::factory()->create(['status' => 'activa', 'created_at' => '2025-03-10 10:00:00', 'updated_at' => '2025-03-10 10:00:00']);
    Vacancy::factory()->create(['status' => 'activa', 'created_at' => '2026-07-15 10:00:00', 'updated_at' => '2026-07-15 10:00:00']);

    Livewire::test(VacancyDashboard::class)
        ->set('year', '2025')
        ->assertSee('1 vacantes')
        ->set('year', '')
        ->set('month', '7')
        ->assertSee('1 vacantes')
        ->set('month', '')
        ->set('dateFrom', '2026-01-01')
        ->set('dateTo', '2026-12-31')
        ->assertSee('1 vacantes');
});
