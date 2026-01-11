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
        $form = Form::where('active', true)
            ->with('fields');
        $brands = Brand::where('status', 'active')->get();
        $useCases = UseCase::where('status', 'active')->get();

        return view('forms.dynamic', compact('form', 'brands', 'useCases'));
    }
    public function showSubmissions(Form $form)
    {
        $submissions = FormSubmission::where('form_id', $form->id)
            ->with(['user', 'values.field'])
            ->latest()
            ->paginate(20);
        return view('admin.customers.submissions', compact('form', 'submissions'));
    }
    public function showPublic($slug)
    {
        $form = Form::where('slug', $slug)
            ->where('active', true)
            ->with('fields')
            ->firstOrFail();
        $brands   = Brand::where('status', 'active')->get();
        $useCases = UseCase::where('status', 'active')->get();
        return view('forms.dynamic', compact('form', 'brands', 'useCases'));
    }

    public function submit(Request $request, $slug)
    {
        $form = Form::where('slug', $slug)
            ->where('active', true)
            ->with('fields')
            ->firstOrFail();
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
                    if ($rule === '255') {
                        $fieldRules[] = 'max:255';
                        continue;
                    }
                    if (preg_match('/^(max|min):(\d+)$/', $rule, $m)) {
                        $fieldRules[] = "{$m[1]}:{$m[2]}";
                    }
                }
            }
            $rules[$field->name] = implode('|', array_unique($fieldRules));
        }
        $validated = $request->validate($rules);
        if (auth()->check()) {
            FormSubmission::where('form_id', $form->id)
                ->where('user_id', auth()->id())
                ->delete();
        }
        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'user_id' => auth()->check() ? auth()->id() : null,
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
            'message' => 'Form submitted successfully',
        ]);
    }
}
