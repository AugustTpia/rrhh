<?php

namespace App\Application\Portal\UseCases;

use App\Domain\Portal\Contracts\PortalRepositoryInterface;

/**
 * Application: CRUD genérico del contenido del portal (usado por Livewire admin).
 */
class ManagePortalContentUseCase
{
    public function __construct(private PortalRepositoryInterface $portal) {}

    public function list(string $resource): array
    {
        return match ($resource) {
            'banners' => $this->portal->banners(),
            'news' => $this->portal->news(),
            'recognitions' => $this->portal->recognitions(),
            'quick-links' => $this->portal->quickLinks(),
            'documents' => $this->portal->documents(),
            'events' => $this->portal->events(),
            default => throw new \InvalidArgumentException("Recurso desconocido: {$resource}"),
        };
    }

    public function create(string $resource, array $data): array
    {
        return $this->portal->store($resource, $data);
    }

    public function update(string $resource, int $id, array $data): array
    {
        return $this->portal->update($resource, $id, $data);
    }

    public function delete(string $resource, int $id): void
    {
        $this->portal->destroy($resource, $id);
    }
}
