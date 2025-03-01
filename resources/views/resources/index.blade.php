<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des ressources') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des ressources') }}</a></div>
                <div class="breadcrumb-item">{{ __('Ressources') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Ressources de la Chapel') }}
    </x-slot>

    <x-tab-layout subtitle="Liste des ressources">
        <x-slot name="home">
            <x-table-resources />
        </x-slot>
        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des Ressources</h3>
                <br>
                <div class="tip">
                    <h5>Ajout d'une Ressource</h5>
                    <p>Pour ajouter une nouvelle ressource, cliquez sur le bouton <span class="highlight">"Ajouter une Ressource"</span> situé dans la section de gestion des ressources. Vous serez dirigé vers un formulaire où vous pourrez saisir les informations nécessaires pour la nouvelle ressource.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Attribution d'une Ressource à un Utilisateur</h5>
                    <p>Après avoir créé vos ressources, vous pouvez les attribuer à des utilisateurs. Cliquez sur la ressource respective, puis sélectionnez l'utilisateur auquel vous souhaitez l'assigner.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Gestion des Groupes de Ressources</h5>
                    <p>Vous pouvez regrouper vos ressources en créant des groupes. Cela facilite la gestion et l'attribution de plusieurs ressources à la fois.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Modification d'une Ressource</h5>
                    <p>Pour modifier une ressource existante, cliquez sur l'option de modification correspondante. Vous serez redirigé vers un formulaire où vous pourrez effectuer les changements nécessaires.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Suppression d'une Ressource</h5>
                    <p>Si vous souhaitez supprimer une ressource, cliquez sur l'option de suppression correspondante. Assurez-vous de confirmer votre action, car cette opération est irréversible.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Impression de la Liste des Ressources</h5>
                    <p>Pour télécharger la liste de vos ressources, utilisez l'option de téléchargement disponible dans la section de gestion des ressources.</p>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
