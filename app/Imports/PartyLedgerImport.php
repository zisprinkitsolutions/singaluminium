<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
class PartyLedgerImport implements FromArray, WithHeadings
{
    /**
    * @param Collection $collection
    */
    public $parties;

    public function __construct($parties)
    {
        $this->parties = $parties;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->parties as $party) {
            $rows[] = [
                'name'   => $party->pi_name,
                'code'   => $party->pi_code,
                'type'   => $party->pi_type,
                'debit'  => $party->dr_amount,
                'credit' => $party->cr_amount,
                'balance' => abs($party->dr_amount - $party->cr_amount),
                'remark' => $party->dr_amount > $party->cr_amount ? 'Receivable' : 'Payable',
            ];
        }
        return $rows;
    }

    public function headings() :array{
        return [
            'Name',
            'Tode',
            'Type',
            'Debit',
            'Credit',
            'Balance',
            'Remark'
        ];
    }

}
