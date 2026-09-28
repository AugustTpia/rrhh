<?php

namespace App\Livewire\Vacancies;

use App\Domain\Dashboard\Contracts\CatalogRepositoryInterface;
use App\Models\Vacancy;
use Illuminate\Support\Str;
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

    public function save()
    {
        $this->validate([
            'title' => 'required|min:3',
            'branch' => 'required',
            'region' => 'required',
            'reason' => 'required|in:nueva_creacion,reemplazo',
            'type' => 'required|in:publica,confidencial',
        ]);

        $folio = 'VAC-'.date('Y').'-'.strtoupper(Str::random(5));

        Vacancy::create([
            'folio' => $folio,
            'title' => $this->title,
            'branch' => $this->branch,
            'region' => $this->region,
            'reason' => $this->reason,
            'type' => $this->type,
            'description' => $this->description,
            'status' => 'activa',
        ]);

        session()->flash('success', "Vacante registrada exitosamente con folio: {$folio}");

        $this->reset(['title', 'branch', 'region', 'description', 'showCreateModal']);
        $this->reason = 'nueva_creacion';
        $this->type = 'publica';
    }

    public function delete($id)
    {
        $vacancy = Vacancy::find($id);
        if ($vacancy) {
            $vacancy->delete();
            session()->flash('success', 'Vacante eliminada correctamente.');
        }
    }

    public function render(CatalogRepositoryInterface $catalogs)
    {
        $query = Vacancy::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('folio', 'like', '%'.$this->search.'%')
                    ->orWhere('branch', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.vacancies.index', [
            'vacancies' => $query->latest()->get(),
            'regionOptions' => $catalogs->regions(),
            'branchOptions' => $catalogs->branches($this->region !== '' ? $this->region : null),
        ]);
    }
}
