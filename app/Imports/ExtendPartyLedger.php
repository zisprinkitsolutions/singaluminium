<?php

namespace App\Imports;

use App\Journal;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ExtendPartyLedger implements FromArray, WithHeadings
{
    /**
    * @param Collection $collection
    */

    public $parties;

    public function __construct($parties){
        $this->parties = $parties;
    }

    public function headings():array{
        return [
            'Name',
            'Code',
            'Type',
            'Remark',
            'Debit',
            'Credit',
            'Balance',
        ];
    }

    public function array():array{

        $rows = [];

        foreach ($this->parties as $party) {

            $rows[] = [
                'name'   => $party['pi_name'],
                'code'   => $party['pi_code'],
                'type'   => $party['pi_type'],
                'remark' => $party['dr_amount'] > $party['cr_amount'] ? 'Receivable' : 'Payable',
                'debit'  => $party['dr_amount'],
                'credit' => $party['cr_amount'],
                'balance' => abs($party['dr_amount'] - $party['cr_amount']),
            ];

            $rows[] = [
                'name'    => 'Date',
                'code'    => 'Narration',
                'type'    => 'Reference',
                'remark'  => '',
                'debit'   => 'Debit',
                'credit'  => 'Credit',
                'balance' => 'Balance',
            ];


            $balance_dr =  0.00;
            $balance_cr = 0.00;
            $balance=0;

            foreach ($party['items'] as $item){
                $journal=Journal::find($item->journal_id);
                $isPayment=true;
                if(!$journal->receipt_id && !$journal->payment_id){
                    $isPayment=false;
                }

                $cr_amount = $item->transaction_type == 'CR' ? $item->amount : 0.00;
                $dr_amount = $item->transaction_type == 'DR' ? $item->amount : 0.00;
                $balance_dr += $dr_amount;
                $balance_cr += $cr_amount;

                $data = [
                    'name' => date('d/m/Y',strtotime($journal->date)),
                    'code' => $journal->party_journal_description($journal->id)['name'],
                    'type' => $journal->party_journal_description($journal->id)['tasks'],
                    'remark'  => '',
                 ];
                if($journal->invoice_id){
                    $b=$journal->records()->where('account_head_id','!=',31)->where('transaction_type','DR')->sum('amount');
                    $data['debit'] = $b;
                    $data['Credit'] = 0.00;
                    $balance_dr +=  $b;
                }else{
                    $c=$journal->records()->where('account_head_id','!=',407)->where('transaction_type','DR')->sum('amount');
                    $data['debit'] = 0.00;
                    $data['credit'] = $c;
                    $balance_dr -=  $c;
                }

                $data['balance'] = abs($balance_dr-$balance_cr);

                $rows[] = $data;
            }


            $rows[] = ['', '', '', '','','',''];
        }

        return $rows;
    }
}
