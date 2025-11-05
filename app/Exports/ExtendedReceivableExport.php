<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExtendedReceivableExport implements FromArray, WithHeadings
{
    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function headings():array{
        return [
            'Date',
            'Aging Period',
            $this->data['column'],
            'Total',
            'Paid',
            'Due',
        ];
    }

    public function array():array{
        $rows = [];

        foreach($this->data['three_month'] as $data){
            $rows[] = [
                'Date' => $data['pi_name'],
                'Aging Period' => '',
                $this->data['column'] => '',
                'Total' => ' ',
                'Paid' => ' ',
                'Due' => $data['due_amount'],
            ];

            foreach($data['items'] as $item){
                $givenDate = \Carbon\Carbon::parse($item->date);
                $diffForHumans = $givenDate->diffForHumans();
                $rows[] = [
                    'Date' => date('d/m/Y', strtotime($item->date)),
                    'Aging Period' => $diffForHumans,
                    $this->data['column'] => $item->invoice_no,
                    'Total' => $item->total_budget,
                    'Paid' => $item->paid_amount,
                    'Due' =>  $item->paid_amount,
                ];
            }
        }

        $rows[] = [
            'Date' => ' ',
            'Aging Period' => '',
            $this->data['column'] => '',
            'Total' => ' ',
            'Paid' => ' ',
            'Due' => ' ',
        ];

        foreach($this->data['six_month'] as $data){
            $rows[] = [
                'Date' => $data['pi_name'],
                'Aging Period' => '',
                $this->data['column'] => '',
                'Total' => ' ',
                'Paid' => ' ',
                'Due' => $data['due_amount'],
            ];

            foreach($data['items'] as $item){
                $givenDate = \Carbon\Carbon::parse($item->date);
                $diffForHumans = $givenDate->diffForHumans();
                $rows[] = [
                    'Date' => date('d/m/Y', strtotime($item->date)),
                    'Aging Period' => $diffForHumans,
                    $this->data['column'] => $item->invoice_no,
                    'Total' => $item->total_budget,
                    'Paid' => $item->paid_amount,
                    'Due' =>  $item->paid_amount,
                ];
            }
        }

        $rows[] = [
            'Date' => ' ',
            'Aging Period' => '',
            $this->data['column'] => '',
            'Total' => ' ',
            'Paid' => ' ',
            'Due' => ' ',
        ];

        foreach($this->data['twelve_month'] as $data){
            $rows[] = [
                'Date' => $data['pi_name'],
                'Aging Period' => '',
                $this->data['column'] => '',
                'Total' => ' ',
                'Paid' => ' ',
                'Due' => $data['due_amount'],
            ];

            foreach($data['items'] as $item){
                $givenDate = \Carbon\Carbon::parse($item->date);
                $diffForHumans = $givenDate->diffForHumans();
                $rows[] = [
                    'Date' => date('d/m/Y', strtotime($item->date)),
                    'Aging Period' => $diffForHumans,
                    $this->data['column'] => $item->invoice_no,
                    'Total' => $item->total_budget,
                    'Paid' => $item->paid_amount,
                    'Due' =>  $item->paid_amount,
                ];
            }
        }

        $rows[] = [
            'Date' => ' ',
            'Aging Period' => '',
            $this->data['column'] => '',
            'Total' => ' ',
            'Paid' => ' ',
            'Due' => ' ',
        ];

        foreach($this->data['old'] as $data){
            $rows[] = [
                'Date' => $data['pi_name'],
                'Aging Period' => '',
                $this->data['column'] => '',
                'Total' => ' ',
                'Paid' => ' ',
                'Due' => $data['due_amount'],
            ];

            foreach($data['items'] as $item){
                $givenDate = \Carbon\Carbon::parse($item->date);
                $diffForHumans = $givenDate->diffForHumans();
                $rows[] = [
                    'Date' => date('d/m/Y', strtotime($item->date)),
                    'Aging Period' => $diffForHumans,
                    $this->data['column'] => $item->invoice_no,
                    'Total' => $item->total_budget,
                    'Paid' => $item->paid_amount,
                    'Due' =>  $item->paid_amount,
                ];
            }
        }

        $rows[] = [
            'Date' => ' ',
            'Aging Period' => '',
            $this->data['column'] => '',
            'Total' => ' ',
            'Paid' => ' ',
            'Due' => ' ',
        ];

        return $rows;
    }

}
