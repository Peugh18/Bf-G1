<?php
namespace App\Services\Reports;

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TabularReport
{
    public function pdf(string $title, array $headers, array $rows): string
    {
        $escape = static fn ($value) => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $html = '<html><head><meta charset="UTF-8"><style>body{font-family:DejaVu Sans;font-size:10px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:6px}</style></head><body><h1>BRUCE FIRE S.A.C.</h1><h2>'.$escape($title).'</h2><table><thead><tr>';
        foreach ($headers as $header) { $html .= '<th>'.$escape($header).'</th>'; }
        $html .= '</tr></thead><tbody>';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $value) { $html .= '<td>'.$escape($value).'</td>'; }
            $html .= '</tr>';
        }
        $html .= '</tbody></table><p>Total de registros: '.count($rows).'</p></body></html>';
        $pdf = new Dompdf();
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->render();
        return $pdf->output();
    }

    public function excel(array $headers, array $rows): string
    {
        $book = new Spreadsheet();
        try {
            $sheet = $book->getActiveSheet();
            // Explicit strings prevent formula injection in user-entered values.
            foreach (array_merge([$headers], $rows) as $index => $row) {
                foreach (array_values($row) as $column => $value) {
                    $sheet->setCellValueExplicit([$column + 1, $index + 1], (string) $value, DataType::TYPE_STRING);
                }
            }
            $sheet->freezePane('A2');
            $sheet->getStyle('1:1')->getFont()->setBold(true);
            $stream = fopen('php://memory', 'w+b');
            if ($stream === false) { throw new \RuntimeException('Cannot create export stream.'); }
            try {
                (new Xlsx($book))->save($stream);
                rewind($stream);
                $bytes = stream_get_contents($stream);
                if ($bytes === false) { throw new \RuntimeException('Cannot read export stream.'); }
                return $bytes;
            } finally { fclose($stream); }
        } finally { $book->disconnectWorksheets(); }
    }
}
