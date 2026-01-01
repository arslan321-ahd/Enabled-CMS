<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagging extends Model
{
    protected $table = 'taggings';
    protected $fillable = [
        'source',
        'status',
        'ref_url',
    ];
}
