<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des Secteurs') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des secteurs') }}</a></div>
                <div class="breadcrumb-item">{{ __('Secteurs') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Secteurs') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des secteurs') }}">
        <x-slot name="home">
            <x-table-sectors/>
        </x-slot>
        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des Secteurs</h3>
                <br>
                <div class="tip">
                    <h5>Création d'un Secteur</h5>
                    <p>Pour créer un nouveau secteur, cliquez sur le bouton <span
                            class="highlight">"Ajouter un Secteur"</span>
                        situé dans la section de gestion des secteurs. Vous serez dirigé vers un formulaire où vous
                        pourrez saisir les informations nécessaires pour le nouveau secteur.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Attribution des Membres à un Secteur</h5>
                    <p>Après avoir créé vos secteurs, vous pouvez attribuer des membres à chacun d'eux. Cliquez sur
                        le secteur respectif, puis sélectionnez les membres que vous souhaitez y associer.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Suppression d'un Secteur</h5>
                    <p>Si vous souhaitez supprimer un secteur, cliquez sur l'option de suppression correspondante.
                        Assurez-vous de confirmer votre action, car cette opération est irréversible.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Impression de la Liste des Secteurs</h5>
                    <p>Pour télécharger la liste de vos secteurs, utilisez l'option de téléchargement disponible dans la
                        section de gestion des secteurs.</p>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>
</x-app-layout>
