<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubsidiaryStore extends Model
{
    public function subsidiary(){
        return $this->belongsTo(Subsidiary::class, 'company_id');
    }
}
