<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MissingInvoiceExport implements  FromArray, WithHeadings, ShouldAutoSize
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
        foreach ($this->records as $record) {

            $data[] = [
                $record->missing_date,
                $record->invoice_number,
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Missing Date',
            'Probably Missing Invoice',
        ];
    }
}
