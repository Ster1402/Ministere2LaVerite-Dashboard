<?php

namespace App\View\Components;

use App\Services\ReportingService;
use Illuminate\View\Component;

class FilterManager extends Component
{
    public $modelName;
    public $filters;
    public $attributes;
    public $operators;

    /**
     * Create a new component instance.
     *
     * @param string $modelName
     * @param array $filters
     * @return void
     */
    public function __construct($modelName, $filters = [])
    {
        $this->modelName = $modelName;
        $this->filters = $filters;

        // Load attributes and operators for the model
        $this->loadModelData();
    }

    /**
     * Load attributes and operators for the model
     */
    protected function loadModelData()
    {
        $reportingService = app(ReportingService::class);
        $modelClass = $this->getModelClass($this->modelName);

        if ($modelClass && class_exists($modelClass) && $reportingService->isFilterable($modelClass)) {
            $this->attributes = $reportingService->getFilterableAttributes($modelClass);
            $this->operators = $reportingService->getFilterOperators($modelClass);
        } else {
            $this->attributes = [];
            $this->operators = [];
        }
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

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.filter-manager');
    }
}
