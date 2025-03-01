<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GenerateSectorsReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws AuthorizationException
     */
    public function __invoke(Request $request,
                             ReportDownloadService $reportDownloadService,
                             ReportConfig $sectorReport)
    {
        $this->authorize('viewAny', Sector::class);

        $sectorReport->title = 'Listes des secteurs';
        $sectorReport->description = 'Listes des secteurs enregistrés dans le système';
        $sectorReport->queryBuilder = Sector::orderBy('name', 'ASC');

        $sectorReport->headers = [
            'Classer par' => 'Nom'
        ];

        $sectorReport->columns = [ // Set Column to be displayed
            'Nom du secteur' => 'name',
            'Secteur maître' => function ($sector) {
                return $sector->master ? $sector->master->name : 'N/A';
            },
            'Description' => 'description',
        ];

        // Download the report
        return $reportDownloadService->downloadReportToPDF($sectorReport);
    }
}
