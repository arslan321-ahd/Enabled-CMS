<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionValue extends Model
{
    protected $fillable = [
        'submission_id',
        'form_field_id',
        'value'
    ];

    // Disable timestamps since your migration doesn't have them
    public $timestamps = false;

    // Relationship with FormSubmission - SPECIFY THE FOREIGN KEY
    public function submission(): BelongsTo
    {
        // Specify the foreign key since it's 'submission_id' not 'form_submission_id'
        return $this->belongsTo(FormSubmission::class, 'submission_id');
    }

    // Relationship with FormField
    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }
}
