<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets Utilisateurs') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Gestion des utilisateurs') }}</a></div>
                <div class="breadcrumb-item">{{ __('Utilisateurs') }}</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Utilisateurs') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des utilisateurs') }}">

        <x-slot name="home">
            <x-table-users/>
        </x-slot>

        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title"> 🛠️ Gestion des comptes des utilisateurs finaux</h3>
                <br>
                <div class="tip">
                    <h5>Ajouter un utilisateur final</h5>
                    <p>Pour ajouter un nouvel utilisateur final, cliquez sur le bouton <span class="highlight">"Ajouter un utilisateur final"</span>
                        situé dans la section de gestion des comptes des utilisateurs finaux. Vous serez dirigé vers un
                        formulaire où vous pourrez saisir les informations nécessaires pour le nouvel utilisateur final.
                    </p>
                </div>
                <br>
                <div class="tip">
                    <h5>Opérations CRUD (Créer, Lire, Mettre à jour, Supprimer)</h5>
                    <p>Sur chaque entrée dans la liste des utilisateurs finaux, vous trouverez des options pour
                        effectuer différentes opérations :</p>
                    <ul>
                        <li><strong>Lire :</strong> Cliquez sur l'utilisateur final respectif pour voir ses détails et
                            ses autorisations.
                        </li>
                        <li><strong>Mettre à jour :</strong> Modifiez les informations de l'utilisateur final en
                            cliquant sur l'option de mise à jour. Vous serez redirigé vers un formulaire où vous pourrez
                            apporter les modifications nécessaires.
                        </li>
                        <li><strong>Supprimer :</strong> Si vous souhaitez supprimer un utilisateur final, cliquez sur
                            l'option de suppression. Assurez-vous de confirmer votre action car cette opération est
                            irréversible.
                        </li>
                    </ul>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>

</x-app-layout>
