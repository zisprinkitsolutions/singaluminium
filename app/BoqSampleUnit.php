<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoqSampleUnit extends Model
{
    protected $guarded = [];

    public function getDeleteAttribute(){
        $exist = BoqSample::where('unit', $this->id)->first();
        if($exist){
            return true;
        }

        return false;
    }
}
