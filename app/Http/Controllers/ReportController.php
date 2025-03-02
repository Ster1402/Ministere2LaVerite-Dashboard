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

        return $this->reportingService->generateReport(
            $modelClass,
            $request->columns,
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
