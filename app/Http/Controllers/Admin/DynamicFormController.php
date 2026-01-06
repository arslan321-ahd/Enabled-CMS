<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SubmissionValue;
use Illuminate\Http\Request;

class DynamicFormController extends Controller
{
    public function show(Form $form)
    {
        // // Optional: check if the logged-in user is allowed to view this form
        // if ($form->user_id !== auth()->id() || !$form->active) {
        //     abort(403);
        // }

        $form->load('fields'); // eager load fields

        return view('forms.dynamic', compact('form'));
    }


    public function submit(Request $request)
    {
        $form = Form::with('fields')
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $rules = [];
        foreach ($form->fields as $field) {
            if ($field->validation) {
                $rules[$field->name] = $field->validation;
            }
        }

        $validated = $request->validate($rules);

        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => auth()->id(),
        ]);

        foreach ($form->fields as $field) {
            SubmissionValue::create([
                'submission_id' => $submission->id,
                'form_field_id' => $field->id,
                'value' => $request[$field->name] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Form Submitted');
    }
}
