<?php
require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../app/Services/Reports/TabularReport.php';

use App\Services\Reports\TabularReport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

$report = new TabularReport();
$pdf = $report->pdf('Clientes', ['Nombre', 'Documento'], [['Cliente <script>', '00123456']]);
if (!str_starts_with($pdf, '%PDF-')) { throw new RuntimeException('PDF signature missing'); }
$file = tempnam(sys_get_temp_dir(), 'bf-report-');
try {
    file_put_contents($file, $report->excel(['Nombre', 'Documento'], [['=1+1', '00123456']]));
    $book = IOFactory::load($file);
    $sheet = $book->getActiveSheet();
    if ($sheet->getCell('A2')->getDataType() !== DataType::TYPE_STRING || $sheet->getCell('A2')->getValue() !== '=1+1') { throw new RuntimeException('Unsafe formula handling'); }
    if ($sheet->getCell('B2')->getValue() !== '00123456') { throw new RuntimeException('Document leading zeros lost'); }
    $book->disconnectWorksheets();
    if (!str_starts_with($report->pdf('Vacio', ['Nombre'], []), '%PDF-')) { throw new RuntimeException('Empty PDF failed'); }
    echo "PASS: PDF, empty dataset, XLSX roundtrip, leading zeros, formula safety\n";
} finally { unlink($file); }
