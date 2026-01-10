<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FormBuilderController extends Controller
{
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

        // Create form
        $form = Form::create([
            'user_id' => $validated['user_id'],
            'title' => $validated['title'],
            'logo' => $logoPath ?? null,
            'active' => true,
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

        return redirect()->back()->with('success', 'Form created successfully!');
    }
}
