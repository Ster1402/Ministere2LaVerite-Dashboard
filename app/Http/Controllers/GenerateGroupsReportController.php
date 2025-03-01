<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GenerateGroupsReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws AuthorizationException
     */
    public function __invoke(Request $request,
                             ReportDownloadService $reportDownloadService,
                             ReportConfig $groupReport)
    {
        $this->authorize('viewAny', Group::class);

        $groupReport->title = 'Listes des groupes';
        $groupReport->description = 'Listes des groupes enregistrés dans le système';
        $groupReport->queryBuilder = Group::orderBy('name', 'ASC');

        $groupReport->headers = [
            'Classer par' => 'Nom'
        ];

        $groupReport->columns = [ // Set Column to be displayed
            'Nom du groupe' => 'name',
            'Description' => 'description',
        ];

        // Download the report
        return $reportDownloadService->downloadReportToPDF($groupReport);
    }
}
