<?php

namespace App\Livewire;

use App\Application\Dashboard\GetDashboardStatsUseCase;
use App\Domain\Dashboard\Contracts\VacancyStatsRepositoryInterface;
use App\Domain\Dashboard\ValueObjects\VacancyFilter;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app', ['title' => 'Tablero de vacantes - Atracción de Talento'])]
class VacancyDashboard extends Component
{
    use WithPagination;

    // Filtros alineados al schema local: region/branch son strings (nombre exacto),
    // position filtra por title (LIKE), status acepta abierta/activa/cerrada/pausada.
    public $status = '';

    public $region = '';

    public $branch = '';

    public $position = '';

    public $month = '';

    public $year = '';

    public $dateFrom = '';

    public $dateTo = '';

    // Back-compat: el Blade legacy usaba region_id/branch_id; se aceptan como alias.
    public $region_id = '';

    public $branch_id = '';

    /** Hook Livewire correcto: cualquier cambio de filtro resetea paginación. */
    public function updated($property): void
    {
        if ($property === 'region_id') {
            $this->region = (string) $this->region_id;
        }
        if ($property === 'branch_id') {
            $this->branch = (string) $this->branch_id;
        }
        if ($property === 'region') {
            $this->region_id = (string) $this->region;
        }
        if ($property === 'branch') {
            $this->branch_id = (string) $this->branch;
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['status', 'region', 'region_id', 'branch', 'branch_id', 'position', 'month', 'year', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function render(GetDashboardStatsUseCase $useCase, VacancyStatsRepositoryInterface $vacancies)
    {
        $filter = VacancyFilter::fromArray([
            'status' => $this->status,
            'region' => $this->region !== '' ? $this->region : $this->region_id,
            'branch' => $this->branch !== '' ? $this->branch : $this->branch_id,
            'position' => $this->position,
            'month' => $this->month,
            'year' => $this->year,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
        ]);

        $stats = $useCase->handle($filter);
        $vacanciesList = $vacancies->paginateFiltered($filter, 15);

        return view('livewire.vacancy-dashboard', [
            'avgCoverageDays' => $stats['metrics']['avgCoverageDays'],
            'totalCritical' => $stats['metrics']['totalCritical'],
            'topPosition' => $stats['metrics']['topPosition'],
            'topBranch' => $stats['metrics']['topBranch'],
            'topRegion' => $stats['metrics']['topRegion'],
            'monthlyOpen' => $stats['metrics']['monthlyOpen'],
            'monthlyHired' => $stats['metrics']['monthlyHired'],
            'monthLabel' => $stats['metrics']['monthLabel'],
            'yearLabel' => $stats['metrics']['yearLabel'],
            'yearlyActive' => $stats['metrics']['yearlyActive'],
            'yearlyCovered' => $stats['metrics']['yearlyCovered'],
            'historyPrev' => $stats['metrics']['historyPrev'],
            'historyCurrent' => $stats['metrics']['historyCurrent'],
            // Aliases legacy que el Blade anterior usaba (objetos -> arrays nombre/total).
            'TopCriticasPorPuesto' => $stats['metrics']['topPosition'] ? (object) ['position_name' => $stats['metrics']['topPosition']['name'], 'total' => $stats['metrics']['topPosition']['total']] : null,
            'TopCriticaSucursal' => $stats['metrics']['topBranch'] ? (object) ['branch' => (object) ['name' => $stats['metrics']['topBranch']['name']], 'total' => $stats['metrics']['topBranch']['total']] : null,
            'TopCriticaRegion' => $stats['metrics']['topRegion'] ? (object) ['region' => (object) ['name' => $stats['metrics']['topRegion']['name']], 'total' => $stats['metrics']['topRegion']['total']] : null,
            'history2025' => $stats['metrics']['historyPrev'],
            'history2026' => $stats['metrics']['historyCurrent'],
            'vacanciesList' => $vacanciesList,
            'catalogRegions' => $stats['catalogs']['regions'],
            'catalogBranches' => $stats['catalogs']['branches'],
            'catalogPositions' => $stats['catalogs']['positions'],
            'catalogYears' => $stats['catalogs']['years'],
        ]);
    }
}
