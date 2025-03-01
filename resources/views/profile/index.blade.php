<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Votre profile') }}">
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ Auth::user()->name }}
    </x-slot>



</x-app-layout>
