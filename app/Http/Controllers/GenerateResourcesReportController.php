<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GenerateResourcesReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws AuthorizationException
     */
    public function __invoke(Request $request,
                             ReportDownloadService $reportDownloadService,
                             ReportConfig $resourceReport)
    {
        $this->authorize('viewAny', Resource::class);

        $resourceReport->title = 'Listes des ressources';
        $resourceReport->description = 'Listes des ressources enregistrées dans le système';
        $resourceReport->queryBuilder = Resource::orderBy('name', 'ASC');

        $resourceReport->headers = [
            'Classer par' => 'Nom'
        ];

        $resourceReport->columns = [ // Set Column to be displayed
            'Nom de la ressource' => 'name',
            'Description' => 'description',
            'Groupe' => fn (Resource $resource) => $resource->group->name,
            'Quantité' => 'quantity',
        ];

        $reportDownloadService->totalColumn = 'Quantité';
        $reportDownloadService->hasTotalColumn = true;

        // Download the report
        return $reportDownloadService->downloadReportToPDF($resourceReport);
    }
}
