<?php

namespace App\Livewire\Vacancies;

use App\Application\Vacancies\ManageVacanciesUseCase;
use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use Livewire\Component;

class Index extends Component
{
    public bool $showCreateModal = false;

    public string $search = '';

    public string $statusFilter = '';

    public string $title = '';

    public string $branch = '';

    public string $region = '';

    public string $reason = 'nueva_creacion';

    public string $type = 'publica';

    public string $description = '';

    public function updatedRegion($value)
    {
        $this->branch = '';
    }

    public function save(ManageVacanciesUseCase $useCase)
    {
        $this->validate([
            'title' => 'required|min:3',
            'branch' => 'required',
            'region' => 'required',
            'reason' => 'required|in:nueva_creacion,reemplazo',
            'type' => 'required|in:publica,confidencial',
        ]);

        $vacancy = $useCase->create([
            'title' => $this->title,
            'branch' => $this->branch,
            'region' => $this->region,
            'reason' => $this->reason,
            'type' => $this->type,
            'description' => $this->description,
        ]);

        session()->flash('success', "Vacante registrada exitosamente con folio: {$vacancy->folio}");

        $this->reset(['title', 'branch', 'region', 'description', 'showCreateModal']);
        $this->reason = 'nueva_creacion';
        $this->type = 'publica';
    }

    public function delete(int $id, ManageVacanciesUseCase $useCase)
    {
        if ($useCase->delete($id)) {
            session()->flash('success', 'Vacante eliminada correctamente.');
        }
    }

    public function render(CatalogRepositoryInterface $catalogs, ManageVacanciesUseCase $useCase)
    {
        return view('livewire.vacancies.index', [
            'vacancies' => $useCase->list($this->search, $this->statusFilter),
            'regionOptions' => $catalogs->regions(),
            'branchOptions' => $catalogs->branches($this->region !== '' ? $this->region : null),
        ]);
    }
}
