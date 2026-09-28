<x-layouts::app :title="__('Tablero de vacantes - Atracción de Talento')">
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
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                        <option value="">Todos</option>
                        <option value="abierta">Abierta</option>
                        <option value="cerrada">Cerrada</option>
                        <option value="pausada">Pausada</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Región</label>
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                        <option value="">Todos</option>
                        <option value="corporativo">Corporativo</option>
                        <option value="golfo-1">Golfo 1</option>
                        <option value="golfo-2">Golfo 2</option>
                        <option value="golfo-3">Golfo 3</option>
                        <option value="norte-1">Norte 1</option>
                        <option value="norte-2">Norte 2</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Sucursal</label>
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                        <option value="">Todos</option>
                        <option value="xalapa">Xalapa Centro</option>
                        <option value="veracruz">Veracruz 3</option>
                        <option value="coyutla">Coyutla</option>
                        <option value="tuxpan">Tuxpan 1</option>
                        <option value="poza-rica">Poza Rica</option>
                        <option value="papantla">Papantla 1</option>
                        <option value="altamira">Altamira</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Oferta de empleo</label>
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                        <option value="">Todos</option>
                        <option value="gerente">Gerente Regional</option>
                        <option value="almacen">Encargado de Almacén</option>
                        <option value="auditor">Auditor Senior</option>
                        <option value="auxiliar">Auxiliar de Inventarios</option>
                        <option value="chofer">Chofer</option>
                        <option value="asesor">Asesor de Ventas</option>
                        <option value="sucursal">Encargado de Sucursal</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold tracking-wider uppercase text-slate-300">Mes</label>
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
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
                    <select class="w-full bg-white text-slate-900 rounded-xl px-3 py-2 text-xs font-medium focus:ring-2 focus:ring-[#0047AB] border-none shadow-inner">
                        <option value="">Todos</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
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
                        <input type="date" class="bg-white text-slate-900 rounded-lg px-3 py-1.5 text-xs w-full sm:w-40 focus:outline-none" />
                    </div>
                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                        <span class="text-xs text-slate-400">Hasta</span>
                        <input type="date" class="bg-white text-slate-900 rounded-lg px-3 py-1.5 text-xs w-full sm:w-40 focus:outline-none" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-1">
                <span class="text-xs text-slate-300 font-medium">132 vacantes</span>
                <button class="text-xs font-semibold text-red-400 hover:text-red-300 underline tracking-wide">
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
                    <h3 class="text-3xl font-black text-[#0A192F] dark:text-white mt-1 mb-4">Pendiente</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por región</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">Pendiente</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por sucursal</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">Pendiente</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por puesto</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1 block">Pendiente</span>
                        </div>
                    </div>
                </div>

                <!-- Vacantes Críticas (Con números destacados y separados para evitar cortes) -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 border-t-4 border-t-[#E31B23] rounded-2xl p-6 shadow-sm">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Vacantes críticas</p>
                    <h3 class="text-4xl font-black text-[#E31B23] mt-1 mb-4">24</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por puesto</span>
                            <div class="flex items-center justify-between mt-1 gap-2">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="Encargado de Almacén">Encargado de Almacén</span>
                                <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">10</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por sucursal</span>
                            <div class="flex items-center justify-between mt-1 gap-2">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="Coyutla">Coyutla</span>
                                <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">3</span>
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3 border border-slate-100 dark:border-slate-800 flex flex-col justify-between">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Por región</span>
                            <div class="flex items-center justify-between mt-1 gap-2">
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 truncate" title="Golfo 1">Golfo 1</span>
                                <span class="text-xs font-black text-[#E31B23] bg-red-100/60 dark:bg-red-950/50 px-1.5 py-0.5 rounded shrink-0">8</span>
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
                        <p class="text-[11px] text-slate-400">Agosto 2026</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-2xl font-black text-[#0A192F] dark:text-white block">18</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes abiertas</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-2xl font-black text-[#0A192F] dark:text-white block">0</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Contrataciones</span>
                        </div>
                    </div>
                </div>

                <!-- Indicadores del año -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Indicadores del año</p>
                        <p class="text-[11px] text-slate-400">2026</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-2xl font-black text-[#0A192F] dark:text-white block">41</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes activas</span>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-2xl font-black text-[#0A192F] dark:text-white block">85</span>
                            <span class="text-[10px] font-bold text-slate-500 uppercase">Vacantes cubiertas</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Resultados Históricos (2025 y 2026) en 2 columnas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Resultados 2025 -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Resultados 2025</p>
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Vacantes</span> <span class="font-bold text-slate-800 dark:text-slate-200">6</span></div>
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Ingresos</span> <span class="font-bold text-slate-800 dark:text-slate-200">1</span></div>
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Cobertura</span> <span class="font-bold text-[#E31B23]">17%</span></div>
                </div>
            </div>

            <!-- Resultados 2026 -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-3">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Resultados 2026</p>
                <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Vacantes</span> <span class="font-bold text-slate-800 dark:text-slate-200">126</span></div>
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Ingresos</span> <span class="font-bold text-slate-800 dark:text-slate-200">32</span></div>
                    <div class="flex justify-between py-2"><span class="text-slate-500 font-semibold uppercase">Cobertura</span> <span class="font-bold text-emerald-600">25%</span></div>
                </div>
            </div>

        </div>

        <!-- SECCIÓN INFERIOR: Control detallado de vacantes -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 dark:border-slate-800 pb-4">
                <div>
                    <h2 class="text-lg font-bold text-[#0A192F] dark:text-white uppercase tracking-wide">Control detallado de vacantes</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Selecciona una vacante para consultar su expediente completo.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3 text-[11px] font-semibold text-slate-600 dark:text-slate-300">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded bg-emerald-600"></span>
                        <span>0–5 días</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded bg-amber-500"></span>
                        <span>6–11 días</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded bg-orange-500"></span>
                        <span>12–20 días</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded bg-[#E31B23]"></span>
                        <span>Más de 20 días</span>
                    </div>
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
                            <th class="py-3 px-3">Categoria</th>
                            <th class="py-3 px-3">Región</th>
                            <th class="py-3 px-3">Responsable</th>
                            <th class="py-3 px-3 text-center">Dias vacante</th>
                            <th class="py-3 px-3 text-center">Clasificación</th>
                            <th class="py-3 px-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">Cerrada</span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#0047AB]">669308</td>
                            <td class="py-3 px-3 text-slate-500">21/11/2025</td>
                            <td class="py-3 px-3 font-semibold text-slate-900 dark:text-white">Gerente Regional</td>
                            <td class="py-3 px-3">Confidencial</td>
                            <td class="py-3 px-3">Golfo 3</td>
                            <td class="py-3 px-3">N/A</td>
                            <td class="py-3 px-3">Corporativo</td>
                            <td class="py-3 px-3">Lic. Argelia</td>
                            <td class="py-3 px-3 text-center text-slate-400 font-mono">—</td>
                            <td class="py-3 px-3 text-center"><span class="text-emerald-700 font-bold">En tiempo</span></td>
                            <td class="py-3 px-3 text-center">
                                <a href="#" class="inline-flex px-3 py-1 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold transition-colors">Ver detalle</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Abierta</span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-[#0047AB]">368504</td>
                            <td class="py-3 px-3 text-slate-500">09/12/2025</td>
                            <td class="py-3 px-3 font-semibold text-slate-900 dark:text-white">Encargado de Almacén</td>
                            <td class="py-3 px-3">Publica</td>
                            <td class="py-3 px-3">Coyutla</td>
                            <td class="py-3 px-3">B</td>
                            <td class="py-3 px-3">Norte 1</td>
                            <td class="py-3 px-3">Ángel Hernández</td>
                            <td class="py-3 px-3 text-center bg-[#E31B23] text-white font-bold">253</td>
                            <td class="py-3 px-3 text-center"><span class="text-[#E31B23] font-bold">Critica</span></td>
                            <td class="py-3 px-3 text-center">
                                <a href="#" class="inline-flex px-3 py-1 rounded-lg border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold transition-colors">Ver detalle</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-layouts::app>