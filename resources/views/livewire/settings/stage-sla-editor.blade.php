<div class="space-y-6">
    @if (session()->has('success'))
        <flux:callout variant="success" icon="check-circle" class="bg-emerald-950/40 border-emerald-800 text-emerald-200">
            {{ session('success') }}
        </flux:callout>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1" class="text-white">{{ __('SLA por etapa del proceso') }}</flux:heading>
            <flux:text class="text-slate-400 mt-1">
                {{ __('Define los días objetivo de cada etapa. El dashboard marcará en verde las etapas que cumplan y en rojo las que excedan el objetivo.') }}
            </flux:text>
        </div>
        <a href="{{ route('dashboard') }}" wire:navigate
           class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
            {{ __('Volver al dashboard') }}
        </a>
    </div>

    <form wire:submit="save" class="space-y-4">
        <flux:card class="bg-slate-900/50 border border-slate-800 p-0 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 text-xs uppercase tracking-wider">
                            <th class="p-4 font-semibold">{{ __('Etapa') }}</th>
                            <th class="p-4 font-semibold w-48">{{ __('Días objetivo (SLA)') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/70">
                        @forelse($slas as $index => $row)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="p-4">
                                    <p class="font-medium text-slate-100">{{ ucwords(str_replace('_', ' ', $row['stage'])) }}</p>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $row['stage'] }}</p>
                                </td>
                                <td class="p-4">
                                    <flux:input
                                        type="number"
                                        min="1"
                                        max="60"
                                        wire:model="slas.{{ $index }}.sla_days"
                                        class="max-w-[120px]"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-6 text-center text-slate-400">
                                    {{ __('No hay etapas configuradas. Ejecuta el seeder de etapas requeridas.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </flux:card>

        <div class="flex items-center justify-end gap-3">
            <flux:text class="text-xs text-slate-500">{{ __('Los cambios aplican de inmediato al dashboard.') }}</flux:text>
            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors cursor-pointer">
                {{ __('Guardar SLA') }}
            </button>
        </div>
    </form>
</div>
