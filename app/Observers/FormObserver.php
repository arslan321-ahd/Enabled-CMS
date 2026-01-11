<?php

namespace App\Observers;

use App\Models\Form;
use App\Services\LogService;

class FormObserver
{
    public function created(Form $form)
    {
        LogService::create(
            'Form Created',
            "Form '{$form->title}' was created",
            'created',
            $form
        );
    }

    public function updated(Form $form)
    {
        if ($form->wasChanged('active')) {
            $status = $form->active ? 'activated' : 'deactivated';

            LogService::create(
                'Form Status Changed',
                "Form '{$form->title}' was {$status}",
                'status_changed',
                $form
            );
        } else {
            LogService::create(
                'Form Updated',
                "Form '{$form->title}' was updated",
                'updated',
                $form
            );
        }
    }


    public function deleted(Form $form)
    {
        LogService::create(
            'Form Deleted',
            "Form '{$form->title}' was deleted",
            'deleted',
            $form
        );
    }
}
