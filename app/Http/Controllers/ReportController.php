<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ReportingService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
        $this->middleware('auth');
    }

    /**
     * Generate a report.
     *
     * @param Request $request
     * @return mixed
     */
    public function generate(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'columns' => 'required|array',
            'columns.*' => 'string',
            'format' => 'required|in:pdf,excel',
            'paper_size' => 'required|string',
            'orientation' => 'required|in:portrait,landscape',
            'filters' => 'nullable|array',
        ]);

        $modelClass = $this->getModelClass($request->model);

        if (!$modelClass || !class_exists($modelClass)) {
            return back()->with('error', 'Model not found.');
        }

        if (!$this->reportingService->isReportable($modelClass)) {
            return back()->with('error', 'This model is not reportable.');
        }

        // Check if the user has permission to view this model's data
        if (!$this->canUserViewModel($request->model)) {
            return back()->with('error', 'You do not have permission to generate this report.');
        }

        // Process filters if present
        $filters = $this->processFilters($request->filters ?? []);

        return $this->reportingService->generateReport(
            $modelClass,
            $request->columns,
            $filters,
            $request->format,
            $request->paper_size,
            $request->orientation
        );
    }

    /**
     * Get the model data for the report modal.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModelData(Request $request)
    {
        $modelName = $request->model;
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass || !class_exists($modelClass)) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        if (!$this->reportingService->isReportable($modelClass)) {
            return response()->json(['error' => 'This model is not reportable'], 400);
        }

        $columns = $this->reportingService->getAvailableColumns($modelClass);

        return response()->json([
            'columns' => $columns,
            'paperSizes' => $this->reportingService->getPaperSizeOptions(),
            'orientations' => $this->reportingService->getOrientationOptions(),
        ]);
    }

    /**
     * Get filter options for a model.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function getModelFilters(Request $request)
    {
        $modelName = $request->model;
        $modelClass = $this->getModelClass($modelName);

        if (!$modelClass || !class_exists($modelClass)) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        // Check if the model is filterable
        if (!$this->reportingService->isFilterable($modelClass)) {
            return view('components.report-filters-not-available', [
                'message' => 'Ce modèle ne prend pas en charge le filtrage dynamique.'
            ]);
        }

        $attributes = $this->reportingService->getFilterableAttributes($modelClass);
        $operators = $this->reportingService->getFilterOperators($modelClass);

        // If there are existing filters, pass them to the view
        $filters = $request->session()->get('report_filters_' . $modelName, []);

        return view('components.dynamic-filters-container', [
            'modelName' => $modelName,
            'attributes' => $attributes,
            'operators' => $operators,
            'filters' => $filters
        ]);
    }

    /**
     * Process filters array to ensure they are valid and complete.
     *
     * @param array $filters
     * @return array
     */
    private function processFilters($filters)
    {
        $validFilters = [];

        foreach ($filters as $filter) {
            // Skip incomplete filters
            if (empty($filter['field']) || empty($filter['operator'])) {
                continue;
            }

            // Special handling for null operators
            if (in_array($filter['operator'], ['is_null', 'is_not_null'])) {
                $filter['value'] = null;
            } else if (empty($filter['value']) && $filter['value'] !== '0') {
                // Skip filters with empty values (except for explicit "0")
                continue;
            }

            $validFilters[] = $filter;
        }

        return $validFilters;
    }

    /**
     * Check if the user can view the model data.
     *
     * @param string $modelName
     * @return bool
     */
    private function canUserViewModel($modelName)
    {
        // Allow admins to view all models
        if (auth()->user()->isAdmin()) {
            return true;
        }

        // Add more specific rules for other roles if needed

        return false;
    }

    /**
     * Get the fully qualified model class name.
     *
     * @param string $modelName
     * @return string|null
     */
    private function getModelClass($modelName)
    {
        $reportableModels = $this->reportingService->getReportableModels();
        return $reportableModels[$modelName] ?? null;
    }
}
