<?php

namespace App\Services\Reports;

class CustomerExportService
{
    public function exportPdf(array $filters = []): array
    {
        return [
            'type' => 'pdf',
            'module' => 'customers',
            'filters' => $filters,
            'generated_at' => now()->toISOString(),
            'records' => 0,
        ];
    }

    public function exportExcel(array $filters = []): array
    {
        return [
            'type' => 'excel',
            'module' => 'customers',
            'filters' => $filters,
            'generated_at' => now()->toISOString(),
            'records' => 0,
        ];
    }
}
