<?php

namespace App\Infrastructure\Portal\Repositories;

use App\Domain\Portal\Contracts\PortalRepositoryInterface;
use App\Models\Portal\Banner;
use App\Models\Portal\FeedbackMessage;
use App\Models\Portal\NewsItem;
use App\Models\Portal\PortalDocument;
use App\Models\Portal\PortalEvent;
use App\Models\Portal\QuickLink;
use App\Models\Portal\Recognition;
use Illuminate\Support\Facades\Storage;

/**
 * Infrastructure: adaptador Eloquent del puerto del Domain.
 */
class EloquentPortalRepository implements PortalRepositoryInterface
{
    /** @var array<string, class-string> */
    private array $map = [
        'banners' => Banner::class,
        'news' => NewsItem::class,
        'recognitions' => Recognition::class,
        'quick-links' => QuickLink::class,
        'documents' => PortalDocument::class,
        'events' => PortalEvent::class,
    ];

    public function banners(bool $onlyActive = false): array
    {
        return Banner::query()
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderByDesc('id')
            ->get()->map(fn (Banner $b) => $this->serializeBanner($b))->all();
    }

    public function news(?string $search = null, ?string $category = null, bool $onlyPublished = true): array
    {
        return NewsItem::query()
            ->when($onlyPublished, fn ($q) => $q->where('is_published', true))
            ->when($category && $category !== 'todos', fn ($q) => $q->where('category', $category))
            ->when($search, fn ($q) => $q->where(fn ($qq) => $qq
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%")))
            ->orderByDesc('is_featured')->orderByDesc('published_at')->orderByDesc('id')
            ->get()->map(fn (NewsItem $n) => $this->serializeNews($n))->all();
    }

    public function recognitions(bool $onlyActive = false): array
    {
        return Recognition::query()
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderByDesc('id')
            ->get()->map(fn (Recognition $r) => $r->toArray())->all();
    }

    public function quickLinks(bool $onlyActive = false): array
    {
        return QuickLink::query()
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderByDesc('id')
            ->get()->map(fn (QuickLink $l) => $l->toArray())->all();
    }

    public function documents(bool $onlyActive = false): array
    {
        return PortalDocument::query()
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderByDesc('id')
            ->get()->map(fn (PortalDocument $d) => [...$d->toArray(), 'download_url' => $d->downloadUrl()])->all();
    }

    public function events(bool $onlyActive = false): array
    {
        return PortalEvent::query()
            ->when($onlyActive, fn ($q) => $q->where('is_active', true))
            ->orderBy('sort_order')->orderBy('event_date')->orderByDesc('id')
            ->get()->map(function (PortalEvent $e) {
                $arr = $e->toArray();
                $arr['day'] = $e->event_date?->format('d');
                $arr['month'] = $e->event_date ? mb_strtoupper($e->event_date->translatedFormat('M')) : null;
                $arr['badge'] = match ($e->type) {
                    'festivo' => 'Día Festivo',
                    'capacitacion' => 'All-Hands',
                    'cumple' => 'Cumpleaños',
                    default => ucfirst((string) $e->type),
                };

                return $arr;
            })->all();
    }

    public function store(string $resource, array $data): array
    {
        $model = $this->resolve($resource);

        return $model::create($data)->fresh()->toArray();
    }

    public function update(string $resource, int $id, array $data): array
    {
        $model = $this->resolve($resource);
        $record = $model::findOrFail($id);
        $record->update($data);

        return $record->fresh()->toArray();
    }

    public function destroy(string $resource, int $id): void
    {
        $model = $this->resolve($resource);
        $record = $model::find($id);

        if (! $record) {
            return;
        }

        // Limpia archivo híbrido si existe.
        if (in_array($resource, ['banners', 'news', 'documents'], true)) {
            $path = $record->image_path ?? $record->file_path ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $record->delete();
    }

    public function find(string $resource, int $id): ?array
    {
        $model = $this->resolve($resource);

        return $model::find($id)?->toArray();
    }

    public function feedbackInbox(bool $onlyPending = false): array
    {
        return FeedbackMessage::query()
            ->when($onlyPending, fn ($q) => $q->where('is_archived', false))
            ->orderByDesc('id')
            ->get()->map(fn (FeedbackMessage $f) => $f->toArray())->all();
    }

    public function storeFeedback(string $body, bool $anonymous): array
    {
        return FeedbackMessage::create([
            'body' => $body,
            'is_anonymous' => $anonymous,
        ])->toArray();
    }

    public function markFeedbackRead(int $id, bool $read = true): void
    {
        FeedbackMessage::whereKey($id)->update(['is_read' => $read]);
    }

    public function archiveFeedback(int $id, bool $archived = true): void
    {
        FeedbackMessage::whereKey($id)->update(['is_archived' => $archived]);
    }

    public function deleteFeedback(int $id): void
    {
        FeedbackMessage::whereKey($id)->delete();
    }

    private function serializeBanner(Banner $b): array
    {
        $src = $b->imageSrc();

        return [...$b->toArray(),
            'image_src' => $src,
            // Alias compatibilidad con welcome.blade.php legacy:
            'titulo' => $b->title, 'subtitulo' => $b->subtitle, 'imagen' => $src, 'badge' => $b->badge,
        ];
    }

    private function serializeNews(NewsItem $n): array
    {
        $badgeClass = match ($n->category) {
            'recursos' => 'bg-amber-100 text-amber-800',
            'cultura' => 'bg-red-100 text-[#E31B23]',
            default => 'bg-blue-100 text-[#0047AB]',
        };

        return [...$n->toArray(), 'image_src' => $n->imageSrc(), 'badge_class' => $badgeClass,
            'fecha' => $n->published_at?->translatedFormat('d \\d\\e F, Y') ?? $n->created_at?->translatedFormat('d \\d\\e F, Y'),
            // Alias compatibilidad con welcome.blade.php legacy:
            'titulo' => $n->title, 'extracto' => $n->excerpt, 'autor' => $n->author,
            'imagen' => $n->imageSrc(), 'destacado' => $n->is_featured, 'categoria' => $n->category];
    }

    /** @return class-string */
    private function resolve(string $resource): string
    {
        if (! isset($this->map[$resource])) {
            throw new \InvalidArgumentException("Recurso desconocido: {$resource}");
        }

        return $this->map[$resource];
    }
}
