<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mockery\Undefined;

class BillOfQuantityTask extends Model
{
    protected $guarded = [];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function sub_tasks(){
        return $this->hasMany(BillOfQuantitySubTask::class, 'task_id');
    }

    public function items(){
        return $this->hasMany(BillOfQuantityItem::class,'task_id');
    }

    public function getEstimatedProgressAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return 100;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $now = Carbon::now();

        if ($now->lt($start)) {
            return 0;
        }

        if ($now->gt($end)) {
            return 100;
        }

        $totalDuration = $start->diffInSeconds($end);

        if ($totalDuration === 0) {
            return 100; // or 0, depending on your logic
        }

        $elapsed = $start->diffInSeconds($now);
        $progress = ($elapsed / $totalDuration) * 100;

        return round($progress, 2);
    }
}
