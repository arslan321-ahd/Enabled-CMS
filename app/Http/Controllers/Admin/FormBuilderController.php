<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormField;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        // Validate form title and logo
        $request->validate([
            'title' => 'required|string|max:255',
            'logo'  => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Upload logo if exists
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('form_logos', 'public');
        }

        // Create the form
        $form = Form::create([
            'title'   => $request->title,
            'user_id' => auth()->id(),
            'logo'    => $logoPath,
            'active'  => 1,
        ]);

        // Save dynamic fields
        foreach ($request->fields as $order => $field) {
            FormField::create([
                'form_id'    => $form->id,
                'label'      => $field['label'],
                'name'       => Str::slug($field['label'], '_'), // optional
                'type'       => $field['type'],
                'validation' => $field['validation'] ?? null,
                'order'      => $order,
            ]);
        }

        return redirect()->back()->with('success', 'Form created successfully!');
    }
}
