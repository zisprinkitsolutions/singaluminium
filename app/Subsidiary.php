<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subsidiary extends Model
{
   protected $fillable = [
        'company_name',
        'company_name_arabic',
        'company_address',
        'company_address_arabic',
        'company_email',
        'company_mobile',
        'company_tele',
        'currency',
        'p_o_box',
        'running_no',
        'title_name',
        'trn_no',
        'arabic_context',
        'image',
    ];
}
