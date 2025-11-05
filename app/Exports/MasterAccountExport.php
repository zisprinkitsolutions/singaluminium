<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;
class MasterAccountExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $records =  DB::table('master_accounts')->join('mst_cat_types', 'mst_cat_types.id', '=', 'master_accounts.account_type_id')
        ->select('master_accounts.mst_ac_head', 'mst_cat_types.title', 'master_accounts.mst_definition', 'master_accounts.mst_ac_type', 'master_accounts.vat_type');
        return $records->whereIn('master_accounts.office_id', [0,Auth::user()->office_id])->get();
    }
    public function headings(): array
    {
        return $select_title = ['Master A/C Head', 'Account Type', 'Definition', 'A/C Type', 'VAT Type'];
    }
}
