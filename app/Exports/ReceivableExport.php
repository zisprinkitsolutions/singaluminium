<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReceivableExport implements FromArray, WithHeadings
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headings(): array{
        return ['Party Name', 'Amount'];
    }

    public function array():array{
        $rows = [];
        foreach($this->data['three_month'] as $data){
            $rows[] = [
                'party' => $data->pi_name,
                'amount' => $data->due_amount,
            ];
        }
        $rows[] = ['party' => ' ', 'amount' => ' '];
        foreach($this->data['six_month'] as $data){
            $rows[] = [
                'party' => $data->pi_name,
                'amount' => $data->due_amount,
            ];
        }

        $rows[] = ['party' => ' ', 'amount' => ' '];
        foreach($this->data['twelve_month'] as $data){
            $rows[] = [
                'party' => $data->pi_name,
                'amount' => $data->due_amount,
            ];
        }

        $rows[] = ['party' => ' ', 'amount' => ' '];
        foreach($this->data['old'] as $data){
            $rows[] = [
                'party' => $data->pi_name,
                'amount' => $data->due_amount,
            ];
        }

        $rows[] = ['party' => ' ', 'amount' => ' '];

        return $rows;
    }
}
