<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Http\Request;

class GenerateEventsReportController extends Controller
{
    /**
     * Handle the incoming request.
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(
        Request $request,
        ReportDownloadService $reportDownloadService,
        ReportConfig $reportConfig
    )
    {
        $this->authorize('viewAny', Event::class);

        // Configure the report

        $reportConfig->title = 'Listes des évènements';
        $reportConfig->queryBuilder = Event::orderBy('title');

        $reportConfig->headers = [
            'Évènements classé par' => 'Titre'
        ];

        $reportConfig->columns = [ // Set Column to be displayed
            'Titre' => 'title',
            'Date de début' => function (Event $event) {
                return $event->from?->format('d-m-Y') ?? 'Non spécifié';
            },
            'Date de fin' => function (Event $event) {
                return $event->to?->format('d-m-Y') ?? 'Non spécifié';
            },
            'Description',
            'Assemblées concernées' => function (Event $event) {
                return $event->assemblies->reduce(fn($acc, $r) => $r->name . ' | ' . $acc, '');
            },
        ];

        // Download the report
        return $reportDownloadService->downloadReportToPDF($reportConfig);
    }
}
