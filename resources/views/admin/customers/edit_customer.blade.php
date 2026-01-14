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
                    <form method="POST" action="{{ route('forms.update', $form->id) }}" enctype="multipart/form-data"
                        id="edit-form">
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
                                <div class="field-group mb-3 border p-3 rounded">
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
                                                <option value="checkbox"
                                                    {{ $field->type == 'checkbox' ? 'selected' : '' }}>Checkbox
                                                </option>
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
                                                class="form-control validation-rules-select" multiple>
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

                                    <!-- Data Source Section (only for select type) -->
                                    <div
                                        class="row mt-2 data-source-section {{ $field->type == 'select' ? '' : 'd-none' }}">
                                        <div class="col-12">
                                            <label class="form-label mb-2">Data Source:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input data-source-radio" type="radio"
                                                    name="fields[{{ $index }}][data_source]" value="manual"
                                                    id="field{{ $index }}_data_manual"
                                                    {{ empty($field->data_source) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="field{{ $index }}_data_manual">
                                                    Custom
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input data-source-radio" type="radio"
                                                    name="fields[{{ $index }}][data_source]" value="database"
                                                    id="field{{ $index }}_data_database"
                                                    {{ !empty($field->data_source) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="field{{ $index }}_data_database">
                                                    Dynamic
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Database Source Selection -->
                                    <div
                                        class="row mt-2 database-source-section {{ $field->type == 'select' && !empty($field->data_source) ? '' : 'd-none' }}">
                                        <div class="col-4">
                                            <label>Select Database Source</label>
                                            <select name="fields[{{ $index }}][data_source_select]"
                                                class="form-control database-source-select">
                                                <option value="">-- Select Source --</option>
                                                <option value="tagging"
                                                    {{ $field->data_source == 'tagging' ? 'selected' : '' }}>Tagging
                                                </option>
                                                <option value="brand"
                                                    {{ $field->data_source == 'brand' ? 'selected' : '' }}>Brand
                                                </option>
                                                <option value="usecases"
                                                    {{ $field->data_source == 'usecases' ? 'selected' : '' }}>Use Cases
                                                </option>
                                            </select>
                                            <small class="text-muted">Data will be fetched from this table when form is
                                                displayed to users</small>
                                        </div>
                                    </div>

                                    <!-- Select Options (for select type - manual/custom) -->
                                    <div
                                        class="row mt-2 select-options {{ $field->type == 'select' && empty($field->data_source) ? '' : 'd-none' }}">
                                        <div class="col-12">
                                            <label class="form-label mb-2">Custom Options:</label>
                                            @php
                                                $options = $field->options ? json_decode($field->options, true) : [];
                                            @endphp
                                            <div class="options-container">
                                                @if (!empty($options))
                                                    @foreach ($options as $optionValue)
                                                        <div class="option-row d-flex align-items-center mb-2">
                                                            <input type="text"
                                                                name="fields[{{ $index }}][options][]"
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
                                                            name="fields[{{ $index }}][options][]"
                                                            class="form-control me-2" placeholder="Option value">
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
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Checkbox Terms/Description -->
                                    <div
                                        class="row mt-2 checkbox-terms-section {{ $field->type == 'checkbox' ? '' : 'd-none' }}">
                                        <div class="col-12">
                                            <label class="form-label mb-2">Checkbox Terms/Description:</label>
                                            <textarea name="fields[{{ $index }}][checkbox_terms]" class="form-control" rows="4"
                                                placeholder="Enter terms and conditions or description for the checkbox. Example: I agree to all the terms and conditions and privacy policy of this website...">{{ $field->checkbox_terms }}</textarea>
                                            <small class="text-muted">This text will appear next to the checkbox as its
                                                label/description.</small>
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
        // Initialize all field sections on page load
        document.querySelectorAll('.field-group').forEach(group => {
            initializeFieldGroup(group);
        });
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('field-type')) {
            handleFieldTypeChange(e.target);
        }

        if (e.target.classList.contains('validation-type')) {
            handleValidationTypeChange(e.target);
        }

        if (e.target.classList.contains('data-source-radio')) {
            handleDataSourceRadioChange(e.target);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add')) {
            addNewField();
        }

        if (e.target.classList.contains('btn-remove')) {
            removeField(e.target);
        }

        if (e.target.classList.contains('btn-option-add')) {
            addOption(e.target);
        }

        if (e.target.classList.contains('btn-option-remove')) {
            removeOption(e.target);
        }
    });

    // Helper functions
    function initializeFieldGroup(group) {
        const fieldType = group.querySelector('.field-type').value;
        
        // Show/hide sections based on field type
        toggleFieldSections(group, fieldType);
        
        // Initialize validation rules visibility
        const validationType = group.querySelector('.validation-type').value;
        toggleValidationRules(group, validationType);
        
        // Initialize data source based on current selection
        const dataSourceRadio = group.querySelector('.data-source-radio:checked');
        if (dataSourceRadio) {
            toggleDataSource(group, dataSourceRadio.value);
        }
        
        // For checkbox type, explicitly hide data source and select options sections
        if (fieldType === 'checkbox') {
            group.querySelector('.data-source-section').classList.add('d-none');
            group.querySelector('.select-options').classList.add('d-none');
        }
        
        // If it's a select field with dynamic source, make sure database section is shown
        if (fieldType === 'select') {
            const databaseRadio = group.querySelector('.data-source-radio[value="database"]');
            if (databaseRadio && databaseRadio.checked) {
                // Show database section if dynamic is selected
                const databaseSection = group.querySelector('.database-source-section');
                databaseSection.classList.remove('d-none');
                
                // Hide custom options
                const selectOptions = group.querySelector('.select-options');
                selectOptions.classList.add('d-none');
            }
        }
    }

    function handleFieldTypeChange(selectElement) {
        const group = selectElement.closest('.field-group');
        const fieldType = selectElement.value;
        toggleFieldSections(group, fieldType);
        
        // If changing to select, default to manual
        if (fieldType === 'select') {
            const manualRadio = group.querySelector('.data-source-radio[value="manual"]');
            if (manualRadio) {
                manualRadio.checked = true;
                toggleDataSource(group, 'manual');
            }
        }
    }

    function handleValidationTypeChange(selectElement) {
        const group = selectElement.closest('.field-group');
        toggleValidationRules(group, selectElement.value);
    }

    function handleDataSourceRadioChange(radioElement) {
        const group = radioElement.closest('.field-group');
        toggleDataSource(group, radioElement.value);
    }

    function toggleFieldSections(group, fieldType) {
        // Hide all optional sections first
        group.querySelector('.data-source-section').classList.add('d-none');
        group.querySelector('.database-source-section').classList.add('d-none');
        group.querySelector('.select-options').classList.add('d-none');
        group.querySelector('.checkbox-terms-section').classList.add('d-none');

        if (fieldType === 'select') {
            // Show data source section for select type
            group.querySelector('.data-source-section').classList.remove('d-none');
            
            // Check existing selection
            const databaseRadio = group.querySelector('.data-source-radio[value="database"]');
            const manualRadio = group.querySelector('.data-source-radio[value="manual"]');
            
            if (databaseRadio && databaseRadio.checked) {
                // Show database section if dynamic is selected
                group.querySelector('.database-source-section').classList.remove('d-none');
                group.querySelector('.select-options').classList.add('d-none');
            } else if (manualRadio && manualRadio.checked) {
                // Show custom options if manual is selected
                group.querySelector('.database-source-section').classList.add('d-none');
                group.querySelector('.select-options').classList.remove('d-none');
            }
            
        } else if (fieldType === 'checkbox') {
            // For checkbox, only show the terms/description section
            group.querySelector('.checkbox-terms-section').classList.remove('d-none');
            // Make sure select options is hidden for checkbox
            group.querySelector('.select-options').classList.add('d-none');
            // Also hide data source section for checkbox
            group.querySelector('.data-source-section').classList.add('d-none');
        }
    }

    function toggleValidationRules(group, validationType) {
        const validationRules = group.querySelector('.validation-rules');
        
        if (validationType === 'validation') {
            validationRules.classList.remove('d-none');
        } else {
            validationRules.classList.add('d-none');
            const rulesSelect = validationRules.querySelector('.validation-rules-select');
            if (rulesSelect) {
                // Clear selected options
                Array.from(rulesSelect.options).forEach(option => {
                    option.selected = false;
                });
            }
        }
    }

    function toggleDataSource(group, dataSourceValue) {
        const databaseSection = group.querySelector('.database-source-section');
        const selectOptions = group.querySelector('.select-options');
        
        if (dataSourceValue === 'database') {
            databaseSection.classList.remove('d-none');
            selectOptions.classList.add('d-none');
        } else {
            databaseSection.classList.add('d-none');
            selectOptions.classList.remove('d-none');
        }
    }

    function addNewField() {
        const wrapper = document.getElementById('fields-wrapper');
        const firstGroup = document.querySelector('.field-group');
        const clone = firstGroup.cloneNode(true);

        // Remove the ID field for new entries
        const hiddenIdInput = clone.querySelector('input[type="hidden"][name*="[id]"]');
        if (hiddenIdInput) {
            hiddenIdInput.remove();
        }

        // Update all names with new index
        clone.querySelectorAll('input, select, textarea').forEach(el => {
            const name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\[\d+\]/g, `[${fieldIndex}]`));
            }
        });

        // Update IDs for radio buttons
        clone.querySelectorAll('[id*="field"]').forEach(el => {
            const id = el.getAttribute('id');
            if (id) {
                el.setAttribute('id', id.replace(/field\d+/g, `field${fieldIndex}`));
            }
        });

        // Update radio button labels
        clone.querySelectorAll('label[for*="field"]').forEach(label => {
            const forAttr = label.getAttribute('for');
            if (forAttr) {
                label.setAttribute('for', forAttr.replace(/field\d+/g, `field${fieldIndex}`));
            }
        });

        // Reset values
        clone.querySelectorAll('input[type="text"]:not([name*="[id]"])').forEach(el => el.value = '');
        clone.querySelectorAll('textarea').forEach(el => el.value = '');

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
            Array.from(validationRulesSelect.options).forEach(option => {
                option.selected = false;
            });
        }

        // Reset radio buttons to manual
        clone.querySelectorAll('.data-source-radio').forEach(radio => {
            radio.checked = radio.value === 'manual';
        });

        // Reset database source select
        const databaseSelect = clone.querySelector('.database-source-select');
        if (databaseSelect) {
            databaseSelect.selectedIndex = 0;
        }

        // Clear select options except first one
        const selectOptionsDiv = clone.querySelector('.select-options');
        if (selectOptionsDiv) {
            const optionsContainer = selectOptionsDiv.querySelector('.options-container');
            const optionRows = optionsContainer.querySelectorAll('.option-row');
            if (optionRows.length > 1) {
                for (let i = 1; i < optionRows.length; i++) {
                    optionRows[i].remove();
                }
            }
            if (optionRows.length > 0) {
                optionRows[0].querySelector('input').value = '';
            }
        }

        // Initialize the new field with default values
        toggleFieldSections(clone, 'text');
        toggleValidationRules(clone, 'nullable');
        
        // Make sure data source section is hidden for non-select fields
        const currentFieldType = clone.querySelector('.field-type').value;
        if (currentFieldType !== 'select') {
            clone.querySelector('.data-source-section').classList.add('d-none');
        }

        wrapper.appendChild(clone);
        fieldIndex++;
    }

    function removeField(button) {
        const groups = document.querySelectorAll('.field-group');
        if (groups.length > 1) {
            button.closest('.field-group').remove();
            // Reindex remaining fields
            reindexFields();
            // Update fieldIndex to match new count
            fieldIndex = document.querySelectorAll('.field-group').length;
        }
    }

    function addOption(button) {
        const row = button.closest('.option-row');
        const optionsContainer = row.closest('.options-container');
        const clone = row.cloneNode(true);
        clone.querySelector('input').value = '';
        
        optionsContainer.appendChild(clone);
    }

    function removeOption(button) {
        const optionsContainer = button.closest('.options-container');
        const rows = optionsContainer.querySelectorAll('.option-row');
        if (rows.length > 1) {
            button.closest('.option-row').remove();
        }
    }

    function reindexFields() {
        const groups = document.querySelectorAll('.field-group');
        groups.forEach((group, index) => {
            // Update field ID if exists
            const hiddenIdInput = group.querySelector('input[type="hidden"][name*="[id]"]');
            if (hiddenIdInput) {
                hiddenIdInput.setAttribute('name', `fields[${index}][id]`);
            }

            // Update all other inputs
            group.querySelectorAll('input, select, textarea').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/g, `[${index}]`);
                    el.setAttribute('name', newName);
                }
            });

            // Update IDs for radio buttons
            group.querySelectorAll('[id*="field"]').forEach(el => {
                const id = el.getAttribute('id');
                if (id) {
                    const newId = id.replace(/field\d+/g, `field${index}`);
                    el.setAttribute('id', newId);
                }
            });

            // Update radio button labels
            group.querySelectorAll('label[for*="field"]').forEach(label => {
                const forAttr = label.getAttribute('for');
                if (forAttr) {
                    const newFor = forAttr.replace(/field\d+/g, `field${index}`);
                    label.setAttribute('for', newFor);
                }
            });
        });
    }
</script>
@endsection
