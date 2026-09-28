<?php

namespace App\Application\Portal\UseCases;

use App\Domain\Portal\Contracts\PortalRepositoryInterface;

/**
 * Application: buzón público + moderación.
 */
class SubmitFeedbackUseCase
{
    public function __construct(private PortalRepositoryInterface $portal) {}

    public function handle(string $body, bool $anonymous = false): array
    {
        $body = trim($body);

        if (mb_strlen($body) < 3) {
            throw new \InvalidArgumentException('Escribe un comentario de al menos 3 caracteres.');
        }

        return $this->portal->storeFeedback(mb_substr($body, 0, 2000), $anonymous);
    }
}
