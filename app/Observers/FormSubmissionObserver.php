<?php

namespace App\Observers;

use App\Models\FormSubmission;
use App\Services\LogService;

class FormSubmissionObserver
{
    public function created(FormSubmission $submission)
    {
        $user = $submission->user
            ? $submission->user->name
            : 'Guest';

        LogService::create(
            'Form Submitted',
            "Form '{$submission->form->title}' was submitted by {$user}",
            'submitted',
            $submission
        );
    }

    public function deleted(FormSubmission $submission)
    {
        LogService::create(
            'Submission Deleted',
            "A submission for form '{$submission->form->title}' was deleted",
            'deleted',
            $submission
        );
    }
}
