<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExtendedGenerelLedger implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $records;
    protected $head;
    protected $year;

    public function __construct($records, $year, $head)
    {
        $this->records = $records;
        $this->head = $head;
        $this->year = $year;
    }

    public function array(): array
    {
        $data = [];
        foreach($this->records as $month_year => $month){
            $data[] = [
                $month_year,
                " ",
                " ",
                $month['month_total_dr'] ?? 0.00,
                $month['month_total_cr'] ?? 0.00,
                abs($month['month_total_dr'] - $month['month_total_cr']),
            ];


            foreach ($month['items'] as $item) {
                $data[] = [
                    $item['date'],
                    $item['narration'],
                    $item['reference'],
                    $item['dr_amount'],
                    $item['cr_amount'],
                    abs($item['dr_amount'] - $item['cr_amount']),
                ];
            }

            $data[] = ['', '', '', '','',''];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Narration',
            'Ref',
            'Debit',
            'Credit',
            'Balance',
        ];
    }
}
