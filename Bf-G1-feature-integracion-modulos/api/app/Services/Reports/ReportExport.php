<?php
namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

abstract class ReportExport
{
    protected string $table;
    protected string $title;
    protected string $searchColumn;
    protected array $columns;
    protected array $headers;

    public function __construct(private TabularReport $report) {}

    private function rows(array $filters): array
    {
        $query = DB::table($this->table)->select($this->columns)->orderBy('id');
        if (isset($filters['q']) && $filters['q'] !== '') {
            $query->where(function ($query) use ($filters) {
                $query->where('name', 'like', '%'.$filters['q'].'%')->orWhere($this->searchColumn, 'like', '%'.$filters['q'].'%');
            });
        }
        return $query->get()->map(fn ($row) => array_map(fn ($column) => $row->$column, $this->columns))->all();
    }

    public function exportPdf(array $filters = []): string
    {
        return $this->report->pdf($this->title, $this->headers, $this->rows($filters));
    }

    public function exportExcel(array $filters = []): string
    {
        return $this->report->excel($this->headers, $this->rows($filters));
    }
}
