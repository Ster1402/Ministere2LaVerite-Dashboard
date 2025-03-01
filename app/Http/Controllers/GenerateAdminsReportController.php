<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Services\Commons\ReportConfig;
use App\Services\ReportDownloadService;
use Illuminate\Http\Request;

class GenerateAdminsReportController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        ReportDownloadService $reportDownloadService,
        ReportConfig $usersReport
    )
    {
        $this->authorize('admin.viewAny');

        // Configure the report

        $usersReport->title = 'Listes des administrateurs';
        $usersReport->description = 'Listes des administrateurs présent dans la plateforme';
        $usersReport->queryBuilder = User::whereHas('roles', fn($q) => $q->whereIn('roles.name', Roles::availableAdminsRoles()))
            ->orderBy('name', 'ASC');

        $usersReport->headers = [
            'Classer par' => 'Nom'
        ];

        $usersReport->columns = [ // Set Column to be displayed
            'Nom et prénoms' => function (User $user) {
                return $user->name . ' ' . $user->surname;
            },
            'Email' => 'email',
            'Numéro de téléphone' => 'phoneNumber',
            'Role(s)' => function (User $result) { // You can do if statement or any action do you want inside this closure
                return $result->roles->reduce(fn($acc, $r) => $r->displayName . ' | ' . $acc, '');
            },
        ];

        // Download the report
        return $reportDownloadService->downloadReportToPDF($usersReport);
    }
}
