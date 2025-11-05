<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDocumentUpload extends Model
{
    protected $fillable = ['job_project_id', 'display_name','filename', 'extension'];
}
