<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_id',
        'user_id'
    ];

    // Relationship with User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Form
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    // Relationship with SubmissionValues - SPECIFY THE FOREIGN KEY
    public function values(): HasMany
    {
        // Specify the foreign key since it's 'submission_id' not 'form_submission_id'
        return $this->hasMany(SubmissionValue::class, 'submission_id');
    }
    public function getSubmitterEmailAttribute()
    {
        // If logged in user
        if ($this->user) {
            return $this->user->email;
        }

        // For guest users, fetch email from submitted values
        return $this->values()
            ->whereHas('field', function ($q) {
                $q->where('type', 'email');
            })
            ->value('value');
    }
}
