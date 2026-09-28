<?php

namespace App\Livewire\Candidates;

use Livewire\Component;
use App\Models\Candidate;
use App\Models\Vacancy;

class Index extends Component
{
    public bool $showCreateModal = false;
    public string $search = '';
    public string $stageFilter = '';
    public string $vacancyFilter = '';

    // Campos del formulario de alta de candidato
    public ?int $vacancy_id = null;
    public string $full_name = '';
    public string $phone = '';
    public string $email = '';
    public string $source = 'Indeed';
    public ?float $salary_expectation = null;
    public string $stage = 'contacto';
    public string $notes = '';

    public function mount()
    {
        $firstVacancy = Vacancy::first();
        if ($firstVacancy) {
            $this->vacancy_id = $firstVacancy->id;
        }
    }

    public function save()
    {
        $this->validate([
            'vacancy_id' => 'required|exists:vacancies,id',
            'full_name' => 'required|min:3',
            'phone' => 'required',
            'source' => 'required',
            'stage' => 'required',
        ]);

        Candidate::create([
            'vacancy_id' => $this->vacancy_id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'source' => $this->source,
            'salary_expectation' => $this->salary_expectation,
            'stage' => $this->stage,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Candidato registrado e ingresado al pipeline exitosamente.');

        $this->reset(['full_name', 'phone', 'email', 'salary_expectation', 'notes', 'showCreateModal']);
        $this->stage = 'contacto';
    }

    public function updateStage($candidateId, $newStage)
    {
        $candidate = Candidate::find($candidateId);
        if ($candidate) {
            $candidate->update(['stage' => $newStage]);
            session()->flash('success', "Candidato movido a la etapa: " . $this->getStageLabel($newStage));
        }
    }

    public function delete($id)
    {
        $candidate = Candidate::find($id);
        if ($candidate) {
            $candidate->delete();
            session()->flash('success', 'Candidato eliminado del pipeline.');
        }
    }

    private function getStageLabel($stage)
    {
        return match($stage) {
            'contacto' => 'Contacto Inicial',
            'psicotecnicas' => 'Pruebas Psicotécnicas',
            'entrevistas' => 'Entrevistas (RH/Regional)',
            'manejo' => 'Prueba de Manejo',
            'socioeconomico' => 'Estudio Socioeconómico',
            'contratado' => 'Contratado',
            'descartado' => 'Descartado',
            default => $stage,
        };
    }

    public function render()
    {
        $query = Candidate::with('vacancy');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->stageFilter) {
            $query->where('stage', $this->stageFilter);
        }

        if ($this->vacancyFilter) {
            $query->where('vacancy_id', $this->vacancyFilter);
        }

        return view('livewire.candidates.index', [
            'candidates' => $query->latest()->get(),
            'vacancies' => Vacancy::all(),
        ]);
    }
}