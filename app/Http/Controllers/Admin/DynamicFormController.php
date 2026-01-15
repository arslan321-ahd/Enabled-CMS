<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Form;
use App\Models\FormReview;
use App\Models\FormSubmission;
use App\Models\SubmissionValue;
use App\Models\Tagging;
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
            ->with([
                'user',
            'values.field', // This will load the field relationship
            'review'
            ])
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
            'status'        => true,
            'message'       => 'Form submitted successfully',
            'submission_id' => $submission->id,
        ]);
    }
    public function submitReview(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => ['required', 'exists:form_submissions,id'],
            'rating'        => ['nullable', 'integer', 'min:1', 'max:5'],
            'comment'       => ['nullable', 'string', 'max:1000'],
        ]);

        // Prevent duplicate reviews for the same submission
        $alreadyReviewed = FormReview::where(
            'form_submission_id',
            $validated['submission_id']
        )->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'status'  => false,
                'message' => 'Review already submitted for this form.',
            ], 409);
        }

        FormReview::create([
            'form_submission_id' => $validated['submission_id'],
            'rating'             => $validated['rating'],
            'comment'            => $validated['comment'],
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Review submitted successfully',
        ]);
    }

    // app/Http/Controllers/Admin/FormController.php
    public function getSubmissionDetails(FormSubmission $submission)
    {
        // Eager load with dynamic value resolution
        $submission->load([
            'values' => function ($query) {
                $query->with(['field' => function ($q) {
                    $q->select('id', 'label', 'type', 'data_source');
                }]);
            },
            'review'
        ]);

        // Transform values to include display_value
        $values = $submission->values->map(function ($value) {
            return [
                'id' => $value->id,
                'form_field_id' => $value->form_field_id,
                'value' => $value->value,
                'display_value' => $value->display_value,
                'field' => $value->field ? [
                    'id' => $value->field->id,
                    'label' => $value->field->label,
                    'type' => $value->field->type,
                    'data_source' => $value->field->data_source
                ] : null
            ];
        });

        return response()->json([
            'values' => $values,
            'review' => $submission->review
        ]);
    }

    public function getDynamicValue(Request $request)
    {
        $source = $request->get('source');
        $id = $request->get('id');

        if (!$source || !$id) {
            return response()->json(['name' => null]);
        }

        switch ($source) {
            case 'brand':
                $item = Brand::select('id', 'name')->find($id);
                break;
            case 'tagging':
                $item = Tagging::select('id', 'source as name')->find($id);
                break;
            case 'usecases':
                $item = UseCase::select('id', 'name')->find($id);
                break;
            default:
                $item = null;
        }

        return response()->json([
            'name' => $item ? $item->name : 'Not Found',
            'id' => $id
        ]);
    }
}
