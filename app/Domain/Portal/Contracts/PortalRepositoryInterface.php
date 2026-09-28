<?php

namespace App\Domain\Portal\Contracts;

/**
 * Puerto del Domain (hexagonal): la Application solo conoce arrays
 * puros, nunca Eloquent. El adaptador Eloquent vive en Infrastructure.
 */
interface PortalRepositoryInterface
{
    /** @return array<int, array> */
    public function banners(bool $onlyActive = false): array;

    /** @return array<int, array> */
    public function news(?string $search = null, ?string $category = null, bool $onlyPublished = true): array;

    /** @return array<int, array> */
    public function recognitions(bool $onlyActive = false): array;

    /** @return array<int, array> */
    public function quickLinks(bool $onlyActive = false): array;

    /** @return array<int, array> */
    public function documents(bool $onlyActive = false): array;

    /** @return array<int, array> */
    public function events(bool $onlyActive = false): array;

    public function store(string $resource, array $data): array;

    public function update(string $resource, int $id, array $data): array;

    public function destroy(string $resource, int $id): void;

    public function find(string $resource, int $id): ?array;

    /** @return array<int, array> */
    public function feedbackInbox(bool $onlyPending = false): array;

    public function storeFeedback(string $body, bool $anonymous): array;

    public function markFeedbackRead(int $id, bool $read = true): void;

    public function archiveFeedback(int $id, bool $archived = true): void;

    public function deleteFeedback(int $id): void;
}
