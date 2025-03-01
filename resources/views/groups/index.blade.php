<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets des Groupes') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">{{ __('Gestion des groupes') }}</a></div>
                <div class="breadcrumb-item">{{ __('Groupes') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Groupes') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des groupes / catégories') }}">
        <x-slot name="home">
            <x-table-groups />
        </x-slot>

        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des Catégories de Ressources</h3>
                <br>
                <div class="tip">
                    <h5>Ajout d'une Catégorie de Ressources</h5>
                    <p>Pour ajouter une nouvelle catégorie de ressources, cliquez sur le bouton <span class="highlight">"Ajouter une Catégorie"</span> situé dans la section de gestion des catégories de ressources. Vous serez dirigé vers un formulaire où vous pourrez saisir les informations nécessaires pour la nouvelle catégorie.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Modification d'une Catégorie de Ressources</h5>
                    <p>Pour modifier une catégorie de ressources existante, cliquez sur l'option de modification correspondante. Vous serez redirigé vers un formulaire où vous pourrez effectuer les changements nécessaires.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Suppression d'une Catégorie de Ressources</h5>
                    <p>Si vous souhaitez supprimer une catégorie de ressources, cliquez sur l'option de suppression correspondante. Assurez-vous de confirmer votre action, car cette opération est irréversible.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Impression de la Liste des Catégories de Ressources</h5>
                    <p>Pour télécharger la liste de vos catégories de ressources, utilisez l'option de téléchargement disponible dans la section de gestion des catégories de ressources.</p>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
