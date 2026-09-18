<?php
namespace App\Services\Reports;

class CustomerExportService extends ReportExport
{
    protected string $table = 'customers';
    protected string $title = 'Clientes';
    protected array $columns = ['id', 'name', 'document_number', 'email', 'phone'];
    protected array $headers = ['ID', 'Nombre', 'Documento', 'Correo', 'Telefono'];
    protected string $searchColumn = 'document_number';
}
