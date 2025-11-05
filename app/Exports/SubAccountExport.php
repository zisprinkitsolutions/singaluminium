<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
class SubAccountExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $records =  DB::table('account_sub_heads')->join('account_heads', 'account_heads.id', '=', 'account_sub_heads.account_head_id')
        ->select('account_sub_heads.name', 'account_heads.fld_ac_head');
        return $records->whereIn('account_sub_heads.office_id', [0,Auth::user()->office_id])->get();
    }
    public function headings(): array
    {
        return $select_title = ['Sub Account Head', 'Account Head'];
    }
}
