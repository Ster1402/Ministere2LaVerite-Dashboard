<x-app-layout>
    <x-slot name="header">
        <x-banner title="{{ __('Onglets Administrateur') }}">
            <x-slot name="breadcrumb">
                <div class="breadcrumb-item"><a href="#">Roles & Access</a></div>
                <div class="breadcrumb-item">Administrateurs</div>
            </x-slot>
        </x-banner>
    </x-slot>

    <x-slot name="subtitle">
        {{ __('Administrateurs') }}
    </x-slot>

    <x-tab-layout subtitle="{{ __('Liste des administrateurs') }}">
        <x-slot name="home">
            <x-table-admins/>
        </x-slot>

        <x-slot name="tutorial">
            <div class="container">
                <h3 class="title">🛠️ Gestion des comptes administrateurs</h3>
                <br>
                <div class="tip">
                    <h5>Ajout d'un administrateur</h5>
                    <p>Pour ajouter un nouvel administrateur, cliquez sur le bouton <span
                            class="highlight">"Ajouter un administrateur"</span> situé dans la section
                        de gestion des comptes administrateurs. Vous serez redirigé vers un formulaire
                        où vous pourrez saisir les informations nécessaires pour le nouvel
                        administrateur.</p>
                </div>
                <br>
                <div class="tip">
                    <h5>Opérations RUD (Lecture, Mise à jour, Suppression)</h5>
                    <p>Pour chaque entrée dans la liste des administrateurs, vous trouverez des options
                        pour effectuer différentes opérations :</p>
                    <ul>
                        <li><strong>Lecture :</strong> Cliquez sur l'administrateur correspondant pour
                            voir ses détails et ses permissions.
                        </li>
                        <li><strong>Mise à jour :</strong> Modifiez les informations de l'administrateur
                            en cliquant sur l'option de mise à jour. Vous serez redirigé vers un
                            formulaire où vous pourrez apporter les modifications nécessaires.
                        </li>
                        <li><strong>Suppression :</strong> Si vous souhaitez supprimer un
                            administrateur, cliquez sur l'option de suppression. Assurez-vous de
                            confirmer votre action car cette opération est irréversible.
                        </li>
                    </ul>
                </div>
            </div>
        </x-slot>
    </x-tab-layout>
</x-app-layout>
