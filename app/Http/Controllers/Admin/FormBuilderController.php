<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
    public function index()
    {
        $forms = Form::with([
            'user:id,name',
        ])
            ->withCount([
                'fields',
                'submissions'
            ])
            ->latest()
            ->get();

        return view('admin.customers.customer_list', compact('forms'));
    }
    public function create()
    {
        // Get all users
        $users = User::all();
        // Pass to the view
        return view('admin.customers.add_customer', compact('users'));
    }

    // In your FormController's store method (or wherever you handle form creation)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('form-logos', 'public');
        }
        $slug = Str::slug($validated['title']);

        $exists = Form::where('slug', $slug)->exists();

        if ($exists) {
            $slug .= '-' . time(); // ensures uniqueness
        }

        // Create form
        $form = Form::create([
            'user_id' => $validated['user_id'],
            'title'   => $validated['title'],
            'slug'    => $slug,
            'logo'    => $logoPath ?? null,
            'active'  => true,
        ]);

        // Create form fields
        if ($request->has('fields')) {
            foreach ($request->fields as $index => $field) {
                $fieldName = strtolower(str_replace(' ', '_', $field['label'])) . '_' . $index;

                // Build validation string based on selection
                $validationRules = [];

                if ($field['validation_type'] === 'validation' && isset($field['validation_rules'])) {
                    $validationRules = $field['validation_rules'];
                } elseif ($field['validation_type'] === 'nullable') {
                    $validationRules = ['nullable'];
                }

                // For required fields, remove 'nullable' if present
                if (in_array('required', $validationRules)) {
                    $validationRules = array_filter($validationRules, function ($rule) {
                        return $rule !== 'nullable';
                    });
                }

                // For email type, add email rule if not present
                if ($field['type'] === 'email' && !in_array('email', $validationRules)) {
                    $validationRules[] = 'email';
                }

                // For number type, add numeric rule if not present
                if ($field['type'] === 'number' && !in_array('numeric', $validationRules)) {
                    $validationRules[] = 'numeric';
                }

                // For text and textarea, add string rule if not present and no other string rule
                if (
                    in_array($field['type'], ['text', 'textarea']) &&
                    !in_array('string', $validationRules) &&
                    !array_filter($validationRules, function ($rule) {
                        return strpos($rule, 'regex:') === 0;
                    })
                ) {
                    $validationRules[] = 'string';
                }

                // Convert array to pipe-separated string
                $validationString = implode('|', array_unique($validationRules));

                FormField::create([
                    'form_id' => $form->id,
                    'label' => $field['label'],
                    'name' => $fieldName,
                    'type' => $field['type'],
                    'options' => $field['type'] === 'select' ? ($field['options'] ?? []) : null,
                    'validation' => $validationString,
                    'required' => in_array('required', $validationRules),
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.forms.index')->with('success', 'Form created successfully!');
    }

    public function edit($id)
    {
        $form = Form::with('fields')->findOrFail($id);
        $users = User::all();

        return view('admin.customers.edit_customer', compact('form', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Handle logo upload
        $logoPath = $form->logo;
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($form->logo && Storage::disk('public')->exists($form->logo)) {
                Storage::disk('public')->delete($form->logo);
            }
            $logoPath = $request->file('logo')->store('form-logos', 'public');
        }

        // Update slug only if title changed
        $slug = $form->slug;
        if ($form->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $exists = Form::where('slug', $slug)->where('id', '!=', $id)->exists();
            if ($exists) {
                $slug .= '-' . time();
            }
        }

        // Update form
        $form->update([
            'user_id' => $validated['user_id'],
            'title'   => $validated['title'],
            'slug'    => $slug,
            'logo'    => $logoPath,
        ]);

        // Get existing field IDs
        $existingFieldIds = $form->fields->pluck('id')->toArray();
        $updatedFieldIds = [];

        // Update or create form fields
        if ($request->has('fields')) {
            foreach ($request->fields as $index => $fieldData) {
                $fieldName = strtolower(str_replace(' ', '_', $fieldData['label'])) . '_' . $index;

                // Build validation string based on selection
                $validationRules = [];

                if (isset($fieldData['validation_type']) && $fieldData['validation_type'] === 'validation' && isset($fieldData['validation_rules'])) {
                    $validationRules = (array) $fieldData['validation_rules'];
                } elseif (isset($fieldData['validation_type']) && $fieldData['validation_type'] === 'nullable') {
                    $validationRules = ['nullable'];
                } else {
                    // For existing fields being updated
                    if (isset($fieldData['id']) && $existingField = FormField::find($fieldData['id'])) {
                        $existingRules = explode('|', $existingField->validation);
                        $validationRules = $existingRules;
                    }
                }

                // For required fields, remove 'nullable' if present
                if (in_array('required', $validationRules)) {
                    $validationRules = array_filter($validationRules, function ($rule) {
                        return $rule !== 'nullable';
                    });
                }

                // For email type, add email rule if not present
                if ($fieldData['type'] === 'email' && !in_array('email', $validationRules)) {
                    $validationRules[] = 'email';
                }

                // For number type, add numeric rule if not present
                if ($fieldData['type'] === 'number' && !in_array('numeric', $validationRules)) {
                    $validationRules[] = 'numeric';
                }

                // For text and textarea, add string rule if not present and no other string rule
                if (
                    in_array($fieldData['type'], ['text', 'textarea']) &&
                    !in_array('string', $validationRules) &&
                    !array_filter($validationRules, function ($rule) {
                        return strpos($rule, 'regex:') === 0;
                    })
                ) {
                    $validationRules[] = 'string';
                }

                // Convert array to pipe-separated string
                $validationString = implode('|', array_unique($validationRules));

                // Check if field already exists
                if (isset($fieldData['id']) && in_array($fieldData['id'], $existingFieldIds)) {
                    // Update existing field
                    $field = FormField::find($fieldData['id']);
                    $field->update([
                        'label' => $fieldData['label'],
                        'name' => $fieldName,
                        'type' => $fieldData['type'],
                        'options' => $fieldData['type'] === 'select' ? ($fieldData['options'] ?? []) : null,
                        'validation' => $validationString,
                        'required' => in_array('required', $validationRules),
                        'order' => $index,
                    ]);
                    $updatedFieldIds[] = $fieldData['id'];
                } else {
                    // Create new field
                    $field = FormField::create([
                        'form_id' => $form->id,
                        'label' => $fieldData['label'],
                        'name' => $fieldName,
                        'type' => $fieldData['type'],
                        'options' => $fieldData['type'] === 'select' ? ($fieldData['options'] ?? []) : null,
                        'validation' => $validationString,
                        'required' => in_array('required', $validationRules),
                        'order' => $index,
                    ]);
                    $updatedFieldIds[] = $field->id;
                }
            }
        }

        // Delete fields that were removed
        $fieldsToDelete = array_diff($existingFieldIds, $updatedFieldIds);
        if (!empty($fieldsToDelete)) {
            FormField::whereIn('id', $fieldsToDelete)->delete();
            // Also delete associated submission values
            DB::table('submission_values')
                ->whereIn('form_field_id', $fieldsToDelete)
                ->delete();
        }

        return redirect()->route('admin.forms.index')->with('success', 'Form updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $form = Form::findOrFail($id);

            // Delete logo if exists
            if ($form->logo && Storage::disk('public')->exists($form->logo)) {
                Storage::disk('public')->delete($form->logo);
            }

            // Delete the form (cascade will handle related records)
            $form->delete();

            // Return JSON response for AJAX requests
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Form deleted successfully!',
                    'data' => ['id' => $id]
                ]);
            }

            return redirect()->route('admin.forms.index')
                ->with('success', 'Form deleted successfully!');
        } catch (\Exception $e) {
            // Return JSON error for AJAX requests
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting form: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.forms.index')
                ->with('error', 'Error deleting form: ' . $e->getMessage());
        }
    }
}
