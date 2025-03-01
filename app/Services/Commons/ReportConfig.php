<?php

namespace App\Services\Commons;

use App\Services\Interfaces\DownloadableReport;

class ReportConfig  implements DownloadableReport
{
    public function __construct(
        public $queryBuilder = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?array $meta = null,
        public ?array $columns = null,
        public ?array $headers = null,
        public ?string $paper = 'a3',
        public ?string $orientation = 'landscape',
    )
    {
    }

    public function getName(): string
    {
        return env('APP_NAME') .' | '. $this->title;
    }

    public function getOrientation(): string
    {
        return $this->orientation;
    }

    public function getPaper(): string
    {
        return $this->paper;
    }

    public function getHeaders(): array
    {
        return [
            'Ce document à été généré le ' => now()->format('d/m/Y à H:i:s'),
            ...($this->headers)
        ];
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }
}
