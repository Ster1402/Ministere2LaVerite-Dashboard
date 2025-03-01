<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Messagerie de la Chapel') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('messages.index') }}">{{ __('Messageries') }}</a></div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('ChapelMail') }}
    </x-slot>

    <x-tab-layout
        tab2="{{ __('Envoyé (' . $messagesSent->total() . ')') }}"
        subtitle="{{ __('Reçu (' . $messagesReceived->total() . ')') }}">
        <x-slot name="tutorial">
            <div class="card-body">
                <ul class="nav nav-pills">

                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('messages.create') }}">Nouveau <span
                                class="badge badge-white">+</span></a>
                    </li>
                    <li class="nav-item" style="padding-left: 15px;">
                        <a class="nav-link" href="#">
                            <span class="badge badge-primary">{{ $messagesSent->total() }}</span>
                            Messages envoyé(s)
                        </a>
                    </li>
                </ul>
            </div>
            <x-table-messages :data="$messagesSent"/>
        </x-slot>
        <x-slot name="home">
            <x-table-messages :data="$messagesReceived"/>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
