<?php

namespace App\View\Components;

use App\Services\ReportingService;
use Illuminate\View\Component;

class DynamicFilter extends Component
{
    public $modelName;
    public $attributes;
    public $operators;
    public $filters;
    public $index;

    /**
     * Create a new component instance.
     *
     * @param string $modelName
     * @param array $attributes
     * @param array $operators
     * @param array $filters
     * @param int $index
     * @return void
     */
    public function __construct($modelName, $attributes = [], $operators = [], $filters = [], $index = 0)
    {
        $this->modelName = $modelName;
        $this->attributes = $attributes;
        $this->operators = $operators;
        $this->filters = $filters;
        $this->index = $index;

        // If attributes and operators are not provided, try to load them
        if (empty($this->attributes) || empty($this->operators)) {
            $this->loadAttributesAndOperators();
        }
    }

    /**
     * Load attributes and operators from the model
     */
    protected function loadAttributesAndOperators()
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
        return view('components.dynamic-filter');
    }
}
