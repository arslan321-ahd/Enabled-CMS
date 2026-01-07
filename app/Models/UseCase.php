<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UseCase extends Model
{
    protected $table = 'use_cases';

    protected $fillable = [
        'brand_id',
        'name',
        'status',
    ];

    /**
     * Get the brand that owns the use case.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
