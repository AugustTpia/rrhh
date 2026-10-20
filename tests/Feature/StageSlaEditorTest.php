<?php

use App\Domain\Dashboard\Contracts\StageSlaProviderInterface;
use App\Livewire\Settings\StageSlaEditor;
use App\Models\StageSlaConfig;
use App\Models\User;
use Database\Seeders\PositionRequiredStagesSeeder;
use Database\Seeders\StageSlaConfigSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(PositionRequiredStagesSeeder::class);
    $this->seed(StageSlaConfigSeeder::class);
    $this->actingAs(User::factory()->create());
});

test('sla editor renders configured stages', function () {
    $this->get(route('admin.sla'))->assertOk()->assertSee('Entrevista Rh');
});

test('sla editor updates the target used by the dashboard', function () {
    $component = Livewire::test(StageSlaEditor::class);
    $index = collect($component->get('slas'))->search(fn ($row) => $row['stage'] === 'entrevista_rh');

    expect($index)->not->toBeFalse();

    $component->set("slas.{$index}.sla_days", 7)->call('save')->assertHasNoErrors();

    expect(StageSlaConfig::where('stage', 'entrevista_rh')->value('sla_days'))->toBe(7)
        ->and(app(StageSlaProviderInterface::class)->daysFor('entrevista_rh'))->toBe(7);
});

test('sla editor rejects non positive targets', function () {
    $component = Livewire::test(StageSlaEditor::class);
    $index = collect($component->get('slas'))->search(fn ($row) => $row['stage'] === 'entrevista_rh');

    $component->set("slas.{$index}.sla_days", 0)->call('save')->assertHasErrors("slas.{$index}.sla_days");
});
