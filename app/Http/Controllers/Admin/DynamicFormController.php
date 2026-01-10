<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SubmissionValue;
use App\Models\UseCase;
use Illuminate\Http\Request;

class DynamicFormController extends Controller
{
    public function show()
    {
        $form = Form::where('user_id', auth()->id())
            ->where('active', true)
            ->with('fields')
            ->firstOrFail();

        // Load dynamic data
        $brands = Brand::where('status', 'active')->get();
        $useCases = UseCase::where('status', 'active')->get();

        return view('forms.dynamic', compact('form', 'brands', 'useCases'));
    }

    public function submit(Request $request)
    {
        $form = Form::where('user_id', auth()->id())
            ->where('active', true)
            ->with('fields')
            ->firstOrFail();

        // Build validation rules safely
        $rules = [];

        foreach ($form->fields as $field) {

            $fieldRules = [];

            $fieldRules[] = $field->required ? 'required' : 'nullable';

            if (in_array($field->type, ['text', 'textarea'])) {
                $fieldRules[] = 'string';
            }

            if ($field->type === 'email') {
                $fieldRules[] = 'email';
            }

            if ($field->type === 'number') {
                $fieldRules[] = 'numeric';
            }

            if ($field->validation) {
                foreach (explode('|', $field->validation) as $rule) {
                    $rule = trim($rule);

                    // Fix bad rules like "255"
                    if ($rule === '255') {
                        $fieldRules[] = 'max:255';
                        continue;
                    }

                    if (preg_match('/^(max|min):\s*(\d+)$/', $rule, $m)) {
                        $fieldRules[] = "{$m[1]}:{$m[2]}";
                        continue;
                    }

                    if (in_array($rule, [
                        'string',
                        'email',
                        'numeric',
                        'integer',
                        'file',
                        'image'
                    ])) {
                        $fieldRules[] = $rule;
                    }
                }
            }

            $rules[$field->name] = implode('|', array_unique($fieldRules));
        }

        // Validate
        $validated = $request->validate($rules);
        $oldSubmission = FormSubmission::where('form_id', $form->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($oldSubmission) {
            $oldSubmission->delete(); // cascades submission_values
        }

        // ✅ CREATE NEW SUBMISSION
        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => auth()->id(),
        ]);

        foreach ($form->fields as $field) {
            SubmissionValue::create([
                'submission_id' => $submission->id,
                'form_field_id' => $field->id,
                'value'         => $validated[$field->name] ?? null,
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Form submitted successfully'
        ]);
    }
}
