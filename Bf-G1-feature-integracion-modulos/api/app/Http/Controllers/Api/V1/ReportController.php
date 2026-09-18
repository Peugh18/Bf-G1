<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Reports\CustomerExportService;
use App\Services\Reports\CatalogExportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private CustomerExportService $customers, private CatalogExportService $catalog) {}

    private function download(string $bytes, string $filename, string $type)
    {
        return response($bytes, 200, ['Content-Type' => $type, 'Content-Disposition' => 'attachment; filename="'.$filename.'"', 'Cache-Control' => 'private, no-store']);
    }

    public function exportCustomersPdf(Request $request)
    {
        return $this->download($this->customers->exportPdf($request->validate(['q' => 'sometimes|nullable|string|max:100'])), 'clientes.pdf', 'application/pdf');
    }

    public function exportCatalogPdf(Request $request)
    {
        return $this->download($this->catalog->exportPdf($request->validate(['q' => 'sometimes|nullable|string|max:100'])), 'catalogo.pdf', 'application/pdf');
    }

    public function exportCustomersExcel(Request $request)
    {
        return $this->download($this->customers->exportExcel($request->validate(['q' => 'sometimes|nullable|string|max:100'])), 'clientes.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function exportCatalogExcel(Request $request)
    {
        return $this->download($this->catalog->exportExcel($request->validate(['q' => 'sometimes|nullable|string|max:100'])), 'catalogo.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
