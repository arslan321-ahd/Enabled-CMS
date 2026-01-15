<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $users = User::all();
        return view('admin.customers.add_customer', compact('users'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('form-logos', 'public');
        }

        $slug = Str::slug($validated['title']);
        $exists = Form::where('slug', $slug)->exists();

        if ($exists) {
            $slug .= '-' . time();
        }

        $form = Form::create([
            'user_id' => $validated['user_id'],
            'title'   => $validated['title'],
            'slug'    => $slug,
            'logo'    => $logoPath ?? null,
            'active'  => true,
        ]);

        if ($request->has('fields')) {
            foreach ($request->fields as $index => $field) {
                $fieldName = strtolower(str_replace(' ', '_', $field['label'])) . '_' . $index;
                $validationRules = [];

                // Handle validation
                if (isset($field['validation_type']) && $field['validation_type'] === 'validation' && isset($field['validation_rules'])) {
                    $validationRules = (array)$field['validation_rules'];
                } elseif (isset($field['validation_type']) && $field['validation_type'] === 'nullable') {
                    $validationRules = ['nullable'];
                }

                if (in_array('required', $validationRules)) {
                    $validationRules = array_filter($validationRules, function ($rule) {
                        return $rule !== 'nullable';
                    });
                }

                // Type-specific validation
                if (isset($field['type'])) {
                    if ($field['type'] === 'email' && !in_array('email', $validationRules)) {
                        $validationRules[] = 'email';
                    }
                    if ($field['type'] === 'number' && !in_array('numeric', $validationRules)) {
                        $validationRules[] = 'numeric';
                    }
                    if (in_array($field['type'], ['text', 'textarea']) && !in_array('string', $validationRules)) {
                        $validationRules[] = 'string';
                    }
                }

                $validationString = implode('|', array_unique($validationRules));

                // Build field data
                $formFieldData = [
                    'form_id' => $form->id,
                    'label' => $field['label'] ?? 'Untitled Field',
                    'name' => $fieldName,
                    'type' => $field['type'] ?? 'text',
                    'validation' => $validationString,
                    'required' => in_array('required', $validationRules),
                    'order' => $index,
                    'data_source' => null,
                    'options' => null,
                    'checkbox_terms' => null,
                ];

                // Handle select fields
                if (isset($field['type']) && $field['type'] === 'select') {
                    if (isset($field['data_source']) && $field['data_source'] === 'database') {
                        $formFieldData['data_source'] = $field['data_source_select'] ?? null;
                    } else {
                        if (isset($field['options']) && is_array($field['options'])) {
                            $filteredOptions = array_values(array_filter($field['options']));
                            if (!empty($filteredOptions)) {
                                $formFieldData['options'] = json_encode($filteredOptions);
                            }
                        }
                    }
                }

                // Handle checkbox fields
                if (isset($field['type']) && $field['type'] === 'checkbox') {
                    $formFieldData['checkbox_terms'] = $field['checkbox_terms'] ?? null;
                }

                FormField::create($formFieldData);
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
    public function update(Request $request, $id)
    {
        $form = Form::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Handle logo update
        $logoPath = $form->logo;
        if ($request->hasFile('logo')) {
            if ($form->logo && Storage::disk('public')->exists($form->logo)) {
                Storage::disk('public')->delete($form->logo);
            }
            $logoPath = $request->file('logo')->store('form-logos', 'public');
        }

        // Update slug if title changed
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

        $existingFieldIds = $form->fields->pluck('id')->toArray();
        $updatedFieldIds = [];

        if ($request->has('fields')) {
            foreach ($request->fields as $index => $fieldData) {
                $fieldName = strtolower(str_replace(' ', '_', $fieldData['label'])) . '_' . $index;
                $validationRules = [];

                // Handle validation rules
                if (isset($fieldData['validation_type']) && $fieldData['validation_type'] === 'validation' && isset($fieldData['validation_rules'])) {
                    $validationRules = (array) $fieldData['validation_rules'];
                } elseif (isset($fieldData['validation_type']) && $fieldData['validation_type'] === 'nullable') {
                    $validationRules = ['nullable'];
                } else {
                    // If no validation type specified, keep existing rules
                    if (isset($fieldData['id']) && $existingField = FormField::find($fieldData['id'])) {
                        $existingRules = explode('|', $existingField->validation);
                        $validationRules = $existingRules;
                    }
                }

                // Remove nullable if required is present
                if (in_array('required', $validationRules)) {
                    $validationRules = array_filter($validationRules, function ($rule) {
                        return $rule !== 'nullable';
                    });
                }

                // Add type-specific validation
                if (isset($fieldData['type'])) {
                    if ($fieldData['type'] === 'email' && !in_array('email', $validationRules)) {
                        $validationRules[] = 'email';
                    }
                    if ($fieldData['type'] === 'number' && !in_array('numeric', $validationRules)) {
                        $validationRules[] = 'numeric';
                    }
                    if (in_array($fieldData['type'], ['text', 'textarea']) && !in_array('string', $validationRules)) {
                        $validationRules[] = 'string';
                    }
                }

                $validationString = implode('|', array_unique($validationRules));

                // Prepare field data - set defaults
                $fieldUpdateData = [
                    'label' => $fieldData['label'],
                    'name' => $fieldName,
                    'type' => $fieldData['type'],
                    'validation' => $validationString,
                    'required' => in_array('required', $validationRules),
                    'order' => $index,
                    'data_source' => null,
                    'options' => null,
                    'checkbox_terms' => null,
                ];

                // Handle select fields
                if (isset($fieldData['type']) && $fieldData['type'] === 'select') {
                    if (isset($fieldData['data_source']) && $fieldData['data_source'] === 'database') {
                        // Dynamic select - store data source
                        $fieldUpdateData['data_source'] = $fieldData['data_source_select'] ?? null;
                        $fieldUpdateData['options'] = null; // No manual options for dynamic selects
                    } else {
                        // Custom select - store options
                        $fieldUpdateData['data_source'] = null;

                        // Handle options as array
                        if (isset($fieldData['options'])) {
                            // Convert to array if it's not already
                            $options = is_array($fieldData['options']) ? $fieldData['options'] : [$fieldData['options']];

                            // Filter out null, empty strings, and false values
                            $filteredOptions = array_filter($options, function ($value) {
                                return $value !== null && $value !== '' && $value !== false;
                            });

                            // Reset array keys to ensure all options are saved
                            $filteredOptions = array_values($filteredOptions);

                            if (!empty($filteredOptions)) {
                                $fieldUpdateData['options'] = json_encode($filteredOptions);
                            } else {
                                $fieldUpdateData['options'] = null;
                            }
                        }
                    }
                }

                // Handle checkbox fields
                if (isset($fieldData['type']) && $fieldData['type'] === 'checkbox') {
                    $fieldUpdateData['checkbox_terms'] = $fieldData['checkbox_terms'] ?? null;
                    // IMPORTANT: Ensure options is null for checkbox fields
                    $fieldUpdateData['options'] = null;
                }

                // For non-select fields, ensure data_source is null
                if (!isset($fieldData['type']) || $fieldData['type'] !== 'select') {
                    $fieldUpdateData['data_source'] = null;
                }

                // Update or create field
                if (isset($fieldData['id']) && in_array($fieldData['id'], $existingFieldIds)) {
                    $field = FormField::find($fieldData['id']);
                    $field->update($fieldUpdateData);
                    $updatedFieldIds[] = $fieldData['id'];
                } else {
                    $field = FormField::create(array_merge(['form_id' => $form->id], $fieldUpdateData));
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
    public function destroy($id)
    {
        try {
            $form = Form::findOrFail($id);
            if ($form->logo && Storage::disk('public')->exists($form->logo)) {
                Storage::disk('public')->delete($form->logo);
            }
            $form->delete();
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
    public function getFormFields(Form $form)
    {
        $fields = $form->fields()->orderBy('order')->get(['id', 'label', 'type']);
        return response()->json($fields);
    }
}
