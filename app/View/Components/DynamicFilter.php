<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DynamicFilter extends Component
{
    public $modelName;
    public $attributes;
    public $operators;
    public $filters;
    public $index;
    public $selectedField;
    public $selectedOperator;
    public $filterValue;

    /**
     * Create a new component instance.
     *
     * @param string $modelName
     * @param array $attributes
     * @param array $operators
     * @param array $filters
     * @param int|string $index
     * @return void
     */
    public function __construct($modelName, $attributes = [], $operators = [], $filters = [], $index = 0)
    {
        $this->modelName = $modelName;
        $this->attributes = $attributes;
        $this->operators = $operators;
        $this->filters = $filters;
        $this->index = $index;

        // Extract values from filters if they exist
        if (isset($filters[$index])) {
            $this->selectedField = $filters[$index]['field'] ?? null;
            $this->selectedOperator = $filters[$index]['operator'] ?? null;
            $this->filterValue = $filters[$index]['value'] ?? null;
        } else {
            $this->selectedField = null;
            $this->selectedOperator = null;
            $this->filterValue = null;
        }
    }

    /**
     * Get operators for a specific field type.
     *
     * @param string $type
     * @return array
     */
    public function getOperatorsForType($type)
    {
        if (empty($this->operators) || !isset($this->operators[$type])) {
            return [];
        }

        return $this->operators[$type];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dynamic-filter', [
            'modelName' => $this->modelName,
            'attributes' => $this->attributes,
            'operators' => $this->operators,
            'index' => $this->index,
            'selectedField' => $this->selectedField,
            'selectedOperator' => $this->selectedOperator,
            'filterValue' => $this->filterValue
        ]);
    }
}
