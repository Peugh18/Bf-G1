<?php

namespace App\Services\Reports;

class CatalogExportService
{
    public function exportPdf(array $filters = []): array
    {
        return [
            'type' => 'pdf',
            'module' => 'catalog',
            'filters' => $filters,
            'generated_at' => now()->toISOString(),
            'records' => 0,
        ];
    }

    public function exportExcel(array $filters = []): array
    {
        return [
            'type' => 'excel',
            'module' => 'catalog',
            'filters' => $filters,
            'generated_at' => now()->toISOString(),
            'records' => 0,
        ];
    }
}
