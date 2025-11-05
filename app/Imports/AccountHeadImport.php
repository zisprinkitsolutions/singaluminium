<?php

namespace App\Imports;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\TempAccountHead;

class AccountHeadImport implements ToModel
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
        if ($row[0] === 'Account Head') {
            return null;
        }
        return new TempAccountHead([
            'user_id' => Auth::user()->id,
            'token' => $token,
            'account_head' => ltrim($row[0]),
            'master_account' => trim($row[1]),
        ]);
    }
}
