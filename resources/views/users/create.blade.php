<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets Utilisateurs') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des utilisateurs') }}</a></div>
                <div class="breadcrumb-item">{{ __('Utilisateurs') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Utilisateurs') }}
    </x-slot>


</x-app-layout>
