<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngineerReport extends Model
{
    protected $guarded = [];

    public function new_project(){
        return $this->belongsTo(NewProject::class, 'new_project_id');
    }

    public function task(){
        return $this->belongsTo(JobProjectTask::class, 'task_id');
    }

    public function item(){
        return $this->belongsTo(NewProject::class, 'new_project_id');
    }

    public function job_project(){
        return $this->belongsTo(JobProject::class, 'job_project_id');
    }

    public function details(){
        return $this->hasMany(EngineerReportDetails::class, 'engineer_report_id');
    }

    public function engineer(){
        return $this->belongsTo(Employee::class, 'engineer_id');
    }

    public function documents(){
        return $this->hasMany(ProjectDocument::class, 'engineer_report_id');
    }
}
