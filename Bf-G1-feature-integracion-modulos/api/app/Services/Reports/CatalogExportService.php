<?php
namespace App\Services\Reports;

class CatalogExportService extends ReportExport
{
    protected string $table = 'products';
    protected string $title = 'Catalogo';
    protected array $columns = ['id', 'sku', 'name', 'price', 'stock'];
    protected array $headers = ['ID', 'Codigo', 'Producto', 'Precio', 'Stock'];
    protected string $searchColumn = 'sku';
}
