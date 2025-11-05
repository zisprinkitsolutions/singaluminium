<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaleReportExcel implements FromArray, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $records;

    public function __construct($records){
        $this->records = $records;
    }
    public function headings():array{
        return [
            'Month',
            'Total Amount',
            'Paid Amount',
            'Due Amount',
        ];
    }

    public function array():array{
        $rows = [];
        $display_year = [];

        foreach($this->records as $record){
            if(!isset($display_year[$record->year])){
                $rows[] = [
                    'month' => ' ',
                    'total_amount' => 'Year',
                    'paid_amount' => $record->year,
                    'due_amount' => ' ',
                ];
            }

            $rows[] = [
                'month' => Carbon::createFromFormat('m',$record->month)->format('F'),
                'total_amount' => $record->total_amount,
                'paid_amount' => $record->paid_amount,
                'due_amount' => $record->due_amount,
            ];

            $row[] = [
                'month' => '',
                'total_amount' => '',
                'paid_amount' =>'',
                'due_amount' => ' ',
            ];

            $display_year[$record->year] = 1;
        }

        return $rows;
    }
}
