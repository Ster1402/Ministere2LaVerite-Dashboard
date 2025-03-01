<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des Assemblées') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des assemblées') }}</a></div>
                <div class="breadcrumb-item">{{ __('Assemblées') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Assemblées') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des assemblées') }}">
        <x-slot name="home">
            <x-table-assemblies/>
        </x-slot>
        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des Assemblées</h3>
                <br>
                <div class="tip">
                    <h5>Création d'une Assemblée</h5>
                    <p>Pour créer une nouvelle assemblée, cliquez sur le bouton <span class="highlight">"Ajouter une Assemblée"</span>
                        situé dans la section de gestion des assemblées. Vous serez dirigé vers un formulaire où
                        vous
                        pourrez saisir les informations nécessaires pour la nouvelle assemblée.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Attribution des Membres à une Assemblée</h5>
                    <p>Après avoir créé vos assemblées, vous pouvez attribuer des membres à chacune d'elles. Cliquez
                        sur
                        l'assemblée respective, puis sélectionnez les membres que vous souhaitez y associer.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Suppression d'une Assemblée</h5>
                    <p>Si vous souhaitez supprimer une assemblée, cliquez sur l'option de suppression
                        correspondante.
                        Assurez-vous de confirmer votre action, car cette opération est irréversible.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Impression de la Liste des Assemblées</h5>
                    <p>Pour télécharger la liste de vos assemblées, utilisez l'option de téléchargement disponible dans la
                        section de gestion des assemblées.</p>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
