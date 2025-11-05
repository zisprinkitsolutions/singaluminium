<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceImport implements FromArray, WithHeadings
{
    /**
    * @param Collection $collection
    */
    protected $masterAccounts;

    public function __construct($masterAccounts)
    {
        $this->masterAccounts = $masterAccounts;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->masterAccounts as $key => $master_account) {
            $drAmount = array_sum(array_column($master_account['account_heads'], 'total_dr_amount'));
            $crAmount = array_sum(array_column($master_account['account_heads'], 'total_cr_amount'));
            $rows[] = [
                $master_account['name'],
                number_format($drAmount, 2, '.', ''),
                number_format($crAmount, 2, '.', ''),
                $crAmount > $drAmount ? number_format($crAmount - $drAmount, 2, '.', '') : 0,
                $drAmount > $crAmount ? number_format($drAmount - $crAmount, 2, '.', '') : 0,
            ];

            foreach ($master_account['account_heads'] as $accountHead) {
                $balance = $accountHead->total_dr_amount - $accountHead->total_cr_amount;
                $rows[] = [
                    $accountHead->fld_ac_head,
                    number_format($accountHead->total_dr_amount, 2, '.', ''),
                    number_format($accountHead->total_cr_amount, 2, '.', ''),
                    $balance > 0 ? number_format($balance, 2, '.', '') : '0.00',
                    $balance < 0 ? number_format(-$balance, 2, '.', '') : '0.00',
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Account Head',
            'Debit',
            'Credit',
            'Balance Debit',
            'Balance Credit',
        ];
    }
    public function styles(Worksheet $sheet)
    {
        // Make the headings bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        // Apply bold styling to master account names
        foreach ($this->masterAccounts as $key => $master_account) {
            $rowNumber = $key + 2; // Adjust row number (headings occupy row 1)
            $sheet->getStyle("A$rowNumber")->getFont()->setBold(true);

            // Optionally, apply color
            $sheet->getStyle("A$rowNumber")->getFont()->getColor()->setRGB('FF0000');
        }

        return [
            // Auto-size and apply borders (optional)
            'A1:E1' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
