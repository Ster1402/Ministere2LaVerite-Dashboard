<?php

namespace App\View\Components;

use App\Services\ReportingService;
use Illuminate\View\Component;

class ReportModal extends Component
{
    public $modelName;
    public $title;
    public $paperSizes;
    public $orientations;
    public $columns;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($modelName, $title = null)
    {
        $this->modelName = $modelName;
        $this->title = $title ?: 'Exporter les données';

        $reportingService = app(ReportingService::class);
        $this->paperSizes = $reportingService->getPaperSizeOptions();
        $this->orientations = $reportingService->getOrientationOptions();

        // Pre-load the columns for this model
        $modelClass = $this->getModelClass($modelName);
        if ($modelClass && class_exists($modelClass) && $reportingService->isReportable($modelClass)) {
            $this->columns = $reportingService->getAvailableColumns($modelClass);
        } else {
            $this->columns = [];
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.report-modal');
    }

    /**
     * Get the model class from the model name.
     *
     * @param string $modelName
     * @return string|null
     */
    private function getModelClass($modelName)
    {
        $reportingService = app(ReportingService::class);
        $reportableModels = $reportingService->getReportableModels();
        return $reportableModels[$modelName] ?? null;
    }
}
