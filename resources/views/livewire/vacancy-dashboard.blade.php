<div class="flex h-full w-full flex-1 flex-col gap-6 p-4 sm:p-6 lg:p-8 bg-slate-100 dark:bg-slate-950 min-h-screen">
    
    <!-- Header Corporativo -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-[#0A192F] text-white font-black shadow-md border border-slate-700">
                <span class="text-sm tracking-tighter text-[#E31B23]">G</span>
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-[#0A192F] dark:text-white uppercase">El Gigante</h1>
                <p class="text-[10px] font-semibold tracking-widest uppercase text-slate-500">De los Azulejos y Mármoles</p>
            </div>
        </div>
        <div class="mt-4 md:mt-0 text-left md:text-right border-t md:border-t-0 pt-3 md:pt-0 border-slate-100 dark:border-slate-800">
            <p class="text-[11px] font-bold tracking-widest text-slate-400 uppercase">Atracción de Talento</p>
            <h2 class="text-2xl font-extrabold text-[#0A192F] dark:text-white">Tablero de vacantes</h2>
        </div>
    </div>

    <!-- Panel de Filtros Superior con Opciones de Prueba -->
    <div class="bg-[#0A192F] rounded-2xl p-6 shadow-lg text-white space-y-4 border border-slate-800">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Estatus</label>
                <select wire:model.live="status" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    <option value="activa">Abierta</option>
                    <option value="cerrada">Cerrada</option>
                    <option value="pausada">Pausada</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Región</label>
                <select wire:model.live="region_id" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    @foreach($catalogRegions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Sucursal</label>
                <select wire:model.live="branch_id" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    @foreach($catalogBranches as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Oferta de empleo</label>
                <select wire:model.live="position" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    @foreach($catalogPositions as $opt)
                        <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Mes</label>
                <select wire:model.live="month" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Año</label>
                <select wire:model.live="year" class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                    <option value="">Todos</option>
                    @foreach($catalogYears as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Rango Personalizado con Datepickers -->
        <div class="bg-white/5 rounded-xl p-3 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-300">Rango personalizado</span>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <span class="text-xs text-slate-400">Desde</span>
                    <input type="date" wire:model.live="dateFrom" class="bg-white text-slate-900 rounded-lg px-3 py-1.5 text-xs w-full sm:w-40 focus:outline-none" />
                </div>
                <div class="flex items-center space-x-2 w-full sm:w-auto">
                    <span class="text-xs text-slate-400">Hasta</span>
                    <input type="date" wire:model.live="dateTo" class="bg-white text-slate-900 rounded-lg px-3 py-1.5 text-xs w-full sm:w-40 focus:outline-none" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-1">
            <span class="text-xs text-slate-300 font-medium">{{ $vacanciesList->total() }} vacantes</span>
            <button wire:click="resetFilters" class="text-xs font-semibold text-red-400 hover:text-red-300 underline tracking-wide">
                Limpiar filtros
            </button>
        </div>
    </div>

    <!-- Cuadrícula Principal de KPIs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna 1: Cobertura Promedio & Vacantes Críticas -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tiempo de Cobertura Promedio -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tiempo de cobertura promedio</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1 mb-4">{{ round($avgCoverageDays) }} días</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por región</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">{{ $TopCriticaRegion->region->name ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por sucursal</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">{{ $TopCriticaSucursal->branch->name ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por puesto</span>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">{{ $TopCriticasPorPuesto->position_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Vacantes Críticas -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-t-4 border-t-[#E31B23] rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Vacantes críticas</p>
                <h3 class="text-4xl font-black text-[#E31B23] mt-1 mb-4">{{ $totalCritical }}</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por puesto</span>
                        <div class="flex items-center justify-between mt-1 gap-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="{{ $TopCriticasPorPuesto->position_name ?? 'N/A' }}">{{ $TopCriticasPorPuesto->position_name ?? 'N/A' }}</span>
                            <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">{{ $TopCriticasPorPuesto->total ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por sucursal</span>
                        <div class="flex items-center justify-between mt-1 gap-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="{{ $TopCriticaSucursal->branch->name ?? 'N/A' }}">{{ $TopCriticaSucursal->branch->name ?? 'N/A' }}</span>
                            <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">{{ $TopCriticaSucursal->total ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por región</span>
                        <div class="flex items-center justify-between mt-1 gap-2">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="{{ $TopCriticaRegion->region->name ?? 'N/A' }}">{{ $TopCriticaRegion->region->name ?? 'N/A' }}</span>
                            <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">{{ $TopCriticaRegion->total ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Columna 2: Indicadores del Mes y del Año -->
        <div class="space-y-6">
            
            <!-- Indicadores del mes -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Indicadores del mes</p>
                    <p class="text-[11px] text-slate-400">{{ \Carbon\Carbon::create($yearLabel, $monthLabel, 1)->translatedFormat('F Y') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-2xl font-black text-[#0A192F] dark:text-white block">{{ $monthlyOpen }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes abiertas</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-2xl font-black text-[#0A192F] dark:text-white block">{{ $monthlyHired }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Contrataciones</span>
                    </div>
                </div>
            </div>

            <!-- Indicadores del año -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Indicadores del año</p>
                    <p class="text-[11px] text-slate-400">{{ $yearLabel }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-2xl font-black text-[#0A192F] dark:text-white block">{{ $yearlyActive }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes activas</span>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        <span class="text-2xl font-black text-[#0A192F] dark:text-white block">{{ $yearlyCovered }}</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes cubiertas</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Resultados Históricos (2025 y 2026) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Resultados {{ $historyPrev['year'] }}</p>
            <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Vacantes</span> <span class="font-bold text-slate-800 dark:text-slate-200">{{ $historyPrev['vacancies'] }}</span></div>
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Ingresos</span> <span class="font-bold text-slate-800 dark:text-slate-200">{{ $historyPrev['hired'] }}</span></div>
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Cobertura</span> <span class="font-bold text-[#E31B23]">{{ $historyPrev['coverage'] }}%</span></div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Resultados {{ $historyCurrent['year'] }}</p>
            <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Vacantes</span> <span class="font-bold text-slate-800 dark:text-slate-200">{{ $historyCurrent['vacancies'] }}</span></div>
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Ingresos</span> <span class="font-bold text-slate-800 dark:text-slate-200">{{ $historyCurrent['hired'] }}</span></div>
                <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Cobertura</span> <span class="font-bold text-emerald-600">{{ $historyCurrent['coverage'] }}%</span></div>
            </div>
        </div>
    </div>

    <!-- Efectividad del proceso de reclutamiento -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-[#0A192F] dark:text-white uppercase tracking-wide">Efectividad del proceso</h2>
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Meta vs. resultado</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Índice de contratación</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1">{{ $recruitment['hiring']['rate'] }}%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Meta ≥ 80%</p>
                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-semibold uppercase">Contratados</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['hiring']['contracted'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500 font-semibold uppercase">Finalistas</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['hiring']['finalists'] }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Aceptación de oferta</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1">{{ $recruitment['offers']['rate'] }}%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Meta ≥ 90%</p>
                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-semibold uppercase">Aceptadas</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['offers']['accepted'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500 font-semibold uppercase">Emitidas</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['offers']['emitted'] }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Calidad de contratación</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1">{{ $recruitment['retention']['rate'] }}%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Permanencia ≥ 90 días · Meta ≥ 90%</p>
                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-semibold uppercase">Retenidos</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['retention']['retained'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500 font-semibold uppercase">Evaluables</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['retention']['eligible'] }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Rotación de nuevo ingreso</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1">{{ $recruitment['turnover']['rate'] }}%</h3>
                <p class="text-[11px] text-slate-400 mt-1">Bajas &lt; 90 días · Meta ≤ 10%</p>
                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-semibold uppercase">Bajas tempranas</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['turnover']['early'] }}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500 font-semibold uppercase">Ingresos</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['turnover']['hires'] }}</span>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Fuente más efectiva</p>
                <h3 class="text-xl font-black text-[#0A192F] dark:text-white mt-1 truncate" title="{{ $recruitment['sources']['top']['source'] ?? 'Sin datos' }}">
                    {{ $recruitment['sources']['top']['source'] ?? 'Sin datos' }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">Contrataciones por fuente</p>
                <div class="mt-4 space-y-1 text-xs">
                    @forelse($recruitment['sources']['ranking'] as $source)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500 font-semibold uppercase truncate">{{ $source['source'] }}</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $source['contracted'] }}/{{ $source['total'] }}</span>
                        </div>
                    @empty
                        <span class="text-slate-400">Sin fuentes registradas.</span>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Costo por contratación</p>
                <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1">
                    ${{ number_format($recruitment['cost']['per_hire'], 2) }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-1">Meta anual</p>
                <div class="mt-4 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-semibold uppercase">Inversión</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">${{ number_format($recruitment['cost']['invested'], 2) }}</span>
                </div>
                <div class="flex items-center justify-between text-xs mt-1">
                    <span class="text-slate-500 font-semibold uppercase">Contrataciones</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ $recruitment['cost']['hires'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tiempo por etapa del proceso</p>
                <p class="text-[11px] text-slate-400">Días promedio entre etapas completadas · Meta según SLA</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @forelse($recruitment['stage_durations'] as $stage)
                    <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-slate-600 dark:text-slate-300 uppercase truncate" title="{{ $stage['stage'] }}">{{ ucwords(str_replace('_', ' ', $stage['stage'])) }}</p>
                            <p class="text-[10px] text-slate-400">
                                {{ $stage['sla_days'] !== null ? 'Meta ≤ '.$stage['sla_days'].' d' : 'Sin SLA' }}
                            </p>
                        </div>
                        <span class="text-xs font-black shrink-0 {{ $stage['on_target'] === false ? 'text-[#E31B23]' : ($stage['on_target'] === true ? 'text-emerald-600' : 'text-[#0A192F] dark:text-white') }}">{{ $stage['avg_days'] }} d</span>
                    </div>
                @empty
                    <span class="text-xs text-slate-400">Sin etapas registradas para el periodo.</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tabla Detallada -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
            <div>
                <h2 class="text-lg font-bold text-[#0A192F] dark:text-white uppercase tracking-wide">Control detallado de vacantes</h2>
                <p class="text-xs text-slate-500 mt-0.5">Selecciona una vacante para consultar su expediente completo.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-emerald-600"></span><span>0–5 días</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-amber-500"></span><span>6–11 días</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-orange-500"></span><span>12–20 días</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-3 h-3 rounded bg-[#E31B23]"></span><span>Más de 20 días</span></div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#0A192F] text-white font-bold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-3">Status</th>
                        <th class="py-3 px-3">Ticket</th>
                        <th class="py-3 px-3">Fecha de creación</th>
                        <th class="py-3 px-3">Oferta de empleo</th>
                        <th class="py-3 px-3">Tipo de vacante</th>
                        <th class="py-3 px-3">Sucursal</th>
                        <th class="py-3 px-3">Categoría</th>
                        <th class="py-3 px-3">Región</th>
                        <th class="py-3 px-3">Responsable</th>
                        <th class="py-3 px-3 text-center">Días vacante</th>
                        <th class="py-3 px-3 text-center">Clasificación</th>
                        <th class="py-3 px-3 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($vacanciesList as $vacante)
                        @php
                            $isOpen = $vacante->status === 'activa';
                            $dias = $isOpen ? (int) abs(now()->diffInDays($vacante->created_at)) : '—';
                            $badgeClass = match(true) {
                                $dias !== '—' && $dias <= 5 => 'bg-emerald-600 text-white',
                                $dias !== '—' && $dias <= 11 => 'bg-amber-500 text-white',
                                $dias !== '—' && $dias <= 20 => 'bg-orange-500 text-white',
                                $dias !== '—' => 'bg-[#E31B23] text-white',
                                default => 'text-slate-400 font-mono'
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $isOpen ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                    {{ $isOpen ? 'Abierta' : ucfirst($vacante->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#0047AB]">{{ $vacante->folio }}</td>
                            <td class="py-3 px-3 text-slate-500">{{ $vacante->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-3 font-semibold text-slate-900 dark:text-white">{{ $vacante->title }}</td>
                            <td class="py-3 px-3">{{ $vacante->type }}</td>
                            <td class="py-3 px-3">{{ $vacante->branch ?? 'N/A' }}</td>
                            <td class="py-3 px-3">{{ $vacante->reason ?? 'N/A' }}</td>
                            <td class="py-3 px-3">{{ $vacante->region ?? 'N/A' }}</td>
                            <td class="py-3 px-3">N/A</td>
                            <td class="py-3 px-3 text-center font-bold {{ $badgeClass }}">
                                {{ $dias }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="{{ is_numeric($dias) && $dias > 20 ? 'text-[#E31B23]' : 'text-emerald-700' }} font-bold">
                                    {{ is_numeric($dias) && $dias > 20 ? 'Crítica' : 'En tiempo' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <a href="#" class="inline-flex px-3 py-1 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold transition-colors">Ver detalle</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-6 text-center text-slate-400 font-medium">
                                No se encontraron vacantes con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4">
            {{ $vacanciesList->links() }}
        </div>
    </div>

</div>