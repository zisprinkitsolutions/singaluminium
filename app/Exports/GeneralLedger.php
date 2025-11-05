<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GeneralLedger implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function array(): array
    {
        $data = [];

        $total_dr_amount = 0;
        $total_cr_amount = 0;

        foreach ($this->records as $record) {
            $data[] = [
                $record->fld_ac_head,
                $record->dr_amount,
                $record->cr_amount,
            ];

            $total_dr_amount +=  $record->dr_amount;
            $total_cr_amount +=   $record->cr_amount;
        }
        $balance = abs($total_cr_amount - $total_dr_amount);
        $data[] = [
            'Balance C/D',
            $total_cr_amount > $total_dr_amount ? $balance : $total_dr_amount,
            $total_dr_amount > $total_cr_amount ? $balance : $total_cr_amount,
        ];

        $data[] = [
            'Total',
            $total_dr_amount,
            $total_cr_amount,
        ];


        return $data;
    }

    public function headings(): array
    {
        return [
            'Account Head',
            'Debit',
            'Credit',
        ];
    }
}
