<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des Évènements') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des évènements') }}</a></div>
                <div class="breadcrumb-item">{{ __('Évènements') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Évènements') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des Évènements') }}">
        <x-slot name="home">
            <x-table-events />
        </x-slot>
        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des événements</h3>
                <br>
                <div class="tip">
                    <h5>Ajouter un événement</h5>
                    <p>Pour ajouter un nouvel événement, cliquez sur le bouton <span
                            class="highlight">"Ajouter un événement"</span> situé dans la section de gestion des événements. Vous
                        serez dirigé vers un formulaire où vous pourrez saisir les informations nécessaires pour le nouvel événement.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)</h5>
                    <p>Pour chaque entrée dans la liste des événements, vous trouverez des options pour effectuer différentes opérations :</p>
                    <ul>
                        <li><strong>Lire :</strong> Cliquez sur l'événement correspondant pour afficher ses détails.</li>
                        <li><strong>Modifier :</strong> Modifiez les informations de l'événement en cliquant sur l'option de mise à jour. Vous serez redirigé vers un formulaire où vous pourrez apporter les modifications nécessaires.</li>
                        <li><strong>Supprimer :</strong> Si vous souhaitez supprimer un événement, cliquez sur l'option de suppression. Assurez-vous de confirmer votre action car cette opération est irréversible.</li>
                    </ul>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
