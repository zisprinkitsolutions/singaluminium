<?php

namespace App;
use App\Models\AccountHead;
use App\AccountSubHead;
use Illuminate\Database\Eloquent\Model;

class ExpenseAllocationItem extends Model
{
    public function project()
    {
        return $this->belongsTo(NewProject::class,'project_id');
    }
}
