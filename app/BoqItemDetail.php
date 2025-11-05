<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoqItemDetail extends Model
{
    protected $fillable = ['task_id', 'description','unit', 'qty','rate', 'amount'];
}
