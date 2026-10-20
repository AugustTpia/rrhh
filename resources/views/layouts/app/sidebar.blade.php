<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-[#E31B23] selection:text-white">

        <flux:sidebar sticky collapsible="mobile" class="border-e border-slate-800/80 bg-slate-900 shadow-2xl">
            <flux:sidebar.header class="border-b border-slate-800/60 bg-slate-950/40 px-5 py-6">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="pl-4 pr-3">
                <flux:sidebar.group :heading="__('Plataforma')">
                    <flux:sidebar.item
                        icon="home"
                        :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')"
                        wire:navigate
                        class="rounded-xl font-medium hover:bg-slate-800/60"
                    >
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="briefcase"
                        :href="route('vacancies.index')"
                        :current="request()->routeIs('vacancies.index')"
                        wire:navigate
                        class="rounded-xl font-medium hover:bg-slate-800/60"
                    >
                        {{ __('Vacantes') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="users"
                        :href="route('candidates.index')"
                        :current="request()->routeIs('candidates.index')"
                        wire:navigate
                        class="rounded-xl font-medium hover:bg-slate-800/60"
                    >
                        {{ __('Candidatos') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item
                        icon="clock"
                        :href="route('admin.sla')"
                        :current="request()->routeIs('admin.sla')"
                        wire:navigate
                        class="rounded-xl font-medium hover:bg-slate-800/60"
                    >
                        {{ __('SLA por etapa') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Contenido Portal')">
                    @foreach(['banners' => 'Banners', 'noticias' => 'Noticias', 'reconocimientos' => 'Reconocimientos', 'accesos' => 'Accesos', 'documentos' => 'Documentos', 'eventos' => 'Eventos', 'buzon' => 'Buzón'] as $key => $label)
                        <flux:sidebar.item
                            icon="photo"
                            :href="route('portal.manager', $key)"
                            :current="request()->routeIs('portal.manager') && request()->route()?->parameter('section') === $key"
                            wire:navigate
                            class="rounded-xl font-medium hover:bg-slate-800/60"
                        >
                            {{ $label }}
                        </flux:sidebar.item>
                    @endforeach
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <div class="border-t border-slate-800/60 bg-slate-950/40 p-4">
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            </div>
        </flux:sidebar>


        <!-- Mobile Header / User Menu -->
        <flux:header class="border-b border-slate-800/80 bg-slate-900 lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />
                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate text-slate-100">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate text-slate-400">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer text-red-400 hover:text-red-300" data-test="logout-button">
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>


        {{ $slot }}


        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist


        @fluxScripts
    </body>
</html>