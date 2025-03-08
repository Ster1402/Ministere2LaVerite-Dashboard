<?php

namespace App\Interfaces;

interface ReportableModel
{
    /**
     * Get the query builder for report generation.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getReportQuery();

    /**
     * Get reportable columns for this model.
     *
     * @return array
     */
    public static function getReportableColumns();

    /**
     * Get the default ordering for reports.
     *
     * @return string
     */
    public static function getReportDefaultOrder();

    /**
     * Get the report title.
     *
     * @return string
     */
    public static function getReportTitle();
}
