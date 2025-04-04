<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogController extends Controller
{
    /**
     * Afficher la page du journal des logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Construire la requête de base
        $query = Log::query();

        // Filtrer par niveau
        if ($request->has('level') && !empty($request->level)) {
            $query->where('level', $request->level);
        }

        // Filtrer par recherche
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                    ->orWhere('context', 'like', "%{$search}%");
            });
        }

        // Filtrer par période
        if ($request->has('date_range') && !empty($request->date_range)) {
            try {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) === 2) {
                    $startDate = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            } catch (\Exception $e) {
                // Ignorer les erreurs de format de date
            }
        }

        // Filtrer par canal
        if ($request->has('channel') && !empty($request->channel)) {
            $query->where('channel', $request->channel);
        }

        // Filtrer par contexte
        if ($request->has('context') && !empty($request->context)) {
            $query->where('context', 'like', "%{$request->context}%");
        }

        // Récupérer les logs avec pagination
        $logs = $query->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Récupérer les canaux disponibles
        $channels = Log::distinct()->pluck('channel')->filter()->values();

        // Récupérer les contextes disponibles (par exemple, les types d'entités concernées)
        $contexts = DB::table('logs')
            ->selectRaw('DISTINCT JSON_EXTRACT(context, "$.entity_type") as entity_type')
            ->whereRaw('JSON_EXTRACT(context, "$.entity_type") IS NOT NULL')
            ->pluck('entity_type')
            ->map(function ($item) {
                return trim($item, '"');
            })
            ->filter()
            ->values();

        // Compter les logs par niveau
        $logCounts = [
            'info' => Log::where('level', 'info')->count(),
            'warning' => Log::where('level', 'warning')->count(),
            'error' => DB::table('logs')->whereIn('level', ['error', 'critical', 'alert', 'emergency'])->count(),
            'debug' => Log::where('level', 'debug')->count(),
        ];

        return view('journal', compact('logs', 'channels', 'contexts', 'logCounts'));
    }

    /**
     * Obtenir les détails d'un log spécifique.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $log = Log::with('user')->findOrFail($id);

        return response()->json([
            'id' => $log->id,
            'level' => $log->level,
            'message' => $log->message,
            'context' => $log->context,
            'channel' => $log->channel,
            'user_id' => $log->user_id,
            'user_name' => $log->user ? $log->user->name : null,
            'ip_address' => $log->ip_address,
            'uri' => $log->uri,
            'formatted_created_at' => $log->created_at->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * Obtenir les compteurs de logs pour l'API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCounts()
    {
        $counts = [
            'info' => Log::where('level', 'info')->count(),
            'warning' => Log::where('level', 'warning')->count(),
            'error' => DB::table('logs')->whereIn('level', ['error', 'critical', 'alert', 'emergency'])->count(),
            'debug' => Log::where('level', 'debug')->count(),
        ];

        return response()->json($counts);
    }

    /**
     * Exporter les logs selon les filtres.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        // Identique à la méthode index mais sans pagination
        $query = Log::query();

        // Appliquer les mêmes filtres que dans la méthode index
        // ...

        // Récupérer tous les logs filtrés
        $logs = $query->with('user')->orderBy('created_at', 'desc')->get();

        // Déterminer le format d'export
        $format = $request->format ?? 'csv';

        // Préparation des données pour l'export
        $exportData = $logs->map(function ($log) {
            return [
                'ID' => $log->id,
                'Niveau' => ucfirst($log->level),
                'Message' => $log->message,
                'Canal' => $log->channel,
                'Utilisateur' => $log->user ? $log->user->name : 'Système',
                'Adresse IP' => $log->ip_address,
                'URI' => $log->uri,
                'Date' => $log->created_at->format('d/m/Y H:i:s'),
            ];
        });

        // Créer l'export selon le format
        switch ($format) {
            case 'excel':
                return Excel::download(new LogsExport($exportData), 'logs.xlsx');
            case 'pdf':
                return PDF::loadView('exports.logs', ['logs' => $exportData])->download('logs.pdf');
            case 'csv':
            default:
                return (new LogsExport($exportData))->download('logs.csv', \Maatwebsite\Excel\Excel::CSV);
        }
    }
}
