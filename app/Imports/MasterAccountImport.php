<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\TempMasterAccount;
class MasterAccountImport implements ToModel
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
        if ($row[0] === 'Master A/C Head') {
            return null;
        }
        return new TempMasterAccount([
            'user_id' => Auth::user()->id,
            'token' => $token,
            'fld_ac_head' => ltrim($row[0]),
            'account_type' => trim($row[1]),
            'definition' => trim($row[2]),
            'ac_type' => ltrim($row[3]),
            'vat_type' => ltrim($row[4]),
        ]);
    }
}
