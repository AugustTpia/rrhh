<?php

namespace App\Livewire\Portal;

use App\Application\Portal\UseCases\ManagePortalContentUseCase;
use App\Domain\Portal\Contracts\PortalRepositoryInterface;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class PortalManager extends Component
{
    use WithFileUploads;

    public string $section = 'banners';

    public bool $showModal = false;

    public ?int $editingId = null;

    // Formulario genérico (se usa el subconjunto según sección).
    public string $title = '';

    public string $subtitle = '';

    public string $badge = '';

    public ?string $image_url = null;

    public $imageUpload = null;

    public string $category = 'comunicados';

    public string $author = '';

    public string $excerpt = '';

    public string $published_at = '';

    public bool $is_featured = false;

    public bool $is_published = true;

    public bool $is_active = true;

    public string $type = '';

    public string $body = '';

    public string $icon = '';

    public string $label = '';

    public string $url = '#';

    public string $name = '';

    public ?string $external_url = null;

    public $fileUpload = null;

    public string $event_date = '';

    public string $description = '';

    public int $sort_order = 0;

    public bool $showArchived = false;

    public function mount(string $section = 'banners'): void
    {
        $this->section = in_array($section, $this->sections(), true) ? $section : 'banners';
    }

    /** @return array<int, string> */
    public function sections(): array
    {
        return ['banners', 'noticias', 'reconocimientos', 'accesos', 'documentos', 'eventos', 'buzon'];
    }

    public function sectionTitle(): string
    {
        return match ($this->section) {
            'banners' => 'Banners del carrusel',
            'noticias' => 'Noticias y comunicados',
            'reconocimientos' => 'Espacio de reconocimiento',
            'accesos' => 'Accesos directos',
            'documentos' => 'Documentos claves',
            'eventos' => 'Calendario y eventos',
            'buzon' => 'Buzón de sugerencias',
            default => 'Contenido',
        };
    }

    public function resource(): string
    {
        return match ($this->section) {
            'noticias' => 'news',
            'reconocimientos' => 'recognitions',
            'accesos' => 'quick-links',
            'documentos' => 'documents',
            'eventos' => 'events',
            default => 'banners',
        };
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id, ManagePortalContentUseCase $useCase): void
    {
        $this->resetForm();
        $this->editingId = $id;
        $item = $useCase->list($this->resource());
        $found = collect($item)->firstWhere('id', $id);

        if (! $found) {
            return;
        }

        foreach (['title', 'subtitle', 'badge', 'image_url', 'category', 'author', 'excerpt', 'published_at', 'type', 'body', 'icon', 'label', 'url', 'name', 'external_url', 'event_date', 'description'] as $field) {
            if (isset($found[$field])) {
                $this->$field = (string) $found[$field];
            }
        }
        foreach (['sort_order'] as $field) {
            if (isset($found[$field])) {
                $this->$field = (int) $found[$field];
            }
        }
        foreach (['is_featured', 'is_published', 'is_active'] as $field) {
            if (isset($found[$field])) {
                $this->$field = (bool) $found[$field];
            }
        }

        $this->showModal = true;
    }

    public function save(ManagePortalContentUseCase $useCase): void
    {
        $data = $this->buildPayload();

        if ($this->editingId) {
            $useCase->update($this->resource(), $this->editingId, $data);
            session()->flash('success', 'Registro actualizado correctamente.');
        } else {
            $useCase->create($this->resource(), $data);
            session()->flash('success', 'Registro creado correctamente.');
        }

        $this->resetForm();
        $this->showModal = false;
    }

    public function delete(int $id, ManagePortalContentUseCase $useCase): void
    {
        $useCase->delete($this->resource(), $id);
        session()->flash('success', 'Registro eliminado correctamente.');
    }

    public function markRead(int $id, PortalRepositoryInterface $portal): void
    {
        $portal->markFeedbackRead($id, true);
    }

    public function archive(int $id, PortalRepositoryInterface $portal): void
    {
        $portal->archiveFeedback($id, true);
    }

    public function unarchive(int $id, PortalRepositoryInterface $portal): void
    {
        $portal->archiveFeedback($id, false);
    }

    public function deleteFeedback(int $id, PortalRepositoryInterface $portal): void
    {
        $portal->deleteFeedback($id);
        session()->flash('success', 'Mensaje eliminado.');
    }

    public function render(ManagePortalContentUseCase $useCase, PortalRepositoryInterface $portal)
    {
        $items = $this->section === 'buzon'
            ? $portal->feedbackInbox(! $this->showArchived ? false : false)
            : $useCase->list($this->resource());

        if ($this->section === 'buzon' && ! $this->showArchived) {
            $items = array_values(array_filter($items, fn ($m) => ! ($m['is_archived'] ?? false)));
        }

        return view('livewire.portal.manager', [
            'items' => $items,
            'sectionTitle' => $this->sectionTitle(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'subtitle', 'badge', 'image_url', 'imageUpload', 'author', 'excerpt', 'published_at', 'is_featured', 'is_published', 'is_active', 'type', 'body', 'icon', 'label', 'url', 'name', 'external_url', 'fileUpload', 'event_date', 'description', 'sort_order']);
        $this->category = 'comunicados';
    }

    /** @return array<string, mixed> */
    private function buildPayload(): array
    {
        $this->validate($this->rules());

        $payload = ['sort_order' => $this->sort_order];

        match ($this->section) {
            'banners' => $payload += [
                'title' => $this->title,
                'subtitle' => $this->subtitle ?: null,
                'badge' => $this->badge ?: null,
                'image_url' => $this->image_url ?: null,
                'image_path' => $this->storeImage('portal/banners'),
                'is_active' => $this->is_active,
            ],
            'noticias' => $payload += [
                'title' => $this->title,
                'excerpt' => $this->excerpt ?: null,
                'category' => $this->category,
                'author' => $this->author ?: null,
                'published_at' => $this->published_at ?: null,
                'image_url' => $this->image_url ?: null,
                'image_path' => $this->storeImage('portal/news'),
                'is_featured' => $this->is_featured,
                'is_published' => $this->is_published,
            ],
            'reconocimientos' => $payload += [
                'type' => $this->type ?: 'aniversario',
                'title' => $this->title,
                'body' => $this->body ?: null,
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
            ],
            'accesos' => $payload += [
                'label' => $this->label ?: $this->title,
                'url' => $this->url ?: '#',
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
            ],
            'documentos' => $payload += [
                'name' => $this->name ?: $this->title,
                'external_url' => $this->external_url ?: null,
                'file_path' => $this->storeFile('portal/documents'),
                'is_active' => $this->is_active,
            ],
            'eventos' => $payload += [
                'title' => $this->title,
                'description' => $this->description ?: null,
                'type' => $this->type ?: 'festivo',
                'event_date' => $this->event_date ?: null,
                'is_active' => $this->is_active,
            ],
            default => $payload,
        };

        // No sobrescribir archivos existentes al editar si no se subió uno nuevo.
        return array_filter($payload, fn ($v) => $v !== '__KEEP__');
    }

    private function storeImage(string $dir): mixed
    {
        if (! $this->imageUpload) {
            return $this->editingId ? '__KEEP__' : null;
        }

        return $this->imageUpload->store($dir, 'public');
    }

    private function storeFile(string $dir): mixed
    {
        if (! $this->fileUpload) {
            return $this->editingId ? '__KEEP__' : null;
        }

        return $this->fileUpload->store($dir, 'public');
    }

    /** @return array<string, mixed> */
    private function rules(): array
    {
        return match ($this->section) {
            'banners' => [
                'title' => 'required|min:3|max:255',
                'imageUpload' => 'nullable|image|max:2048',
                'image_url' => 'nullable|url|max:500',
                'sort_order' => 'integer|min:0',
            ],
            'noticias' => [
                'title' => 'required|min:3|max:255',
                'category' => 'required|in:comunicados,recursos,cultura',
                'imageUpload' => 'nullable|image|max:2048',
                'image_url' => 'nullable|url|max:500',
            ],
            'reconocimientos' => ['title' => 'required|min:3|max:255', 'type' => 'nullable|in:aniversario,ascenso,otro'],
            'accesos' => ['label' => 'required_without:title|min:2|max:255', 'url' => 'required|max:500'],
            'documentos' => ['name' => 'required_without:title|min:2|max:255', 'fileUpload' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', 'external_url' => 'nullable|max:500'],
            'eventos' => ['title' => 'required|min:3|max:255', 'type' => 'nullable|in:festivo,capacitacion,cumple', 'event_date' => 'nullable|date'],
            default => ['title' => 'required|min:3'],
        };
    }
}
