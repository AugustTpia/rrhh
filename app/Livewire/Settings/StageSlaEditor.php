<?php

namespace App\Livewire\Settings;

use App\Application\Dashboard\UpdateStageSlasUseCase;
use App\Domain\Dashboard\Contracts\StageSlaProviderInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('SLA por etapa')]
class StageSlaEditor extends Component
{
    /** @var array<int, array{stage:string, sla_days:int}> */
    public array $slas = [];

    public function mount(StageSlaProviderInterface $provider): void
    {
        $slas = collect($provider->all())
            ->map(fn (int $days, string $stage) => ['stage' => $stage, 'sla_days' => $days])
            ->sortBy('stage')
            ->values()
            ->all();

        $this->slas = $slas;
    }

    public function save(UpdateStageSlasUseCase $useCase): void
    {
        $this->validate([
            'slas.*.sla_days' => 'required|integer|min:1|max:60',
        ], [
            'slas.*.sla_days.required' => 'Captura los días objetivo de cada etapa.',
            'slas.*.sla_days.integer' => 'Los días objetivo deben ser un número entero.',
            'slas.*.sla_days.min' => 'El SLA mínimo es 1 día.',
            'slas.*.sla_days.max' => 'El SLA máximo es 60 días.',
        ]);

        $useCase->handle($this->slas);

        session()->flash('success', 'SLA por etapa actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.settings.stage-sla-editor');
    }
}
