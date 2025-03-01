<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Http\Request;

class GenerateUsersReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(
        Request               $request,
        ReportDownloadService $reportDownloadService,
        ReportConfig          $reportConfig
    )
    {
        $this->authorize('viewAny', User::class);

        // Configure the report

        $reportConfig->title = 'Listes des utilisateurs';
        $reportConfig->queryBuilder = User::orderBy('name', 'ASC');

        $reportConfig->headers = [
            'Utilisateur classé par' => 'Nom'
        ];

        $reportConfig->columns = [ // Set Column to be displayed
            'Nom et prénom' => function (User $user) {
                return $user->name . ' ' . $user->surname;
            },
            'Genre' => function (User $user) {
                return ($user->gender == 'male' ? 'Homme' :
                    ($user->gender == 'female' ? 'Femme' : 'Non spécifié'));
            },
            'Date de naissance' => function (User $user) {
                return $user->dateOfBirth?->format('d/m/Y');
            },
            'Résidence' => 'residence',
//            'Date d\'arrivée' => function (User $user) {
//                return $user->arrivalDate?->format('d/m/Y') ?? 'Non précisé';
//            },
            'Email' => 'email',
            'Occupation' => function (User $user) {
                return User::professions()->firstWhere('name', $user->profession)?->displayName ?: "Inconnue";
            },
            'Details' => 'profession_details',
            'Téléphone' => 'phoneNumber',
            'Role(s)' => function (User $result) { // You can do if statement or any action do you want inside this closure
                return $result->roles->reduce(fn($acc, $r) => $r->displayName . ' | ' . $acc, '');
            },
            'Assemblé' => function (User $user) {
                return $user->assembly?->name;
            },
//            'Antécédent' => 'antecedent',
//            'Commentaire' => 'comment',
//            'Est actif ?' => function (User $result) {
//                return $result->isActive ? 'Oui' : 'Non';
//            },
//            'Est discipliné ?' => function (User $result) {
//                return $result->isDisciplined ? 'Oui' : 'Non';
//            },
//            "Date d'arrivé" => function (User $result) {
//                return $result->arrivalDate?->format("d/m/Y");
//            },
            "Status marital" => function (User $result) {
                return match ($result->maritalStatus) {
                    'single' => 'Célibataire',
                    'married' => 'Marié(e)',
                    'divorced' => 'Divorcé(e)',
                    'widower' => 'Veuf(ve)',
                    'concubinage' => 'En concubinage',
                    default => 'Non précisé'
                };
            },
//            "Est stérile ?" => function (User $result) {
//                return $result->sterileWoman ? "Oui" : "Non";
//            },
            "Nombre d'enfants" => 'numberOfChildren',
//            "Maladie grave" => 'seriousIllnesses',
//            "Marqueur nominal" => function (User $result) {
//                return match ($result->baptisms->first()?->nominalMaker) {
//                    "conqueror" => "Conquérant",
//                    "messengers" => "Messager / Messagère",
//                    "warriors" => "Guerrier / Guerrière",
//                    "soldat" => "Soldat(e)",
//                    default => "Non spécifié"
//                };
//            },
            "Date baptême eau" => function (User $result) {
                return $result->baptisms->first()?->date_water?->format("d/m/Y");
            },
            "Date baptême Saint-Esprit" => function (User $result) {
                return $result->baptisms->first()?->date_holy_spirit?->format("d/m/Y");
            },
//            "Date dernier baptême" => function (User $result) {
//                return $result->baptisms->first()?->date_latest?->format("d/m/Y");
//            },
//            "A manifesté le don du Saint-Esprit ?" => function (User $result) {
//                return $result->baptisms->first()?->hasHolySpirit ? "Oui" : "Non";
//            }
        ];

        $reportConfig->paper = 'a3';
        $reportConfig->orientation = 'landscape';

        // Download the report
        return $reportDownloadService->downloadReportToPDF($reportConfig);
    }
}
