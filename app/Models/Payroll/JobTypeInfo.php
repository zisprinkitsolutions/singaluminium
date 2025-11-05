<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Model;

class JobTypeInfo extends Model
{
    protected $guarded = [];

    public function type_name(){
        return $this->hasOne(JobType::class , 'id','type');
    }

}
