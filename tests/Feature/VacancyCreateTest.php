<?php

use App\Livewire\Vacancies\Index;
use App\Models\User;
use Livewire\Livewire;

test('vacancy create modal lists real external catalogs', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertSee('Selecciona una región')
        ->assertSee('Selecciona una sucursal')
        ->assertDontSee('Veracruz Centro')
        ->assertDontSee('Xalapa Banderilla');
});

test('vacancy can be created with real catalog values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->set('title', 'Asesor de Ventas')
        ->set('region', 'N1')
        ->set('branch', 'POZA RICA')
        ->set('reason', 'nueva_creacion')
        ->set('type', 'publica')
        ->call('save')
        ->assertHasNoErrors();

    expect(App\Models\Vacancy::where('branch', 'POZA RICA')->where('region', 'N1')->count())->toBe(1);
});
