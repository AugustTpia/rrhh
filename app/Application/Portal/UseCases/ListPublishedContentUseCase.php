<?php

namespace App\Application\Portal\UseCases;

use App\Domain\Portal\Contracts\PortalRepositoryInterface;

/**
 * Application: orquesta la lectura pública de la landing.
 * Sin dependencias de Eloquent ni HTTP.
 */
class ListPublishedContentUseCase
{
    public function __construct(private PortalRepositoryInterface $portal) {}

    /**
     * @return array{banners: array, news: array, recognitions: array, quickLinks: array, documents: array, events: array}
     */
    public function handle(?string $search = null, ?string $category = null): array
    {
        return [
            'banners' => $this->portal->banners(true),
            'news' => $this->portal->news($search, $category, true),
            'recognitions' => $this->portal->recognitions(true),
            'quickLinks' => $this->portal->quickLinks(true),
            'documents' => $this->portal->documents(true),
            'events' => $this->portal->events(true),
        ];
    }
}
