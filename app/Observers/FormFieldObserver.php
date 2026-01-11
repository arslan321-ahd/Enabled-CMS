<?php

namespace App\Observers;

use App\Models\FormField;
use App\Services\LogService;

class FormFieldObserver
{
    public function created(FormField $field)
    {
        $formTitle = optional($field->form)->title ?? 'Unknown Form';

        LogService::create(
            'Field Added',
            "Field '{$field->label}' was added to form '{$formTitle}'",
            'created',
            $field
        );
    }

    public function updated(FormField $field)
    {
        $formTitle = optional($field->form)->title ?? 'Unknown Form';

        LogService::create(
            'Field Updated',
            "Field '{$field->label}' was updated in form '{$formTitle}'",
            'updated',
            $field
        );
    }

    public function deleted(FormField $field)
    {
        $formTitle = optional($field->form)->title ?? 'Unknown Form';

        LogService::create(
            'Field Removed',
            "Field '{$field->label}' was removed from form '{$formTitle}'",
            'deleted',
            $field
        );
    }
}
