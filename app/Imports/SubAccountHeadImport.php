<?php

namespace App\Imports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\TempSubAccountHead;

class SubAccountHeadImport implements ToModel
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
        if ($row[0] === 'Sub Account Head') {
            return null;
        }
        return new TempSubAccountHead([
            'user_id' => Auth::user()->id,
            'token' => $token,
            'sub_head' => trim($row[0]),
            'account_head' => ltrim($row[1]),
        ]);
    }
}
