<?php

namespace App;

use App\Models\AccountHead;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    public function head()
    {
        return $this->belongsTo(AccountHead::class,'head_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class,'unit_id');
    }


}
