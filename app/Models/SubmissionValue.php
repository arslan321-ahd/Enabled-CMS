<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionValue extends Model
{
    protected $fillable = ['submission_id', 'form_field_id', 'value'];
    public $timestamps = false;
}
