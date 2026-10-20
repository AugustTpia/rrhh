<?php

namespace App\Application\Vacancies;

use App\Domain\Vacancies\Contracts\VacancyRepositoryInterface;
use App\Domain\Vacancies\VacancySummary;

/**
 * Capa Application: casos de uso de administración de vacantes.
 * El folio y el estatus inicial son reglas de negocio, no del componente Livewire.
 */
final class ManageVacanciesUseCase
{
    public function __construct(
        private readonly VacancyRepositoryInterface $vacancies,
    ) {}

    /**
     * @return array<int, VacancySummary>
     */
    public function list(string $search = '', string $status = ''): array
    {
        return $this->vacancies->all($search, $status);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): VacancySummary
    {
        return $this->vacancies->create([
            'folio' => $this->nextFolio(),
            'title' => $data['title'],
            'branch' => $data['branch'],
            'region' => $data['region'],
            'reason' => $data['reason'],
            'type' => $data['type'],
            'description' => $data['description'] ?? '',
            'status' => 'activa',
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->vacancies->delete($id);
    }

    private function nextFolio(): string
    {
        return 'VAC-'.date('Y').'-'.strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));
    }
}
