<?php

namespace App\Imports;

use App\PartyInfo;
use App\Models\AccountHead;
use App\TaxInvoice;
use App\Models\MasterAccount;
use App\Journal;
use App\JournalRecord;
use App\PurchaseExpense;
use App\TempInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class InvoiceImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function startRow(): int
    {
        return 2;
    }
    public function model(array $row)
    {
        $token = Session::get('token');
        // Skip header rows
        if ($row[0] === 'TAX INVOICE DATE' || $row[0] === 'DATE') {
            return null;
        }

        try {
            // Process Party Info
            if ($row[11]) {
                $party_info = PartyInfo::where('pi_name', $row[10])->where('pi_type', $this->getPartyType($row[2], $row[3], $row[4]))->first();
                if ($party_info) {
                    $party_info->pi_type = $this->getPartyType($row[2], $row[3], $row[4]);
                    $party_info->save();
                } else {
                    $party_info = $this->createPartyInfo($row);
                }
            } else {
                $party_info = PartyInfo::find(56); // Default PartyInfo
            }
            // Validate required data
            if ($row[0] && $row[1] && $row[5] && $row[3] && $party_info) {
                $date = $this->transformDate($row[0]);
                $vat = $this->calculateVAT($row[5], $row[6]);
                // dd($row);
                return new TempInvoice([
                    'office_id' => Auth::user()->office_id,
                    'user_id' => Auth::user()->id,
                    'token' => $token,
                    'date' => $date,
                    'invoice_no' => $row[1],
                    'type' => $row[2],
                    'debit_account' => ltrim($row[3]),
                    'credit_account' => ltrim($row[4]),
                    'amount' => $row[5],
                    'tax_rate' => $row[6],
                    'vat' => $vat,
                    'total' => $row[5] + $vat,
                    'paid_amount' => $row[9]?$row[9]:0.00,
                    'party_id' => $party_info->id,
                    'narration' => $row[12],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing row: ', $row);
            Log::error($e->getMessage());
            return null; // Skip invalid row
        }

        return null; // Skip empty or invalid row
    }

    public function chunkSize(): int
    {
        return 500; // Process rows in chunks of 500
    }

    private function transformDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            $unix_date = ($excelDate - 25569) * 86400;
            return gmdate("Y-m-d", $unix_date);
        }
        return strtr($excelDate, '/', '-');
    }

    private function calculateVAT($amount, $taxRate)
    {
        return ($taxRate === 'Standard' || $taxRate === 'STANDARD') ? $amount * 0.05 : 0;
    }

    private function getPartyType($type,$debit,$credit)
    {
        if($type == "ADJUSTMENT"){
            if($credit == 'ACCOUNT RECEIVABLE' || $credit == 'Account Receivable'){
                return 'Customer';
            }elseif($debit == 'ACCOUNT PAYABLE' || $debit == 'Account Payable'){
                return 'Supplier';
            }else{
                return in_array($type, ['Sale', 'SALES', 'Income', 'OTHER INCOME', 'ADJUSTMENT']) ? 'Customer' : 'Supplier';
            }
        }else{
            return in_array($type, ['Sale', 'SALES', 'Income', 'OTHER INCOME']) ? 'Customer' : 'Supplier';
        }
    }

    private function createPartyInfo($row)
    {
        $latest = PartyInfo::withTrashed()->orderBy('id', 'DESC')->first();
        $pi_code = $latest ? (int) preg_replace('/^PI-/', '', $latest->pi_code) + 1 : 1;
        $pi_code = sprintf('PI-%04d', $pi_code);

        $party_info = new PartyInfo();
        $party_info->office_id = Auth::user()->office_id;
        $party_info->pi_code = $pi_code;
        $party_info->pi_name = $row[10];
        $party_info->pi_type = $this->getPartyType($row[2], $row[3], $row[4]);
        $party_info->trn_no = $row[11];
        $party_info->save();

        return $party_info;
    }
}
