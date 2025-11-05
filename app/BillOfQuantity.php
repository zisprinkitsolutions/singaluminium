<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillOfQuantity extends Model
{
    protected $guarded = [];

    public function tasks(){
        return $this->hasMany(BillOfQuantityTask::class, 'boq_id');
    }
    public function items(){
        return $this->hasMany(BillOfQuantityItem::class, 'bill_id');
    }
    public function project(){
        return $this->belongsTo(NewProject::class,'project_id');
    }

    public function party(){
        return $this->belongsTo(PartyInfo::class, 'party_id');
    }

    public function quotations(){
        return $this->hasMany(LpoProject::class,'project_id');
    }
    public function company(){
        return $this->belongsTo(Subsidiary::class, 'compnay_id');
    }
}
