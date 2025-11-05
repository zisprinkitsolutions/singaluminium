<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GnattChart extends Model
{

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(GnattChartItem::class,'gnatt_chart_id');
    }

    public function quotation(){
        return $this->belongsTo(LpoProject::class, 'quotation_id');
    }

    public function job_project(){
        return $this->belongsTo(JobProject::class, 'job_project_id');
    }

    public function party(){
        return $this->belongsTo(PartyINfo::class, 'party_id');
    }

    public function project(){
        return $this->belongsTo(LpoProject::class, 'project_id');
    }
}
