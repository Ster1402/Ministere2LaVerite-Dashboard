<?php

namespace App\Traits;

trait Reportable
{
    /**
     * Get the query builder for report generation.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery()
    {
        return static::query();
    }

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder()
    {
        return 'id';
    }

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle()
    {
        $modelName = class_basename(static::class);
        return "Rapport des " . \Illuminate\Support\Str::plural(strtolower($modelName));
    }
}
