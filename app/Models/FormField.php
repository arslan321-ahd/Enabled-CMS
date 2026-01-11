<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'form_id',
        'label',
        'name',
        'type',
        'options',
        'validation',
        'required',
        'order'
    ];

    protected $casts = [
        'options' => 'array'
    ];
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
