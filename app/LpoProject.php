<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LpoProject extends Model
{
    protected $guarded=[];

    public function tasks(){
        return $this->hasMany(LpoPorjectTask::class);
    }
    public function items(){
        return $this->hasMany(LpoProjectTaskItem::class, 'lpo_id');
    }
    public function party(){
        return $this->belongsTo(PartyInfo::class,'customer_id');
    }

    public function boq(){
        return $this->belongsTo(BillOfQuantity::class, 'project_id');
    }

    public function jobProjects(){
        return $this->hasMany(JobProject::class, 'lpo_projects_id');
    }
    public function company(){
        return $this->belongsTo(Subsidiary::class, 'company_id');
    }
}
