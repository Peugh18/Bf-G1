<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Reports\CustomerExportService;
use App\Services\Reports\CatalogExportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected CustomerExportService $customerExportService;
    protected CatalogExportService $catalogExportService;

    public function __construct(
        CustomerExportService $customerExportService,
        CatalogExportService $catalogExportService
    ) {
        $this->customerExportService = $customerExportService;
        $this->catalogExportService = $catalogExportService;
    }

    public function exportCustomersPdf(Request $request)
    {
        $filters = $request->all();
        $result = $this->customerExportService->exportPdf($filters);

        return response()->json([
            'message' => 'Exportación de Clientes a PDF generada exitosamente',
            'data' => $result
        ]);
    }

    public function exportCatalogPdf(Request $request)
    {
        $filters = $request->all();
        $result = $this->catalogExportService->exportPdf($filters);

        return response()->json([
            'message' => 'Exportación de Catálogo a PDF generada exitosamente',
            'data' => $result
        ]);
    }
}