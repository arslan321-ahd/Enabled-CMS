<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormReview extends Model
{
    protected $fillable = [
        'form_submission_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class, 'form_submission_id');
    }
}
