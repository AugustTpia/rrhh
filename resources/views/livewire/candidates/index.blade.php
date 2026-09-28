<div class="space-y-6">
    @if (session()->has('success'))
        <flux:callout variant="success" icon="check-circle" class="bg-emerald-950/40 border-emerald-800 text-emerald-200">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1" class="text-white">{{ __('Pipeline de Candidatos') }}</flux:heading>
            <flux:text class="text-slate-400 mt-1">{{ __('Seguimiento granular por etapas, evaluaciones psicotécnicas, entrevistas y visitas domiciliarias.') }}</flux:text>
        </div>
        <button wire:click="$set('showCreateModal', true)" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
            <flux:icon.plus class="w-4 h-4" />
            {{ __('Nuevo Candidato') }}
        </button>
    </div>

    <!-- Filtros Dinámicos -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-slate-900/40 p-4 rounded-2xl border border-slate-800/60">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Buscar por nombre, teléfono o correo..." />
        
        <flux:select wire:model.live="vacancyFilter" placeholder="Filtrar por vacante">
            <option value="">Todas las vacantes</option>
            @foreach($vacancies as $vac)
                <option value="{{ $vac->id }}">{{ $vac->folio }} - {{ $vac->title }}</option>
            @endforeach
        </flux:select>

        <flux:select wire:model.live="stageFilter" placeholder="Filtrar por etapa">
            <option value="">Todas las etapas</option>
            <option value="contacto">Contacto Inicial</option>
            <option value="psicotecnicas">Pruebas Psicotécnicas</option>
            <option value="entrevistas">Entrevistas</option>
            <option value="manejo">Prueba de Manejo</option>
            <option value="socioeconomico">Estudio Socioeconómico</option>
            <option value="contratado">Contratado</option>
            <option value="descartado">Descartado</option>
        </flux:select>
    </div>

    <!-- Tabla del Pipeline -->
    <flux:card class="bg-slate-900/50 border border-slate-800 p-0 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="p-4 font-semibold">{{ __('Candidato y Contacto') }}</th>
                        <th class="p-4 font-semibold">{{ __('Vacante Postulada') }}</th>
                        <th class="p-4 font-semibold">{{ __('Origen / Expectativa') }}</th>
                        <th class="p-4 font-semibold">{{ __('Etapa Actual') }}</th>
                        <th class="p-4 font-semibold text-end">{{ __('Avanzar / Acciones') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-slate-300">
                    @forelse($candidates as $candidate)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="p-4">
                                <p class="font-medium text-slate-100 text-base">{{ $candidate->full_name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">📞 {{ $candidate->phone }} | ✉️ {{ $candidate->email ?: 'N/D' }}</p>
                            </td>
                            <td class="p-4">
                                <p class="font-mono text-xs text-[#E31B23] font-bold">{{ $candidate->vacancy?->folio }}</p>
                                <p class="text-xs text-slate-300">{{ $candidate->vacancy?->title }}</p>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $candidate->source }}
                                </span>
                                <p class="text-xs text-emerald-400 mt-1 font-semibold">
                                    {{ $candidate->salary_expectation ? '$' . number_format($candidate->salary_expectation, 2) : 'No especificada' }}
                                </p>
                            </td>
                            <td class="p-4">
                                @if($candidate->stage === 'contacto')
                                    <flux:badge color="blue" size="sm">Contacto Inicial</flux:badge>
                                @elseif($candidate->stage === 'psicotecnicas')
                                    <flux:badge color="purple" size="sm">Pruebas Psicotécnicas</flux:badge>
                                @elseif($candidate->stage === 'entrevistas')
                                    <flux:badge color="amber" size="sm">Entrevistas</flux:badge>
                                @elseif($candidate->stage === 'manejo')
                                    <flux:badge color="cyan" size="sm">Prueba de Manejo</flux:badge>
                                @elseif($candidate->stage === 'socioeconomico')
                                    <flux:badge color="indigo" size="sm">Estudio Socioeconómico</flux:badge>
                                @elseif($candidate->stage === 'contratado')
                                    <flux:badge color="emerald" size="sm">Contratado</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Descartado</flux:badge>
                                @endif
                            </td>
                            <td class="p-4 text-end">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Selector rápido para cambiar de etapa -->
                                    <select wire:change="updateStage({{ $candidate->id }}, $event.target.value)" class="text-xs bg-slate-950 border border-slate-700 text-slate-300 rounded-lg px-2 py-1 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                        <option value="" disabled selected>Cambiar etapa...</option>
                                        <option value="contacto">Contacto</option>
                                        <option value="psicotecnicas">Psicotécnicas</option>
                                        <option value="entrevistas">Entrevistas</option>
                                        <option value="manejo">Prueba de Manejo</option>
                                        <option value="socioeconomico">Socioeconómico</option>
                                        <option value="contratado">Contratado</option>
                                        <option value="descartado">Descartado</option>
                                    </select>

                                    <button wire:click="delete({{ $candidate->id }})" wire:confirm="¿Estás seguro de eliminar este candidato?" class="p-1.5 text-red-400 hover:text-red-300 hover:bg-slate-800 rounded-lg transition-colors cursor-pointer" title="Eliminar">
                                        <flux:icon.trash class="w-4 h-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-500">
                                No hay candidatos registrados en el pipeline.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </flux:card>

    <!-- Modal de Registro de Candidato -->
    <flux:modal wire:model="showCreateModal" class="max-w-xl space-y-6">
        <div>
            <flux:heading size="lg">{{ __('Registrar Nuevo Candidato') }}</flux:heading>
            <flux:text class="text-sm text-slate-400 mt-1">{{ __('Agrega los datos de contacto y vincúlalo directamente a una vacante abierta.') }}</flux:text>
        </div>

        <form wire:submit="save" class="space-y-4">
            <flux:select wire:model="vacancy_id" label="Vacante Asignada">
                @foreach($vacancies as $vac)
                    <option value="{{ $vac->id }}">{{ $vac->folio }} - {{ $vac->title }} ({{ $vac->branch }})</option>
                @endforeach
            </flux:select>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="full_name" label="Nombre Completo" placeholder="Ej. Juan Pérez López" required />
                <flux:input wire:model="phone" label="Teléfono / WhatsApp" placeholder="2281234567" required />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input wire:model="email" label="Correo Electrónico" type="email" placeholder="correo@ejemplo.com" />
                <flux:input wire:model="salary_expectation" label="Expectativa Salarial (MXN)" type="number" step="500" placeholder="15000" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:select wire:model="source" label="Origen de Postulación">
                    <option value="Indeed">Indeed</option>
                    <option value="OCCMundial">OCCMundial</option>
                    <option value="LinkedIn">LinkedIn</option>
                    <option value="Bolsa de Trabajo Local">Bolsa de Trabajo Local</option>
                    <option value="Recomendación">Recomendación</option>
                    <option value="Directo">Directo</option>
                </flux:select>

                <flux:select wire:model="stage" label="Etapa Inicial">
                    <option value="contacto">Contacto Inicial</option>
                    <option value="psicotecnicas">Pruebas Psicotécnicas</option>
                    <option value="entrevistas">Entrevistas</option>
                    <option value="manejo">Prueba de Manejo</option>
                    <option value="socioeconomico">Estudio Socioeconómico</option>
                </flux:select>
            </div>

            <flux:textarea wire:model="notes" label="Notas u Observaciones Iniciales" rows="2" placeholder="Comentarios sobre perfil, disponibilidad, etc." />

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                <flux:modal.close>
                    <flux:button variant="subtle">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>
                <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
                    {{ __('Guardar Candidato') }}
                </button>
            </div>
        </form>
    </flux:modal>
</div>