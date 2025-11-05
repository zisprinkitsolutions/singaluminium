<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SaleReportExtendedExcel implements FromArray, WithHeadings, WithEvents
{
    public $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function headings(): array
    {
        return [
            // Dynamic header placeholders, including the year
            ['Year Placeholder'], // This will be dynamically set in AfterSheet
            ['Month', 'Total', 'Paid', 'Due', 'Invoice No', 'Party name'],
        ];
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->invoices as $invoice) {
            // Add each month's data
            $rows[] = [
                $invoice['month'],
                $invoice['total_amount'],
                $invoice['paid_amount'],
                $invoice['due_amount'],
                '', // Extra column 1
                '', // Extra column 2
            ];

            // Add invoice items
            foreach ($invoice['items'] as $item) {
                $rows[] = [
                    date('d/m/Y',strtotime($item->date)),
                    $item->total_amount,
                    $item->paid_amount,
                    $item->due_amount,
                    $item->invoice_no,
                    $item->pi_name,
                ];
            }

            // Blank row for spacing
            $rows[] = ['', '', '', '', '', ''];
        }

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $currentRow = 1; // First row
                $columnCount = 6; // Number of columns in the table
                $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
                $sheet->setCellValue("A{$currentRow}", 'Year: 2025');
                $sheet->getStyle("A{$currentRow}:F{$currentRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ]);
            },
        ];
    }
}
