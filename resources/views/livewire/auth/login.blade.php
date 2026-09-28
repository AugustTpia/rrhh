<x-layouts::auth :title="__('Acceso Corporativo - ATS')">
    <div class="fixed inset-0 z-50 w-screen h-screen bg-slate-950 lg:grid lg:grid-cols-12 overflow-y-auto">
        
        <!-- Columna Izquierda: Identidad Visual Inmersiva (Azulejos y Mármoles) -->
        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-12 bg-gradient-to-br from-[#0A192F] via-[#003888] to-[#0047AB] text-white overflow-hidden shadow-2xl">
            <!-- Patrón geométrico de fondo -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
            
            <!-- Acento superior con el Rojo Institucional -->
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#E31B23]/20 blur-3xl pointer-events-none"></div>

            <!-- Cabecera de la Marca -->
            <div class="relative z-10 flex items-center space-x-3">
                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 shadow-inner">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-bold tracking-widest uppercase text-blue-300">Plataforma ATS</span>
                    <h3 class="text-lg font-bold tracking-tight text-white">El Gigante de los Azulejos</h3>
                </div>
            </div>

            <!-- Contenido Central Inspirador -->
            <div class="relative z-10 my-auto space-y-6 max-w-lg">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#E31B23]/30 border border-[#E31B23]/50 text-red-200 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-[#E31B23] animate-pulse"></span>
                    <span>Gestión de Reclutamiento Autónomo</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Optimiza el talento que construye <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white decoration-[#E31B23] decoration-4">grandes proyectos</span>.
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Sistema centralizado para la administración de sucursales, control de vacantes operativas y seguimiento del pipeline de candidatos sin fricciones.
                </p>
            </div>

            <!-- Pie de la columna izquierda -->
            <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-white/10 pt-6">
                <span>Arquitectura de Reclutamiento Empresarial</span>
                <span>v1.3 Enterprise</span>
            </div>
        </div>

        <!-- Columna Derecha: Formulario de Acceso de Alta Precisión -->
        <div class="col-span-12 lg:col-span-5 flex items-center justify-center p-6 sm:p-12 bg-white dark:bg-slate-900 min-h-screen lg:min-h-0">
            <div class="w-full max-w-sm space-y-8">
                
                <!-- Identidad móvil o compacta -->
                <div class="space-y-2 text-center lg:text-left">
                    <div class="inline-flex lg:hidden items-center justify-center w-12 h-12 rounded-xl bg-[#0047AB]/10 text-[#0047AB] mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Iniciar Sesión
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Introduce tus credenciales corporativas para continuar
                    </p>
                </div>

                <!-- Estado de Sesión -->
                <x-auth-session-status class="rounded-xl bg-blue-50 dark:bg-blue-950/50 p-3 text-xs text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-800" :status="session('status')" />

                <!-- Formulario -->
                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <flux:input
                            name="email"
                            :label="__('Correo o Usuario')"
                            :value="old('email')"
                            type="text"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="JORGE.TAPIA o empleado@azulejos.com"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <flux:label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                {{ __('Contraseña') }}
                            </flux:label>
                            @if (Route::has('password.request'))
                                <flux:link class="text-xs font-semibold text-[#0047AB] hover:underline dark:text-blue-400" :href="route('password.request')" wire:navigate>
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </flux:link>
                            @endif
                        </div>
                        <flux:input
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            :placeholder="__('••••••••••••')"
                            viewable
                        />
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <flux:checkbox name="remember" :label="__('Recordar en este equipo')" :checked="old('remember')" />
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 inline-flex items-center justify-center font-semibold text-white bg-[#0047AB] hover:bg-[#003888] rounded-xl shadow-lg shadow-blue-600/20 focus:outline-none focus:ring-2 focus:ring-[#0047AB] focus:ring-offset-2 transition-all duration-200 active:scale-[0.99]"
                            data-test="login-button">
                            <span>{{ __('Ingresar al Sistema') }}</span>
                            <svg class="w-5 h-5 ml-2 -mr-1 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Footer del formulario -->
                <div class="text-center space-y-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ __('¿No tienes cuenta activa?') }}</span>
                        <flux:link class="font-semibold text-[#E31B23] hover:underline ms-1" :href="route('register')" wire:navigate>
                            {{ __('Solicitar alta corporativa') }}
                        </flux:link>
                    </p>
                </div>

            </div>
        </div>

    </div>
</x-layouts::auth>