<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Interno | El Gigante de los Azulejos y Mármoles</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Alpine.js para interactividad dinámica en el calendario, carrusel, filtros y buzón -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    @php
        // Datos vía LandingController (hexagonal). Fallback legacy si las tablas están vacías.
        $search = $search ?? request('search');
        $categoria = $categoria ?? request('categoria', 'todos');

        $noticiasCorporativas = $noticiasCorporativas ?? collect();
        if ($noticiasCorporativas->isEmpty()) {
            $noticiasCorporativas = collect([
                [
                    'id' => 1,
                    'titulo' => 'Mensaje de Dirección: Cierre exitoso del tercer trimestre y metas logradas',
                    'extracto' => 'Agradecemos el compromiso extraordinario de todas las sucursales y centros de distribución por superar las expectativas de distribución este periodo.',
                    'categoria' => 'comunicados',
                    'fecha' => '12 de Septiembre, 2026',
                    'autor' => 'Dirección General',
                    'badge_class' => 'bg-blue-100 text-[#0047AB]',
                    'destacado' => true,
                    'imagen' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80'
                ],
                [
                    'id' => 2,
                    'titulo' => 'Nuevo protocolo de permisos, vacaciones y guías operativas 2026-2027',
                    'extracto' => 'Consulta los lineamientos actualizados y descarga los formatos oficiales en el repositorio de documentos claves para agilizar tus trámites.',
                    'categoria' => 'recursos',
                    'fecha' => '10 de Septiembre, 2026',
                    'autor' => 'Recursos Humanos',
                    'badge_class' => 'bg-amber-100 text-amber-800',
                    'destacado' => false,
                    'imagen' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80'
                ],
                [
                    'id' => 3,
                    'titulo' => 'Preparativos oficiales para el Gran Convivio Patrio y Festejos del Mes',
                    'extracto' => 'Conoce la programación de eventos festivos y los días de asueto oficial autorizados para todo el personal operativo y administrativo.',
                    'categoria' => 'cultura',
                    'fecha' => '08 de Septiembre, 2026',
                    'autor' => 'Comité de Cultura y Eventos',
                    'badge_class' => 'bg-red-100 text-[#E31B23]',
                    'destacado' => false,
                    'imagen' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80'
                ],
                [
                    'id' => 4,
                    'titulo' => 'Lanzamiento exclusivo de la nueva línea de Mármol Italiano Premium',
                    'extracto' => 'Ya se encuentra disponible el catálogo corporativo actualizado con acabados de alta gama para proyectos residenciales y comerciales de alta exigencia.',
                    'categoria' => 'comunicados',
                    'fecha' => '05 de Septiembre, 2026',
                    'autor' => 'Gerencia Comercial',
                    'badge_class' => 'bg-blue-100 text-[#0047AB]',
                    'destacado' => false,
                    'imagen' => 'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?auto=format&fit=crop&w=800&q=80'
                ],
            ]);

            if ($categoria && $categoria !== 'todos') {
                $noticiasCorporativas = $noticiasCorporativas->where('categoria', $categoria);
            }

            if ($search) {
                $noticiasCorporativas = $noticiasCorporativas->filter(function ($item) use ($search) {
                    return str_contains(strtolower($item['titulo']), strtolower($search)) ||
                           str_contains(strtolower($item['extracto']), strtolower($search));
                });
            }
        }

        // Banners: desde DB con fallback legacy idéntico al diseño actual.
        $slidesFallback = [
            [
                'titulo' => 'Nueva Colección Mármol Italiano 2026',
                'subtitulo' => 'Exclusividad y alta gama para proyectos residenciales y comerciales exigentes.',
                'imagen' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1400&q=80',
                'badge' => 'Lanzamiento Exclusivo'
            ],
            [
                'titulo' => 'Gran Convivio Patrio y Festejos de Septiembre',
                'subtitulo' => 'Consulta la programación de eventos y días de asueto oficial autorizados.',
                'imagen' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1400&q=80',
                'badge' => 'Cultura y Eventos'
            ],
            [
                'titulo' => 'Optimización de Procesos y Guías Operativas',
                'subtitulo' => 'Actualiza tus solicitudes de permisos y vacaciones en el nuevo portal.',
                'imagen' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=1400&q=80',
                'badge' => 'Recursos Humanos'
            ]
        ];
        $slides = !empty($banners) ? collect($banners)->map(fn ($b) => [
            'titulo' => $b['titulo'] ?? $b['title'] ?? '',
            'subtitulo' => $b['subtitulo'] ?? $b['subtitle'] ?? '',
            'imagen' => $b['imagen'] ?? $b['image_src'] ?? '',
            'badge' => $b['badge'] ?? '',
        ])->values()->all() : $slidesFallback;

        $recognitions = $recognitions ?? [];
        $quickLinks = $quickLinks ?? [];
        $documents = $documents ?? [];
        $events = $events ?? [];
    @endphp

    <!-- Navbar / Cabecera Corporativa -->
    <header class="bg-[#0A192F] border-b border-blue-900/60 sticky top-0 z-50 text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#0047AB] to-[#003888] flex items-center justify-center font-extrabold text-white shadow-md border border-blue-400/20">
                    GM
                </div>
                <div>
                    <span class="text-[10px] font-extrabold tracking-widest uppercase text-red-400 block">Portal Interno</span>
                    <span class="font-extrabold text-sm sm:text-base tracking-tight text-white">El Gigante de los Azulejos y Mármoles</span>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-blue-200 hover:text-white transition">Ir al Sistema</a>
                @else
                    <a href="{{ route('login') }}" class="bg-[#0047AB] text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-[#003888] transition shadow-md border border-blue-400/30">Iniciar Sesión</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Área de Banners Principales (Carrusel Corporativo Dinámico) -->
    <section class="bg-[#0A192F] border-b border-slate-800 relative overflow-hidden shadow-lg" x-data='{
        activeSlide: 0,
        slides: @js($slides),
        timer: null,
        init() {
            this.timer = setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            }, 6000);
        }
    }' x-init="init()">
        <div class="relative h-64 sm:h-80 lg:h-96 w-full overflow-hidden">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" 
                     x-transition:enter="transition ease-out duration-700" 
                     x-transition:enter-start="opacity-0 scale-105" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     x-transition:leave="transition ease-in duration-500" 
                     x-transition:leave-start="opacity-100 scale-100" 
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 w-full h-full">
                    <img :src="slide.imagen" :alt="slide.titulo" class="w-full h-full object-cover opacity-55">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#0A192F] via-[#0A192F]/80 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center max-w-7xl mx-auto px-6 sm:px-12">
                        <div class="max-w-2xl space-y-3 text-white">
                            <span class="bg-[#0047AB] text-white text-[10px] font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider shadow" x-text="slide.badge"></span>
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight" x-text="slide.titulo"></h2>
                            <p class="text-sm sm:text-base text-slate-200 leading-relaxed" x-text="slide.subtitulo"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <!-- Controles indicadores del carrusel -->
        <div class="absolute bottom-4 right-6 flex items-center space-x-2 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" :class="activeSlide === index ? 'w-8 bg-[#E31B23]' : 'w-2.5 bg-white/50 hover:bg-white'" class="h-2.5 rounded-full transition-all duration-300" :aria-label="'Ir a diapositiva ' + (index + 1)"></button>
            </template>
        </div>
    </section>

    <!-- Buscador Global Inteligente y Filtros por Categoría (Sin lupa y ancho completo) -->
    <section class="bg-gradient-to-br from-[#0A192F] via-[#003888] to-[#0047AB] text-white py-20 px-6 sm:px-8 shadow-xl relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#E31B23]/25 blur-3xl pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto text-center relative z-10 space-y-8">
            <div class="space-y-3 max-w-3xl mx-auto">
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">¿Qué información o recurso necesitas hoy?</h1>
                <p class="text-blue-100 text-sm sm:text-base leading-relaxed">Buscador global inteligente para encontrar noticias pasadas, comunicados oficiales, documentos o políticas rápidamente.</p>
            </div>
            
            <form action="{{ url('/') }}" method="GET" class="w-full flex flex-col sm:flex-row items-center gap-4">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por palabra clave, documento, política o noticia..." class="w-full px-6 py-5 rounded-2xl text-slate-900 bg-white shadow-2xl focus:outline-none focus:ring-4 focus:ring-[#E31B23]/50 focus:border-[#E31B23] transition-all text-base sm:text-lg placeholder-slate-400 font-medium border border-slate-300">
                </div>
                <input type="hidden" name="categoria" value="{{ $categoria }}">
                <button type="submit" class="w-full sm:w-auto bg-[#E31B23] hover:bg-red-700 text-white font-bold px-10 py-5 rounded-2xl transition-all shadow-2xl text-base sm:text-lg shrink-0 flex items-center justify-center space-x-2">
                    <span>Buscar</span>
                </button>
            </form>

            <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                <a href="?categoria=todos&search={{ $search }}" class="text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-all font-semibold shadow-sm {{ $categoria == 'todos' ? 'bg-[#E31B23] text-white shadow-md ring-2 ring-red-400/50 scale-105' : 'bg-white/10 hover:bg-white/20 text-blue-100 border border-white/10' }}">Todos</a>
                <a href="?categoria=comunicados&search={{ $search }}" class="text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-all font-semibold shadow-sm {{ $categoria == 'comunicados' ? 'bg-[#E31B23] text-white shadow-md ring-2 ring-red-400/50 scale-105' : 'bg-white/10 hover:bg-white/20 text-blue-100 border border-white/10' }}">Comunicados Oficiales</a>
                <a href="?categoria=recursos&search={{ $search }}" class="text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-all font-semibold shadow-sm {{ $categoria == 'recursos' ? 'bg-[#E31B23] text-white shadow-md ring-2 ring-red-400/50 scale-105' : 'bg-white/10 hover:bg-white/20 text-blue-100 border border-white/10' }}">Documentos y Políticas</a>
                <a href="?categoria=cultura&search={{ $search }}" class="text-xs sm:text-sm px-5 py-2.5 rounded-xl transition-all font-semibold shadow-sm {{ $categoria == 'cultura' ? 'bg-[#E31B23] text-white shadow-md ring-2 ring-red-400/50 scale-105' : 'bg-white/10 hover:bg-white/20 text-blue-100 border border-white/10' }}">Cultura y Festejos</a>
            </div>
        </div>
    </section>

    <!-- Contenido Principal en Grid -->
    <main class="max-w-7xl mx-auto px-6 sm:px-8 py-14 flex-grow w-full grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Columna Izquierda / Central (2 cols) -->
        <div class="lg:col-span-2 space-y-10">
            
            <!-- Sección: Comunicados y Novedades (Búsqueda corregida) -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-[#0A192F] flex items-center gap-3">
                        <span class="w-3.5 h-3.5 bg-[#E31B23] rounded-full inline-block shadow-sm"></span> Comunicados y Novedades
                    </h2>
                    @if($search || $categoria !== 'todos')
                        <a href="{{ url('/') }}" class="text-xs text-[#0047AB] font-semibold hover:underline">Limpiar filtros</a>
                    @endif
                </div>
                
                <div class="space-y-6">
                    @if(!$search && $categoria === 'todos')
                        @php
                            $noticiaPrincipal = $noticiasCorporativas->where('destacado', true)->first() ?? $noticiasCorporativas->first();
                            $otrasNoticias = $noticiasCorporativas->reject(function ($item) use ($noticiaPrincipal) {
                                return $noticiaPrincipal && $item['id'] === $noticiaPrincipal['id'];
                            });
                        @endphp

                        @if($noticiaPrincipal)
                            <div class="relative rounded-2xl overflow-hidden bg-[#0A192F] text-white shadow-lg border border-slate-200 group">
                                <div class="relative h-64 sm:h-80 w-full overflow-hidden">
                                    <img src="{{ $noticiaPrincipal['imagen'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80' }}" alt="Banner Noticia" class="w-full h-full object-cover opacity-65 group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0A192F] via-[#0A192F]/60 to-transparent"></div>
                                    <div class="absolute top-5 left-5 bg-[#E31B23] text-white text-[10px] font-bold px-3.5 py-1.5 rounded-full uppercase tracking-widest shadow">Comunicado Oficial</div>
                                </div>
                                <div class="p-6 sm:p-8 relative -mt-16 z-10 space-y-3">
                                    <div class="flex items-center space-x-2.5 text-xs text-blue-200 font-medium">
                                        <span class="bg-[#0047AB] px-3 py-1 rounded-md text-white shadow-sm">{{ $noticiaPrincipal['autor'] }}</span>
                                        <span>•</span>
                                        <span>{{ $noticiaPrincipal['fecha'] }}</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-extrabold leading-snug text-white">{{ $noticiaPrincipal['titulo'] }}</h3>
                                    <p class="text-sm sm:text-base text-slate-200 leading-relaxed pt-1">{{ $noticiaPrincipal['extracto'] }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-5">
                            @foreach($otrasNoticias as $item)
                                <article class="p-6 sm:p-7 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-[#0047AB] hover:shadow-md transition space-y-3">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="{{ $item['badge_class'] }} font-bold px-3.5 py-1 rounded-md uppercase text-[10px] tracking-wider">{{ $item['autor'] }}</span>
                                        <span class="font-medium">{{ $item['fecha'] }}</span>
                                    </div>
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 hover:text-[#0047AB] transition cursor-pointer leading-snug">{{ $item['titulo'] }}</h3>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $item['extracto'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <!-- Listado completo de resultados cuando hay búsqueda o categoría activa -->
                        <div class="space-y-5">
                            @forelse($noticiasCorporativas as $item)
                                <article class="p-6 sm:p-7 rounded-2xl bg-slate-50/80 border border-slate-200/80 hover:border-[#0047AB] hover:shadow-md transition space-y-3">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span class="{{ $item['badge_class'] }} font-bold px-3.5 py-1 rounded-md uppercase text-[10px] tracking-wider">{{ $item['autor'] }}</span>
                                        <span class="font-medium">{{ $item['fecha'] }}</span>
                                    </div>
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 hover:text-[#0047AB] transition cursor-pointer leading-snug">{{ $item['titulo'] }}</h3>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $item['extracto'] }}</p>
                                </article>
                            @empty
                                <div class="text-center py-12 text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-200 p-6">
                                    No se encontraron comunicados o noticias corporativas que coincidan con la búsqueda.
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            </section>

            <!-- Sección: Espacio de Reconocimiento -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-sm hover:shadow-md transition">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0A192F] mb-6 flex items-center gap-3">
                    <span class="w-3.5 h-3.5 bg-[#0047AB] rounded-full inline-block shadow-sm"></span> Espacio de Reconocimiento
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($recognitions as $rec)
                        <div class="p-6 {{ ($rec['type'] ?? '') === 'ascenso' ? 'bg-blue-50/80 border-blue-200/80' : 'bg-amber-50/80 border-amber-200/80' }} border rounded-2xl flex items-start space-x-4 shadow-sm">
                            <div class="w-12 h-12 {{ ($rec['type'] ?? '') === 'ascenso' ? 'bg-blue-200/90 text-[#0047AB]' : 'bg-amber-200/90 text-amber-800' }} rounded-xl flex items-center justify-center font-bold shrink-0 shadow-inner text-lg">{{ $rec['icon'] ?? '🏆' }}</div>
                            <div class="space-y-1.5">
                                <h4 class="font-bold text-slate-900 text-base">{{ $rec['title'] }}</h4>
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $rec['body'] }}</p>
                            </div>
                        </div>
                    @empty
                    <div class="p-6 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-start space-x-4 shadow-sm">
                        <div class="w-12 h-12 bg-amber-200/90 rounded-xl flex items-center justify-center font-bold text-amber-800 shrink-0 shadow-inner text-lg">🏆</div>
                        <div class="space-y-1.5">
                            <h4 class="font-bold text-slate-900 text-base">¡Feliz Aniversario Laboral!</h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Carlos Mendoza cumple 5 años en la sucursal Xalapa Centro. ¡Gracias por tu entrega y trayectoria!</p>
                        </div>
                    </div>
                    <div class="p-6 bg-blue-50/80 border border-blue-200/80 rounded-2xl flex items-start space-x-4 shadow-sm">
                        <div class="w-12 h-12 bg-blue-200/90 rounded-xl flex items-center justify-center font-bold text-[#0047AB] shrink-0 shadow-inner text-lg">⭐</div>
                        <div class="space-y-1.5">
                            <h4 class="font-bold text-slate-900 text-base">Ascenso Destacado</h4>
                            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">Reconocemos a Sofía Rivas por su reciente nombramiento como Gerente Regional de Operaciones.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>

            <!-- Sección: Canal de Retroalimentación (persiste en DB vía LandingController) -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-8 sm:p-10 shadow-sm hover:shadow-md transition">
                <h2 class="text-xl sm:text-2xl font-extrabold text-[#0A192F] mb-1">Canal de Retroalimentación y Buzón de Sugerencias</h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-6">Envía tus comentarios de forma anónima o directa para resolver dudas comunes o proponer mejoras operativas.</p>

                @if(session('feedback_ok'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-semibold flex items-center space-x-3 shadow-sm">
                        <span class="text-lg">✅</span>
                        <span>{{ session('feedback_ok') }}</span>
                    </div>
                @endif
                @error('body')
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-semibold">{{ $message }}</div>
                @enderror

                <form action="{{ route('feedback.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <textarea name="body" rows="4" placeholder="Escribe aquí tu sugerencia, felicitación o reporte..." class="w-full p-4 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0047AB] shadow-inner text-slate-800 leading-relaxed" required>{{ old('body') }}</textarea>
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <label class="flex items-center space-x-2.5 text-xs sm:text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" name="anonimo" value="1" class="w-4 h-4 rounded border-slate-300 text-[#0047AB] focus:ring-[#0047AB]">
                            <span>Enviar como comentario anónimo</span>
                        </label>
                        <button type="submit" class="w-full sm:w-auto bg-[#0047AB] hover:bg-[#003888] text-white font-extrabold px-10 py-4 rounded-xl transition shadow-lg text-sm sm:text-base shrink-0 flex items-center justify-center space-x-2">
                            <span>Enviar Comentario</span>
                        </button>
                    </div>
                </form>
            </section>

        </div>

        <!-- Columna Derecha: Accesos, Documentos y Calendario Dinámico e Intuitivo -->
        <div class="space-y-10" x-data="{ tabCalendario: 'todos' }">
            
            <!-- Directorio de Accesos Directos -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:shadow-md transition">
                <h3 class="font-extrabold text-[#0A192F] text-lg mb-5 pb-3 border-b border-slate-100">Directorio de Accesos Directos</h3>
                <ul class="space-y-3 text-sm">
                    @forelse($quickLinks as $link)
                        <li><a href="{{ $link['url'] ?? '#' }}" class="block p-3.5 rounded-xl hover:bg-blue-50/80 text-[#0047AB] font-semibold transition flex items-center justify-between border border-transparent hover:border-blue-100"><span>{{ $link['icon'] ?? '' }} {{ $link['label'] }}</span> &rarr;</a></li>
                    @empty
                    <li><a href="#" class="block p-3.5 rounded-xl hover:bg-blue-50/80 text-[#0047AB] font-semibold transition flex items-center justify-between border border-transparent hover:border-blue-100"><span>📌 Portal de Recursos Humanos</span> &rarr;</a></li>
                    <li><a href="#" class="block p-3.5 rounded-xl hover:bg-blue-50/80 text-[#0047AB] font-semibold transition flex items-center justify-between border border-transparent hover:border-blue-100"><span>📞 Contacto con Reclutamiento (ATS)</span> &rarr;</a></li>
                    <li><a href="#" class="block p-3.5 rounded-xl hover:bg-blue-50/80 text-[#0047AB] font-semibold transition flex items-center justify-between border border-transparent hover:border-blue-100"><span>💻 Soporte Técnico y Tickets</span> &rarr;</a></li>
                    <li><a href="#" class="block p-3.5 rounded-xl hover:bg-blue-50/80 text-[#0047AB] font-semibold transition flex items-center justify-between border border-transparent hover:border-blue-100"><span>📂 Directorio General de Sucursales</span> &rarr;</a></li>
                    @endforelse
                </ul>
            </section>

            <!-- Repositorio de Documentos Claves -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:shadow-md transition">
                <h3 class="font-extrabold text-[#0A192F] text-lg mb-2 pb-3 border-b border-slate-100">Repositorio de Documentos Claves</h3>
                <p class="text-xs text-slate-500 mb-5">Materiales informativos generales y guías operativas.</p>
                <ul class="space-y-3.5 text-xs sm:text-sm">
                    @forelse($documents as $doc)
                        <li class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-200 transition">
                            <span class="font-semibold text-slate-700">{{ $doc['name'] }}</span>
                            <a href="{{ $doc['download_url'] ?? $doc['external_url'] ?? '#' }}" class="text-[#E31B23] font-bold hover:underline shrink-0 ml-2">Descargar</a>
                        </li>
                    @empty
                    <li class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-200 transition">
                        <span class="font-semibold text-slate-700">Reglamento Interno 2026.pdf</span>
                        <a href="#" class="text-[#E31B23] font-bold hover:underline shrink-0 ml-2">Descargar</a>
                    </li>
                    <li class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-200 transition">
                        <span class="font-semibold text-slate-700">Formato Oficial Permisos.docx</span>
                        <a href="#" class="text-[#E31B23] font-bold hover:underline shrink-0 ml-2">Descargar</a>
                    </li>
                    <li class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:border-slate-200 transition">
                        <span class="font-semibold text-slate-700">Guía de Identidad Visual.pdf</span>
                        <a href="#" class="text-[#E31B23] font-bold hover:underline shrink-0 ml-2">Descargar</a>
                    </li>
                    @endforelse
                </ul>
            </section>

            <!-- Calendario de Eventos Dinámico e Intuitivo con Alpine.js -->
            <section class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="font-extrabold text-[#0A192F] text-lg">Calendario y Eventos</h3>
                    <span class="text-xs text-slate-500 font-semibold">Septiembre 2026</span>
                </div>

                <!-- Pestañas de filtrado dinámico intuitivo -->
                <div class="flex bg-slate-100 p-1 rounded-xl mb-5 text-[11px] font-bold">
                    <button @click="tabCalendario = 'todos'" :class="tabCalendario === 'todos' ? 'bg-white text-[#0A192F] shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg transition">Todos</button>
                    <button @click="tabCalendario = 'festivos'" :class="tabCalendario === 'festivos' ? 'bg-white text-[#E31B23] shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg transition">Festivos</button>
                    <button @click="tabCalendario = 'capacitacion'" :class="tabCalendario === 'capacitacion' ? 'bg-white text-[#0047AB] shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg transition">Capacitaciones</button>
                    <button @click="tabCalendario = 'cumple'" :class="tabCalendario === 'cumple' ? 'bg-white text-amber-800 shadow-sm' : 'text-slate-500 hover:text-slate-800'" class="flex-1 py-1.5 rounded-lg transition">Festejos</button>
                </div>

                <!-- Lista de Eventos (desde DB, fallback legacy) -->
                <div class="space-y-4 text-xs sm:text-sm">
                    @forelse($events as $ev)
                        @php
                            $evType = $ev['type'] ?? 'festivo';
                            $color = $evType === 'festivo' ? 'red' : ($evType === 'capacitacion' ? 'blue' : 'amber');
                            $textColor = $evType === 'festivo' ? 'text-[#E31B23]' : ($evType === 'capacitacion' ? 'text-[#0047AB]' : 'text-amber-800');
                            $bgColor = $evType === 'festivo' ? 'bg-red-100' : ($evType === 'capacitacion' ? 'bg-blue-100' : 'bg-amber-100');
                        @endphp
                        <div x-show="tabCalendario === 'todos' || tabCalendario === '{{ $evType }}'" class="flex items-start space-x-4 p-3.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                            <div class="{{ $bgColor }} {{ $textColor }} font-bold px-3.5 py-2.5 rounded-xl text-center shrink-0 shadow-sm">
                                <span class="block text-base sm:text-lg">{{ $ev['day'] ?? '--' }}</span>
                                <span class="text-[10px] uppercase tracking-wider font-extrabold">{{ $ev['month'] ?? '' }}</span>
                            </div>
                            <div class="space-y-1">
                                <span class="inline-block px-2 py-0.5 {{ $bgColor }} {{ $textColor }} text-[10px] font-bold rounded">{{ $ev['badge'] ?? $evType }}</span>
                                <h4 class="font-bold text-slate-900">{{ $ev['title'] }}</h4>
                                <p class="text-slate-500 text-xs leading-relaxed">{{ $ev['description'] }}</p>
                            </div>
                        </div>
                    @empty
                    <!-- Evento 1: Festivo -->
                    <div x-show="tabCalendario === 'todos' || tabCalendario === 'festivos'" class="flex items-start space-x-4 p-3.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="bg-red-100 text-[#E31B23] font-bold px-3.5 py-2.5 rounded-xl text-center shrink-0 shadow-sm">
                            <span class="block text-base sm:text-lg">15</span>
                            <span class="text-[10px] uppercase tracking-wider font-extrabold">SEP</span>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 bg-red-100 text-[#E31B23] text-[10px] font-bold rounded">Día Festivo</span>
                            <h4 class="font-bold text-slate-900">Aniversario y Convivio Patrio</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">Celebración general en todas las sucursales a partir de las 14:00 hrs.</p>
                        </div>
                    </div>

                    <!-- Evento 2: Capacitación / All-Hands -->
                    <div x-show="tabCalendario === 'todos' || tabCalendario === 'capacitacion'" class="flex items-start space-x-4 p-3.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="bg-blue-100 text-[#0047AB] font-bold px-3.5 py-2.5 rounded-xl text-center shrink-0 shadow-sm">
                            <span class="block text-base sm:text-lg">22</span>
                            <span class="text-[10px] uppercase tracking-wider font-extrabold">SEP</span>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 bg-blue-100 text-[#0047AB] text-[10px] font-bold rounded">All-Hands</span>
                            <h4 class="font-bold text-slate-900">Capacitación General</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">Reunión mensual vía streaming y asignación de metas trimestrales.</p>
                        </div>
                    </div>

                    <!-- Evento 3: Cumpleaños / Festejos -->
                    <div x-show="tabCalendario === 'todos' || tabCalendario === 'cumple'" class="flex items-start space-x-4 p-3.5 rounded-xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                        <div class="bg-amber-100 text-amber-800 font-bold px-3.5 py-2.5 rounded-xl text-center shrink-0 shadow-sm">
                            <span class="block text-base sm:text-lg">30</span>
                            <span class="text-[10px] uppercase tracking-wider font-extrabold">SEP</span>
                        </div>
                        <div class="space-y-1">
                            <span class="inline-block px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded">Cumpleaños</span>
                            <h4 class="font-bold text-slate-900">Festejo Colaboradores del Mes</h4>
                            <p class="text-slate-500 text-xs leading-relaxed">Reconocimiento a colaboradores cumpliendo años en el periodo de septiembre.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </section>

        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#0A192F] border-t border-slate-800 py-8 text-center text-xs text-slate-400">
        <p>&copy; {{ date('Y') }} El Gigante de los Azulejos y Mármoles. Todos los derechos reservados.</p>
    </footer>

</body>
</html>