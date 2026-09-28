<div class="space-y-6">
    @if (session()->has('success'))
        <flux:callout variant="success" icon="check-circle" class="bg-emerald-950/40 border-emerald-800 text-emerald-200">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1" class="text-white">{{ __('Contenido del Portal') }} — {{ $sectionTitle }}</flux:heading>
            <flux:text class="text-slate-400 mt-1">{{ __('Administra cada sección de la landing sin afectar el diseño actual.') }}</flux:text>
        </div>
        @if($section !== 'buzon')
            <button wire:click="openCreate" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
                <flux:icon.plus class="w-4 h-4" />
                {{ __('Nuevo registro') }}
            </button>
        @endif
    </div>

    <!-- Tabs de secciones -->
    <div class="flex flex-wrap gap-2 bg-slate-900/40 p-3 rounded-2xl border border-slate-800/60">
        @foreach(['banners' => 'Banners', 'noticias' => 'Noticias', 'reconocimientos' => 'Reconocimientos', 'accesos' => 'Accesos', 'documentos' => 'Documentos', 'eventos' => 'Eventos', 'buzon' => 'Buzón'] as $key => $label)
            <a href="{{ route('portal.manager', $key) }}" wire:navigate
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $section === $key ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                {{ $label }}
            </a>
        @endforeach
        <a href="{{ route('home') }}" target="_blank" class="ml-auto px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-300 hover:bg-slate-800">Ver landing ↗</a>
    </div>

    @if($section === 'buzon')
        <label class="flex items-center gap-2 text-xs text-slate-300">
            <input type="checkbox" wire:model.live="showArchived" class="rounded border-slate-600"> Mostrar archivados
        </label>
        <flux:card class="bg-slate-900/50 border border-slate-800 p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-xs uppercase tracking-wider">
                            <th class="p-4">Fecha</th>
                            <th class="p-4">Mensaje</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($items as $msg)
                            <tr class="hover:bg-slate-800/30">
                                <td class="p-4 text-xs text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($msg['created_at'])->format('d/m/Y H:i') }}</td>
                                <td class="p-4 max-w-md">{{ $msg['body'] }}</td>
                                <td class="p-4"><flux:badge size="sm" color="{{ $msg['is_anonymous'] ? 'zinc' : 'blue' }}">{{ $msg['is_anonymous'] ? 'Anónimo' : 'Directo' }}</flux:badge></td>
                                <td class="p-4">
                                    @if($msg['is_archived']) <flux:badge size="sm" color="zinc">Archivado</flux:badge>
                                    @elseif($msg['is_read']) <flux:badge size="sm" color="emerald">Leído</flux:badge>
                                    @else <flux:badge size="sm" color="amber">Nuevo</flux:badge> @endif
                                </td>
                                <td class="p-4 text-end">
                                    <div class="inline-flex items-center gap-1">
                                        @if(!$msg['is_read']) <button wire:click="markRead({{ $msg['id'] }})" class="px-2 py-1 text-xs text-emerald-300 hover:bg-slate-800 rounded-lg">Marcar leído</button> @endif
                                        @if(!$msg['is_archived']) <button wire:click="archive({{ $msg['id'] }})" class="px-2 py-1 text-xs text-slate-300 hover:bg-slate-800 rounded-lg">Archivar</button>
                                        @else <button wire:click="unarchive({{ $msg['id'] }})" class="px-2 py-1 text-xs text-slate-300 hover:bg-slate-800 rounded-lg">Desarchivar</button> @endif
                                        <button wire:click="deleteFeedback({{ $msg['id'] }})" wire:confirm="¿Eliminar mensaje?" class="p-1.5 text-red-400 hover:bg-slate-800 rounded-lg"><flux:icon.trash class="w-4 h-4" /></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-12 text-center text-slate-500">Sin mensajes en el buzón.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </flux:card>
    @else
        <flux:card class="bg-slate-900/50 border border-slate-800 p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-xs uppercase tracking-wider">
                            <th class="p-4">Título / Nombre</th>
                            <th class="p-4">Detalle</th>
                            <th class="p-4">Orden</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60 text-slate-300">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-800/30">
                                <td class="p-4 font-medium text-slate-100">{{ $item['title'] ?? $item['label'] ?? $item['name'] ?? '—' }}</td>
                                <td class="p-4 text-xs text-slate-400 max-w-md truncate">
                                    {{ $item['subtitle'] ?? $item['excerpt'] ?? $item['body'] ?? $item['url'] ?? $item['description'] ?? $item['badge'] ?? $item['category'] ?? $item['type'] ?? '—' }}
                                </td>
                                <td class="p-4 text-xs">{{ $item['sort_order'] ?? 0 }}</td>
                                <td class="p-4">
                                    @php $active = $item['is_active'] ?? $item['is_published'] ?? true; @endphp
                                    <flux:badge size="sm" color="{{ $active ? 'emerald' : 'zinc' }}">{{ $active ? 'Visible' : 'Oculto' }}</flux:badge>
                                </td>
                                <td class="p-4 text-end">
                                    <div class="inline-flex items-center gap-1">
                                        <button wire:click="edit({{ $item['id'] }})" class="p-1.5 text-blue-300 hover:bg-slate-800 rounded-lg"><flux:icon.pencil class="w-4 h-4" /></button>
                                        <button wire:click="delete({{ $item['id'] }})" wire:confirm="¿Eliminar este registro?" class="p-1.5 text-red-400 hover:bg-slate-800 rounded-lg"><flux:icon.trash class="w-4 h-4" /></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-12 text-center text-slate-500">Sin registros. Crea el primero con “Nuevo registro”.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </flux:card>
    @endif

    @if($showModal && $section !== 'buzon')
        <flux:modal wire:model="showModal" class="max-w-xl space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Editar' : 'Nuevo' }} — {{ $sectionTitle }}</flux:heading>
                <flux:text class="text-sm text-slate-400 mt-1">Los cambios se reflejan en la landing al guardar.</flux:text>
            </div>
            <form wire:submit="save" class="space-y-4">
                @if(in_array($section, ['banners', 'noticias', 'reconocimientos', 'eventos']))
                    <flux:input wire:model="title" label="Título" required />
                @endif
                @if($section === 'banners')
                    <flux:input wire:model="subtitle" label="Subtítulo" />
                    <flux:input wire:model="badge" label="Badge" placeholder="Ej. Lanzamiento Exclusivo" />
                    <flux:input wire:model="image_url" label="Imagen URL (opcional si subes archivo)" placeholder="https://..." />
                    <flux:input wire:model="imageUpload" type="file" label="Subir imagen (opcional)" accept="image/*" />
                @endif
                @if($section === 'noticias')
                    <flux:textarea wire:model="excerpt" label="Extracto" rows="3" />
                    <div class="grid grid-cols-2 gap-4">
                        <flux:select wire:model="category" label="Categoría">
                            <option value="comunicados">Comunicados Oficiales</option>
                            <option value="recursos">Documentos y Políticas</option>
                            <option value="cultura">Cultura y Festejos</option>
                        </flux:select>
                        <flux:input wire:model="author" label="Autor" />
                    </div>
                    <flux:input wire:model="published_at" type="datetime-local" label="Fecha publicación" />
                    <flux:input wire:model="image_url" label="Imagen URL (opcional)" placeholder="https://..." />
                    <flux:input wire:model="imageUpload" type="file" label="Subir imagen (opcional)" accept="image/*" />
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" wire:model="is_featured" class="rounded"> Destacada</label>
                        <label class="flex items-center gap-2 text-sm text-slate-300"><input type="checkbox" wire:model="is_published" class="rounded"> Publicada</label>
                    </div>
                @endif
                @if($section === 'reconocimientos')
                    <flux:select wire:model="type" label="Tipo">
                        <option value="aniversario">Aniversario</option>
                        <option value="ascenso">Ascenso</option>
                        <option value="otro">Otro</option>
                    </flux:select>
                    <flux:textarea wire:model="body" label="Texto" rows="3" />
                    <flux:input wire:model="icon" label="Icono (emoji o texto)" placeholder="🏆" />
                @endif
                @if($section === 'accesos')
                    <flux:input wire:model="label" label="Etiqueta" required />
                    <flux:input wire:model="url" label="URL" required />
                    <flux:input wire:model="icon" label="Icono (opcional)" />
                @endif
                @if($section === 'documentos')
                    <flux:input wire:model="name" label="Nombre del documento" required />
                    <flux:input wire:model="external_url" label="URL externa (opcional si subes archivo)" placeholder="https://..." />
                    <flux:input wire:model="fileUpload" type="file" label="Subir archivo (PDF/DOC, opcional)" />
                @endif
                @if($section === 'eventos')
                    <flux:textarea wire:model="description" label="Descripción" rows="2" />
                    <div class="grid grid-cols-2 gap-4">
                        <flux:select wire:model="type" label="Tipo">
                            <option value="festivo">Festivo</option>
                            <option value="capacitacion">Capacitación</option>
                            <option value="cumple">Festejos</option>
                        </flux:select>
                        <flux:input wire:model="event_date" type="date" label="Fecha" />
                    </div>
                @endif
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="sort_order" type="number" label="Orden" />
                    @if(!in_array($section, ['noticias']))
                        <label class="flex items-center gap-2 text-sm text-slate-300 pt-6"><input type="checkbox" wire:model="is_active" class="rounded"> Visible en landing</label>
                    @endif
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                    <flux:modal.close><flux:button variant="subtle">Cancelar</flux:button></flux:modal.close>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg">Guardar</button>
                </div>
            </form>
        </flux:modal>
    @endif
</div>
