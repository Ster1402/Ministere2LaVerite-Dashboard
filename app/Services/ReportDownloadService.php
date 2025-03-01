<?php


namespace App\Services;

use App\Models\Resource;
use App\Models\Transaction;
use App\Services\Interfaces\DownloadableReport;

class ReportDownloadService
{
    public string $totalColumn;
    public bool $hasTotalColumn;

    public function __construct()
    {
        $this->totalColumn = '';
        $this->hasTotalColumn = false;
    }

    public function downloadReportToPDF(DownloadableReport $report)
    {
        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        $title = $report->getName();
        $meta = $report->getHeaders();
        $queryBuilder = $report->getQueryBuilder();
        $columns = $report->getColumns();

        $pdf = \PdfReport::of($title, $meta, $queryBuilder, $columns)
            ->setPaper($report->getPaper())
            ->setOrientation($report->getOrientation());

        if ($this->hasTotalColumn) {
            $pdf->editColumn($this->totalColumn, [
                'displayAs' => fn($value) => match ($value::class) {
                    Transaction::class => number_format($value->amount, 2),
                    Resource::class => $value->quantity,
                    default => $value
                },
                'class' => 'right'
            ]);
            $pdf->showTotal([$this->totalColumn => 'Total']);
        }

        return $pdf->download(filename: $title);
    }
}
