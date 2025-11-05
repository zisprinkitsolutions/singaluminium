<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseImport extends Model
{
    protected $fillable = ['token', 'project_code','project_name','date', 'party_id', 'vr', 'bill_no', 'description', 'amount', 'account_head'];
    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }
}
