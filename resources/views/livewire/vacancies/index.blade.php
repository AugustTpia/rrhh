<div class="space-y-6">
    @if (session()->has('success'))
        <flux:callout variant="success" icon="check-circle" class="bg-emerald-950/40 border-emerald-800 text-emerald-200">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1" class="text-white">{{ __('Gestión Directa de Vacantes') }}</flux:heading>
            <flux:text class="text-slate-400 mt-1">{{ __('Control de altas con folios automáticos, motivos de creación y estatus operativos.') }}</flux:text>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
            <flux:icon.plus class="w-4 h-4" />
            {{ __('Nueva Vacante') }}
        </button>
    </div>

    <!-- Filtros de Búsqueda y Estatus -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-800/60">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por folio o puesto..." />
        
        <flux:select wire:model.live="statusFilter" placeholder="Filtrar por estatus">
            <option value="">Todos los estatus</option>
            <option value="activa">Activa</option>
            <option value="pausada">Pausada</option>
            <option value="cerrada">Cerrada</option>
        </flux:select>
    </div>

    <!-- Tabla Operativa -->
    <flux:card class="bg-slate-900/50 border border-slate-800 p-0 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">{{ __('Folio Automático') }}</th>
                        <th class="p-4 font-semibold">{{ __('Puesto') }}</th>
                        <th class="p-4 font-semibold">{{ __('Sucursal / Región') }}</th>
                        <th class="p-4 font-semibold">{{ __('Motivo') }}</th>
                        <th class="p-4 font-semibold">{{ __('Tipo') }}</th>
                        <th class="p-4 font-semibold">{{ __('Estatus') }}</th>
                        <th class="p-4 font-semibold text-end">{{ __('Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($vacancies as $vacancy)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-4 font-mono text-xs text-[#E31B23] font-bold">{{ $vacancy->folio }}</td>
                            <td class="p-4 font-medium text-slate-100">{{ $vacancy->title }}</td>
                            <td class="p-4">
                                <p class="text-sm">{{ $vacancy->branch }}</p>
                                <p class="text-xs text-slate-500">{{ $vacancy->region }}</p>
                            </td>
                            <td class="p-4">
                                <flux:badge variant="subtle" size="sm" color="{{ $vacancy->reason === 'nueva_creacion' ? 'blue' : 'purple' }}">
                                    {{ $vacancy->reason === 'nueva_creacion' ? 'Nueva Creación' : 'Reemplazo' }}
                                </flux:badge>
                            </td>
                            <td class="p-4 text-xs text-slate-400 capitalize">{{ $vacancy->type }}</td>
                            <td class="p-4">
                                @if($vacancy->status === 'activa')
                                    <flux:badge color="emerald" size="sm">Activa</flux:badge>
                                @elseif($vacancy->status === 'pausada')
                                    <flux:badge color="amber" size="sm">Pausada</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Cerrada</flux:badge>
                                @endif
                            </td>
                            <td class="p-4 text-end">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="delete({{ $vacancy->id }})" wire:confirm="¿Estás seguro de eliminar esta vacante?" class="p-1.5 text-red-400 hover:text-red-300 hover:bg-slate-800 rounded-lg transition-colors cursor-pointer" title="Eliminar">
                                        <flux:icon.trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-500">
                                No se encontraron vacantes registradas en SQLite.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>

    <!-- Modal de Alta Directa -->
    <flux:modal wire:model="showCreateModal" class="max-w-xl space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Registrar Nueva Vacante') }}</flux:heading>
            <flux:text class="text-sm text-slate-400 mt-1">{{ __('Se asignará un folio interno automático al guardar en la base de datos.') }}</flux:text>
        </div>

        <form wire:submit="save" class="space-y-4">
            <flux:input wire:model="title" label="Título del Puesto" placeholder="Ej. Asesor Comercial" required />

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model.live="region" label="Región" placeholder="Selecciona una región..." required>
                    @foreach($regionOptions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="branch" label="Sucursal" placeholder="Selecciona una sucursal..." required>
                    @foreach($branchOptions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="reason" label="Motivo de Creación">
                    <option value="nueva_creacion">Nueva Creación</option>
                    <option value="reemplazo">Reemplazo</option>
                </flux:select>

                <flux:select wire:model="type" label="Tipo de Vacante">
                    <option value="publica">Pública</option>
                    <option value="confidencial">Confidencial</option>
                </flux:select>
            </div>

            <flux:textarea wire:model="description" label="Descripción y Requisitos" rows="3" />

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <flux:modal.close>
                    <flux:button variant="subtle">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
                    {{ __('Guardar y Generar Folio') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>