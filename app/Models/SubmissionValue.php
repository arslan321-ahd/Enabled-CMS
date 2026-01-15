<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionValue extends Model
{
    protected $table = 'submission_values';
    public $timestamps = false;
    protected $fillable = [
        'submission_id',
        'form_field_id',
        'value'
    ];
    protected $appends = ['display_value'];
    protected $casts = [
        'value' => 'string',
    ];
    // Relationship with FormField
    public function field(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'form_field_id');
    }

    // Relationship with FormSubmission
    public function submission(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class, 'submission_id');
    }

    // Accessor to get display value for dynamic sources
    public function getDisplayValueAttribute()
    {
        $field = $this->field;
        if (!$field) {
            return $this->value;
        }

        // Check if this field uses dynamic data source
        if ($field->data_source && $field->type === 'select' && !empty($this->value)) {
            switch ($field->data_source) {
                case 'brand':
                    $brand = Brand::find($this->value);
                    return $brand ? $brand->name : $this->value;

                case 'tagging':
                    $tag = Tagging::find($this->value);
                    return $tag ? $tag->source : $this->value; // Note: Tagging uses 'source' not 'name'

                case 'usecases':
                    $usecase = UseCase::find($this->value);
                    return $usecase ? $usecase->name : $this->value;

                default:
                    return $this->value;
            }
        }

        // For checkbox type
        if ($field->type === 'checkbox') {
            return $this->value == 1 ? 'Accepted' : 'Not Accepted';
        }

        // For empty values
        if ($this->value === null || $this->value === '') {
            return '-';
        }

        return $this->value;
    }
}