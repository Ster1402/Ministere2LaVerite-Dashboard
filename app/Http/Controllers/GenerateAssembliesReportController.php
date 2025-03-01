<?php

namespace App\Http\Controllers;

use App\Models\Assembly;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GenerateAssembliesReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws AuthorizationException
     */
    public function __invoke(
        Request               $request,
        ReportDownloadService $reportDownloadService,
        ReportConfig          $assemblyReport
    )
    {
        $this->authorize('viewAny', Assembly::class);

        $assemblyReport->title = 'Listes des assemblées';
        $assemblyReport->description = 'Listes des assemblées enregistrées dans le système';
        $assemblyReport->queryBuilder = Assembly::orderBy('name', 'ASC');

        $assemblyReport->headers = [
            'Classer par' => 'Nom'
        ];

        $assemblyReport->columns = [ // Set Column to be displayed
            'Nom de l\'assemblée' => 'name',
            'Secteur' => function (Assembly $assembly) {
                return $assembly->sector->name;
            },
            'Description' => 'description',
        ];

        // Download the report
        return $reportDownloadService->downloadReportToPDF($assemblyReport);
    }
}
