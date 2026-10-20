<?php

namespace App\Infrastructure\Vacancies;

use App\Domain\Vacancies\Contracts\VacancyRepositoryInterface;
use App\Domain\Vacancies\VacancySummary;
use App\Models\Vacancy;

/**
 * Capa Infrastructure: adaptador Eloquent del puerto de vacantes.
 */
final class EloquentVacancyRepository implements VacancyRepositoryInterface
{
    public function all(string $search = '', string $status = ''): array
    {
        return Vacancy::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', '%'.$search.'%')
                        ->orWhere('folio', 'like', '%'.$search.'%')
                        ->orWhere('branch', 'like', '%'.$search.'%');
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->latest()
            ->get()
            ->map(fn (Vacancy $vacancy) => $this->summary($vacancy))
            ->all();
    }

    public function create(array $attributes): VacancySummary
    {
        return $this->summary(Vacancy::create($attributes));
    }

    public function delete(int $id): bool
    {
        return Vacancy::query()->whereKey($id)->delete() > 0;
    }

    private function summary(Vacancy $vacancy): VacancySummary
    {
        return new VacancySummary(
            id: (int) $vacancy->id,
            folio: (string) $vacancy->folio,
            title: (string) $vacancy->title,
            branch: (string) $vacancy->branch,
            region: (string) $vacancy->region,
            reason: (string) $vacancy->reason,
            type: (string) $vacancy->type,
            status: (string) $vacancy->status,
        );
    }
}
