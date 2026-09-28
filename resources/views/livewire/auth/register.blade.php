<x-layouts::auth :title="__('Registro Corporativo - ATS')">
    <div class="fixed inset-0 z-50 w-screen h-screen bg-slate-950 lg:grid lg:grid-cols-12 overflow-y-auto">
        
        <!-- Columna Izquierda: Identidad Visual Inmersiva -->
        <div class="hidden lg:flex lg:col-span-7 relative flex-col justify-between p-12 bg-gradient-to-br from-[#0A192F] via-[#003888] to-[#0047AB] text-white overflow-hidden shadow-2xl">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#E31B23]/20 blur-3xl pointer-events-none"></div>

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

            <div class="relative z-10 my-auto space-y-6 max-w-lg">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-[#E31B23]/30 border border-[#E31B23]/50 text-red-200 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-[#E31B23] animate-pulse"></span>
                    <span>Alta de Credenciales y Perfiles</span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-white leading-tight">
                    Construye el equipo ideal con <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white decoration-[#E31B23] decoration-4">gestión autónoma</span>.
                </h1>
                <p class="text-slate-300 text-sm leading-relaxed">
                    Registra tu cuenta corporativa para administrar sucursales, vacantes y el pipeline completo de selección sin dependencias externas.
                </p>
            </div>

            <div class="relative z-10 flex items-center justify-between text-xs text-slate-400 border-t border-white/10 pt-6">
                <span>Seguridad de Acceso Interno</span>
                <span>v1.3 Enterprise</span>
            </div>
        </div>

        <!-- Columna Derecha: Formulario de Registro -->
        <div class="col-span-12 lg:col-span-5 flex items-center justify-center p-6 sm:p-12 bg-white dark:bg-slate-900 min-h-screen lg:min-h-0">
            <div class="w-full max-w-md space-y-6">
                
                <div class="space-y-2 text-center lg:text-left">
                    <div class="inline-flex lg:hidden items-center justify-center w-12 h-12 rounded-xl bg-[#0047AB]/10 text-[#0047AB] mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
                        Crear cuenta corporativa
                    </h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Ingresa tus datos para registrarte en el sistema ATS
                    </p>
                </div>

                <x-auth-session-status class="rounded-xl bg-blue-50 dark:bg-blue-950/50 p-3 text-xs text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-800" :status="session('status')" />

                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1">
                        <flux:input
                            name="name"
                            :label="__('Nombre completo')"
                            :value="old('name')"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            :placeholder="__('Ej. Juan Pérez')"
                        />
                    </div>

                    <div class="space-y-1">
                        <flux:input
                            name="email"
                            :label="__('Correo electrónico')"
                            :value="old('email')"
                            type="email"
                            required
                            autocomplete="email"
                            placeholder="empleado@azulejos.com"
                        />
                    </div>

                    <div class="space-y-1">
                        <flux:input
                            name="password"
                            :label="__('Contraseña')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('••••••••••••')"
                            passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                            viewable
                        />
                    </div>

                    <div class="space-y-1">
                        <flux:input
                            name="password_confirmation"
                            :label="__('Confirmar contraseña')"
                            type="password"
                            required
                            autocomplete="new-password"
                            :placeholder="__('••••••••••••')"
                            passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                            viewable
                        />
                    </div>

                    <div class="pt-2">
                        <button 
                            type="submit" 
                            class="w-full py-3.5 px-4 inline-flex items-center justify-center font-semibold text-white bg-[#0047AB] hover:bg-[#003888] rounded-xl shadow-lg shadow-blue-600/20 focus:outline-none focus:ring-2 focus:ring-[#0047AB] focus:ring-offset-2 transition-all duration-200 active:scale-[0.99]"
                            data-test="register-user-button">
                            <span>{{ __('Registrar Cuenta') }}</span>
                            <svg class="w-5 h-5 ml-2 -mr-1 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>

                <div class="text-center space-y-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
                        <flux:link class="font-semibold text-[#E31B23] hover:underline ms-1" :href="route('login')" wire:navigate>
                            {{ __('Iniciar sesión') }}
                        </flux:link>
                    </p>
                </div>

            </div>
        </div>

    </div>
</x-layouts::auth>