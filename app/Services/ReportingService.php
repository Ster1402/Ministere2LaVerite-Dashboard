<?php

namespace App\Services;

use App\Interfaces\FilterableModel;
use App\Interfaces\ReportableModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Exceptions\ReportGenerationException;

class ReportingService
{
    /**
     * Generate a report.
     *
     * @param string $modelClass
     * @param array $columns
     * @param array $filters
     * @param string $format
     * @param string $paperSize
     * @param string $orientation
     * @return mixed
     */
    public function generateReport(
        $modelClass,
        $columns,
        $filters = [],
        $format = 'pdf',
        $paperSize = 'a4',
        $orientation = 'portrait'
    ) {
        if (!$this->isReportable($modelClass)) {
            throw new ReportGenerationException("The model {$modelClass} is not reportable");
        }

        // Get the report title
        $title = $modelClass::getReportTitle();

        // Get the query builder from the model
        $queryBuilder = $modelClass::getReportQuery();

        // Apply dynamic filters if model is filterable
        if ($this->isFilterable($modelClass) && !empty($filters)) {
            $queryBuilder = $modelClass::applyDynamicFilters($queryBuilder, $filters);
        }

        // Apply ordering
        $orderColumn = $modelClass::getReportDefaultOrder();
        $queryBuilder->orderBy($orderColumn);

        // Prepare column definitions
        $columnDefinitions = $this->prepareColumns($modelClass, $columns);

        // Metadata for the report header
        $meta = [
            'Nom de l\'application' => env("APP_NAME"),
            'Exporté par' => auth()->user()->name,
            'Généré le' => date('d M Y à H:i'),
            'Format' => strtoupper($paperSize) . ' ' . ucfirst($orientation),
        ];

        // Generate the report
        if ($format === 'pdf') {
            return \PdfReport::of(env("APP_NAME") . ' - ' . $title, $meta, $queryBuilder, $columnDefinitions)
                ->setPaper($paperSize)
                ->setOrientation($orientation)
                ->stream();
        } else {
            return \ExcelReport::of($title, $meta, $queryBuilder, $columnDefinitions)
                ->download($title);
        }
    }

    /**
     * Prepare column definitions for the report.
     *
     * @param string $modelClass
     * @param array $selectedColumns
     * @return array
     */
    private function prepareColumns($modelClass, $selectedColumns)
    {
        // Get all available columns for this model
        $availableColumns = $modelClass::getReportableColumns();

        $columns = [];

        // If '*' is selected, include all columns
        if (in_array('*', $selectedColumns)) {
            return $availableColumns;
        }

        foreach ($selectedColumns as $column) {
            if (isset($availableColumns[$column])) {
                $columns[$availableColumns[$column]['title']] = $availableColumns[$column]['data'];
            }
        }

        return $columns;
    }

    /**
     * Get all available columns for a model.
     *
     * @param string $modelClass
     * @return array
     */
    public function getAvailableColumns($modelClass)
    {
        if (!$this->isReportable($modelClass)) {
            return [];
        }

        return $modelClass::getReportableColumns();
    }

    /**
     * Get all filterable attributes for a model.
     *
     * @param string $modelClass
     * @return array
     */
    public function getFilterableAttributes($modelClass)
    {
        if (!$this->isFilterable($modelClass)) {
            return [];
        }

        return $modelClass::getFilterableAttributes();
    }

    /**
     * Get filter operators for a model.
     *
     * @param string $modelClass
     * @return array
     */
    public function getFilterOperators($modelClass)
    {
        if (!$this->isFilterable($modelClass)) {
            return [];
        }

        return $modelClass::getFilterOperators();
    }

    /**
     * Check if a model class implements the ReportableModel interface.
     *
     * @param string $modelClass
     * @return bool
     */
    public function isReportable($modelClass)
    {
        return in_array(
            \App\Interfaces\ReportableModel::class,
            class_implements($modelClass) ?: []
        );
    }

    /**
     * Check if a model class implements the FilterableModel interface.
     *
     * @param string $modelClass
     * @return bool
     */
    public function isFilterable($modelClass)
    {
        return in_array(
            \App\Interfaces\FilterableModel::class,
            class_implements($modelClass) ?: []
        );
    }

    /**
     * Get paper size options.
     *
     * @return array
     */
    public function getPaperSizeOptions()
    {
        return [
            'a4' => 'A4',
            'a3' => 'A3',
            'a2' => 'A2',
            'a1' => 'A1',
            'a0' => 'A0',
        ];
    }

    /**
     * Get orientation options.
     *
     * @return array
     */
    public function getOrientationOptions()
    {
        return [
            'portrait' => 'Portrait',
            'landscape' => 'Paysage',
        ];
    }

    /**
     * Get all reportable models.
     *
     * @return array
     */
    public function getReportableModels()
    {
        // This is a simplified approach. In a real application, you might want to use a
        // more sophisticated method to discover all reportable models.
        return [
            'users' => \App\Models\User::class,
            'admins' => \App\Models\Admin::class,
            'transactions' => \App\Models\Transaction::class,
            'donations' => \App\Models\Donation::class,
            'assemblies' => \App\Models\Assembly::class,
            'sectors' => \App\Models\Sector::class,
            'events' => \App\Models\Event::class,
            'messages' => \App\Models\Message::class,
            'resources' => \App\Models\Resource::class,
            'groups' => \App\Models\Group::class,
            'subsectors' => \App\Models\Subsector::class,
            // Add more models as needed
        ];
    }
}
