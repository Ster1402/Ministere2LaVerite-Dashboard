<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GenerateDonationsReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws AuthorizationException
     */
    public function __invoke(
        Request               $request,
        ReportDownloadService $reportDownloadService,
        ReportConfig          $donationsReport
    )
    {
        $this->authorize('viewAny', Transaction::class);

        // Configure the report
        $donationsReport->title = 'Listes des dons';
        $donationsReport->description = 'Listes des dons effectués par les utilisateurs';
        $donationsReport->queryBuilder = Transaction::with('user')
            ->orderBy('amount', 'DESC');

        $donationsReport->headers = [
            'Classer par' => 'Montant'
        ];

        $donationsReport->columns = [ // Set Column to be displayed
            'Nom et prénoms du donateur' => function (Transaction $t) {
                return $t->user->name . ' ' . $t->user?->surname;
            },
            'Email' => function (Transaction $t) {
                return $t->user->email;
            },
            'Numéro de téléphone' => function (Transaction $t) {
                return $t->user->phoneNumber;
            },
            'Assemblée' => function (Transaction $t) {
                return $t->user->assembly?->name;
            },
            'Déposé le' => 'created_at',
            'Montant déposé' => 'amount',
        ];

        $reportDownloadService->totalColumn = 'Montant déposé';
        $reportDownloadService->hasTotalColumn = true;

        // Download the report
        return $reportDownloadService->downloadReportToPDF($donationsReport);
    }
}
