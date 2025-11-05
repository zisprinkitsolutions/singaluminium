<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
class AccountHeadExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $records =  DB::table('account_heads')->join('master_accounts', 'master_accounts.id', 'account_heads.master_account_id')
        ->select('account_heads.fld_ac_head', 'master_accounts.mst_ac_head');
        return $records->whereIn('account_heads.office_id', [0,Auth::user()->office_id])->get();
    }
    public function headings(): array
    {
        return $select_title = ['Account Head', 'Master A/C Head'];
    }
}
