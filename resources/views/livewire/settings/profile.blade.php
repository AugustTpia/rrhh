<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading level="2" class="sr-only">{{ __('Configuración del perfil') }}</flux:heading>

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Actualiza tu nombre y correo electrónico')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Nombre')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Correo electrónico')" type="email" required autocomplete="email" />
            </div>

            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>