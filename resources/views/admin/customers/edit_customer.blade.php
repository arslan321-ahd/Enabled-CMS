@extends('admin.partials.layouts')
@section('content')
@section('title', 'Edit Customer Form')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Edit Customer Form</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Customer Form</li>
                    </ol>
                </div>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Edit Customer Form</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('forms.update', $form->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label>Select User</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- Select User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $form->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label>Form Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter form title"
                                    value="{{ $form->title }}" required>
                            </div>
                            <div class="col-4 mb-3">
                                <label>Form Logo</label>
                                @if ($form->logo)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($form->logo) }}" alt="Current Logo"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                        <br>
                                        <small class="text-muted">Current logo</small>
                                    </div>
                                @endif
                                <input type="file" name="logo" class="form-control">
                                <small class="text-muted">Leave empty to keep current logo</small>
                            </div>
                        </div>
                        <div id="fields-wrapper">
                            @foreach ($form->fields as $index => $field)
                                <div class="field-group mb-3">
                                    <input type="hidden" name="fields[{{ $index }}][id]"
                                        value="{{ $field->id }}">
                                    <div class="row align-items-end">
                                        <div class="col-3">
                                            <label>Field Label</label>
                                            <input type="text" name="fields[{{ $index }}][label]"
                                                class="form-control" value="{{ $field->label }}" required>
                                        </div>
                                        <div class="col-2">
                                            <label>Field Type</label>
                                            <select name="fields[{{ $index }}][type]"
                                                class="form-control field-type">
                                                <option value="text" {{ $field->type == 'text' ? 'selected' : '' }}>
                                                    Text</option>
                                                <option value="email" {{ $field->type == 'email' ? 'selected' : '' }}>
                                                    Email</option>
                                                <option value="number"
                                                    {{ $field->type == 'number' ? 'selected' : '' }}>Number</option>
                                                <option value="textarea"
                                                    {{ $field->type == 'textarea' ? 'selected' : '' }}>Textarea
                                                </option>
                                                <option value="select"
                                                    {{ $field->type == 'select' ? 'selected' : '' }}>Select</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label>Validation Type</label>
                                            @php
                                                $validationType =
                                                    $field->required || !empty($field->validation)
                                                        ? 'validation'
                                                        : 'nullable';
                                            @endphp
                                            <select name="fields[{{ $index }}][validation_type]"
                                                class="form-control validation-type">
                                                <option value="nullable"
                                                    {{ $validationType == 'nullable' ? 'selected' : '' }}>Nullable (No
                                                    Validation)</option>
                                                <option value="validation"
                                                    {{ $validationType == 'validation' ? 'selected' : '' }}>With
                                                    Validation</option>
                                            </select>
                                        </div>
                                        <div
                                            class="col-2 validation-rules {{ $validationType == 'validation' ? '' : 'd-none' }}">
                                            <label>Validation Rules</label>
                                            @php
                                                $currentRules = $field->validation
                                                    ? explode('|', $field->validation)
                                                    : [];
                                                // Filter out type-specific rules that are auto-added
                                                $displayRules = array_filter($currentRules, function ($rule) use (
                                                    $field,
                                                ) {
                                                    if (in_array($rule, ['string', 'email', 'numeric', 'integer'])) {
                                                        // Only show if not auto-added by type
                                                        if ($field->type == 'email' && $rule == 'email') {
                                                            return false;
                                                        }
                                                        if ($field->type == 'number' && $rule == 'numeric') {
                                                            return false;
                                                        }
                                                        if (
                                                            in_array($field->type, ['text', 'textarea']) &&
                                                            $rule == 'string'
                                                        ) {
                                                            return false;
                                                        }
                                                    }
                                                    return $rule != 'nullable';
                                                });
                                            @endphp
                                            <select name="fields[{{ $index }}][validation_rules][]"
                                                class="form-control validation-rules-select"
                                                aria-label="Default select example" multiple>
                                                <option value="required"
                                                    {{ in_array('required', $currentRules) ? 'selected' : '' }}>
                                                    Required</option>
                                                <option value="string"
                                                    {{ in_array('string', $displayRules) ? 'selected' : '' }}>String
                                                </option>
                                                <option value="email"
                                                    {{ in_array('email', $displayRules) ? 'selected' : '' }}>Email
                                                </option>
                                                <option value="numeric"
                                                    {{ in_array('numeric', $displayRules) ? 'selected' : '' }}>Numeric
                                                </option>
                                                <option value="integer"
                                                    {{ in_array('integer', $displayRules) ? 'selected' : '' }}>Integer
                                                </option>
                                                <option value="max:255"
                                                    {{ in_array('max:255', $currentRules) ? 'selected' : '' }}>Max 255
                                                    Characters</option>
                                                <option value="min:3"
                                                    {{ in_array('min:3', $currentRules) ? 'selected' : '' }}>Min 3
                                                    Characters</option>
                                                <option value="max:500"
                                                    {{ in_array('max:500', $currentRules) ? 'selected' : '' }}>Max 500
                                                    Characters</option>
                                                <option value="url"
                                                    {{ in_array('url', $currentRules) ? 'selected' : '' }}>URL</option>
                                                <option value="date"
                                                    {{ in_array('date', $currentRules) ? 'selected' : '' }}>Date
                                                </option>
                                                <option value="regex:/^[A-Za-z\s]+$/"
                                                    {{ in_array('regex:/^[A-Za-z\s]+$/', $currentRules) ? 'selected' : '' }}>
                                                    Alphabets Only</option>
                                                <option value="regex:/^[0-9]+$/"
                                                    {{ in_array('regex:/^[0-9]+$/', $currentRules) ? 'selected' : '' }}>
                                                    Numbers Only</option>
                                                <option value="regex:/^[A-Za-z0-9\s]+$/"
                                                    {{ in_array('regex:/^[A-Za-z0-9\s]+$/', $currentRules) ? 'selected' : '' }}>
                                                    Alphanumeric</option>
                                            </select>
                                            <small class="text-muted">Hold Ctrl/Cmd to select multiple rules</small>
                                        </div>
                                        <div class="col-2 d-flex gap-2 align-items-end">
                                            <button type="button" class="btn btn-success btn-add">+</button>
                                            <button type="button" class="btn btn-danger btn-remove">−</button>
                                        </div>
                                    </div>
                                    <div
                                        class="row mt-2 select-options {{ $field->type == 'select' ? '' : 'd-none' }}">
                                        <div class="col-9">
                                            @if ($field->type == 'select' && !empty($field->options))
                                                @foreach ($field->options as $optionIndex => $optionValue)
                                                    <div class="option-row d-flex align-items-center mb-2">
                                                        <input type="text"
                                                            name="fields[{{ $index }}][options][{{ $optionIndex }}]"
                                                            class="form-control me-2" placeholder="Option value"
                                                            value="{{ $optionValue }}">
                                                        <button type="button"
                                                            class="btn btn-success btn-option-add me-1"
                                                            title="Add option">
                                                            +
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-danger btn-option-remove"
                                                            title="Remove option">
                                                            −
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="option-row d-flex align-items-center mb-2">
                                                    <input type="text"
                                                        name="fields[{{ $index }}][options][0]"
                                                        class="form-control me-2" placeholder="Option value">
                                                    <button type="button" class="btn btn-success btn-option-add me-1"
                                                        title="Add option">
                                                        +
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-option-remove"
                                                        title="Remove option">
                                                        −
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">Update Form</button>
                            <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>
    </div>
</div>
<script>
    let fieldIndex = {{ $form->fields->count() }};

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize validation rules visibility on page load
        document.querySelectorAll('.validation-type').forEach(select => {
            toggleValidationRules(select);
        });

        // Initialize field type visibility on page load
        document.querySelectorAll('.field-type').forEach(select => {
            toggleSelectOptions(select);
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('field-type')) {
            toggleSelectOptions(e.target);
        }

        if (e.target.classList.contains('validation-type')) {
            toggleValidationRules(e.target);
        }
    });

    function toggleSelectOptions(selectElement) {
        const group = selectElement.closest('.field-group');
        const options = group.querySelector('.select-options');
        if (selectElement.value === 'select') {
            options.classList.remove('d-none');
        } else {
            options.classList.add('d-none');
        }
    }

    function toggleValidationRules(selectElement) {
        const group = selectElement.closest('.field-group');
        const validationRules = group.querySelector('.validation-rules');

        if (selectElement.value === 'validation') {
            validationRules.classList.remove('d-none');
            validationRules.style.display = 'block';
        } else {
            validationRules.classList.add('d-none');
            validationRules.style.display = 'none';
            // Clear selected rules when switching to nullable
            const rulesSelect = validationRules.querySelector('.validation-rules-select');
            if (rulesSelect) {
                rulesSelect.selectedIndex = -1;
            }
        }
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add')) {
            const wrapper = document.getElementById('fields-wrapper');
            const fieldGroups = document.querySelectorAll('.field-group');
            const lastGroup = fieldGroups[fieldGroups.length - 1];
            const clone = lastGroup.cloneNode(true);

            // Remove the ID field for new entries
            clone.querySelector('input[type="hidden"]')?.remove();

            // Update all names with new index
            clone.querySelectorAll('input, select').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace(/\[\d+\]/g, `[${fieldIndex}]`));
                }
            });

            // Reset values
            clone.querySelectorAll('input[type="text"]:not([name*="[id]"])').forEach(el => el.value = '');

            // Reset field type
            const fieldTypeSelect = clone.querySelector('.field-type');
            if (fieldTypeSelect) {
                fieldTypeSelect.selectedIndex = 0;
            }

            // Reset validation type to nullable
            const validationTypeSelect = clone.querySelector('.validation-type');
            if (validationTypeSelect) {
                validationTypeSelect.selectedIndex = 0;
            }

            // Reset validation rules
            const validationRulesSelect = clone.querySelector('.validation-rules-select');
            if (validationRulesSelect) {
                validationRulesSelect.selectedIndex = -1;
            }

            // Hide options and validation rules by default
            clone.querySelector('.select-options').classList.add('d-none');
            clone.querySelector('.validation-rules').classList.add('d-none');
            clone.querySelector('.validation-rules').style.display = 'none';

            // Clear select options except first one
            const optionRows = clone.querySelectorAll('.option-row');
            optionRows.forEach((row, index) => {
                if (index > 0) {
                    row.remove();
                } else {
                    row.querySelector('input').value = '';
                }
            });

            wrapper.appendChild(clone);
            fieldIndex++;
        }

        if (e.target.classList.contains('btn-remove')) {
            const groups = document.querySelectorAll('.field-group');
            if (groups.length > 1) {
                e.target.closest('.field-group').remove();
                // Reindex remaining fields
                reindexFields();
            }
        }

        if (e.target.classList.contains('btn-option-add')) {
            const row = e.target.closest('.option-row');
            const parentDiv = row.parentNode;
            const fieldGroup = row.closest('.field-group');
            const fieldNameInput = fieldGroup.querySelector('input[name*="[label]"]');
            const fieldName = fieldNameInput ? fieldNameInput.getAttribute('name') : '';
            const fieldIndexMatch = fieldName.match(/\[(\d+)\]/);
            const currentIndex = fieldIndexMatch ? fieldIndexMatch[1] : fieldIndex;

            const clone = row.cloneNode(true);
            clone.querySelector('input').value = '';

            // Update the name attribute for the new option
            const optionInput = clone.querySelector('input[name*="[options]"]');
            const currentName = optionInput.getAttribute('name');
            const newName = currentName.replace(/\[\d+\]\[\d+\]/, `[${currentIndex}][${Date.now()}]`);
            optionInput.setAttribute('name', newName);

            parentDiv.appendChild(clone);
        }

        if (e.target.classList.contains('btn-option-remove')) {
            const rows = e.target.closest('.select-options').querySelectorAll('.option-row');
            if (rows.length > 1) {
                e.target.closest('.option-row').remove();
            }
        }
    });

    function reindexFields() {
        const groups = document.querySelectorAll('.field-group');
        groups.forEach((group, index) => {
            // Update field ID if exists
            const hiddenIdInput = group.querySelector('input[type="hidden"]');
            if (hiddenIdInput) {
                hiddenIdInput.setAttribute('name', `fields[${index}][id]`);
            }

            // Update all other inputs
            group.querySelectorAll('input:not([type="hidden"]), select').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/g, `[${index}]`);
                    el.setAttribute('name', newName);
                }
            });
        });
    }
</script>
@endsection
