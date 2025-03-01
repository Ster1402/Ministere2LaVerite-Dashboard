<?php

namespace App\Services\Interfaces;

interface DownloadableReport
{
    public function getName(): string;
    public function getColumns(): array; // Set Column to be displayed
    public function getHeaders(): array;
    public function getQueryBuilder();
    public function getPaper(): string;
    public function getOrientation(): string;
}
