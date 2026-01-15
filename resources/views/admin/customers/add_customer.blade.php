@extends('admin.partials.layouts')
@section('content')
@section('title', 'Add Customer')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Add Customer Form</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Add Customer Form</li>
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
                            <h4 class="card-title">Customer Form</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('forms.store') }}" enctype="multipart/form-data" id="dynamic-form">
                        @csrf
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label>Select Branch</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label>Form Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter form title"
                                    required>
                            </div>
                            <div class="col-4 mb-3">
                                <label>Form Logo</label>
                                <input type="file" name="logo" class="form-control">
                            </div>
                        </div>
                        <div id="fields-wrapper">
                            <div class="field-group mb-1 p-2">
                                <div class="row align-items-end">
                                    <div class="col-3">
                                        <label>Field Label</label>
                                        <input type="text" name="fields[0][label]" class="form-control" required>
                                    </div>
                                    <div class="col-2">
                                        <label>Field Type</label>
                                        <select name="fields[0][type]" class="form-control field-type">
                                            <option value="text">Text</option>
                                            <option value="email">Email</option>
                                            <option value="number">Number</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="select">Select</option>
                                            <option value="checkbox">Checkbox</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label>Validation Type</label>
                                        <select name="fields[0][validation_type]" class="form-control validation-type">
                                            <option value="nullable">Nullable (No Validation)</option>
                                            <option value="validation">With Validation</option>
                                        </select>
                                    </div>
                                    <div class="col-2 validation-rules d-none">
                                        <label>Validation Rules</label>
                                        <select name="fields[0][validation_rules][]"
                                            class="form-control validation-rules-select" multiple>
                                            <option value="required">Required</option>
                                            <option value="string">String</option>
                                            <option value="email">Email</option>
                                            <option value="numeric">Numeric</option>
                                            <option value="integer">Integer</option>
                                            <option value="max:255">Max 255 Characters</option>
                                            <option value="min:3">Min 3 Characters</option>
                                            <option value="max:500">Max 500 Characters</option>
                                            <option value="url">URL</option>
                                            <option value="date">Date</option>
                                            <option value="regex:/^[A-Za-z\s]+$/">Alphabets Only</option>
                                            <option value="regex:/^[0-9]+$/">Numbers Only</option>
                                            <option value="regex:/^[A-Za-z0-9\s]+$/">Alphanumeric</option>
                                        </select>
                                        <small class="text-muted">Hold Ctrl/Cmd to select multiple rules</small>
                                    </div>
                                    <div class="col-2 d-flex gap-2 align-items-end">
                                        <button type="button" class="btn btn-success btn-add">+</button>
                                        <button type="button" class="btn btn-danger btn-remove">−</button>
                                    </div>
                                </div>
                                <div class="row mt-2 data-source-section d-none">
                                    <div class="col-12">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input data-source-radio" type="radio"
                                                name="fields[0][data_source]" id="field0_data_manual" value="manual"
                                                checked>
                                            <label class="form-check-label" for="field0_data_manual">
                                                Custom
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input data-source-radio" type="radio"
                                                name="fields[0][data_source]" id="field0_data_database"
                                                value="database">
                                            <label class="form-check-label" for="field0_data_database">
                                                Dynamic
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2 database-source-section d-none">
                                    <div class="col-4">
                                        <label>Select Dynamic</label>
                                        <select name="fields[0][data_source_select]"
                                            class="form-control database-source-select">
                                            <option value="">Select Source</option>
                                            <option value="tagging">Tagging</option>
                                            <option value="brand">Brand</option>
                                            <option value="usecases">Use Cases</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2 select-options d-none">
                                    <div class="col-12">
                                        <label class="form-label mb-2">Custom Options:</label>
                                        <div class="option-row d-flex align-items-center mb-2">
                                            <input type="text" name="fields[0][options][]"
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
                                    </div>
                                </div>
                                <div class="row mt-2 checkbox-terms-section d-none">
                                    <div class="col-12">
                                        <label class="form-label mb-2">Checkbox Terms/Description:</label>
                                        <textarea name="fields[0][checkbox_terms]" class="form-control" rows="4"
                                            placeholder="Enter terms and conditions or description for the checkbox. Example: I agree to all the terms and conditions and privacy policy of this website..."></textarea>
                                        <small class="text-muted">This text will appear next to the checkbox as its
                                            label/description.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save Form</button>
                    </form>
                </div><!--end card-body-->
            </div><!--end card-->
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let fieldIndex = 1;
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('field-type')) {
                const group = e.target.closest('.field-group');
                toggleFieldSections(group, e.target.value);
            }
            if (e.target.classList.contains('validation-type')) {
                const group = e.target.closest('.field-group');
                const validationRules = group.querySelector('.validation-rules');
                if (e.target.value === 'validation') {
                    validationRules.classList.remove('d-none');
                } else {
                    validationRules.classList.add('d-none');
                    const rulesSelect = validationRules.querySelector('.validation-rules-select');
                    if (rulesSelect) {
                        Array.from(rulesSelect.options).forEach(option => {
                            option.selected = false;
                        });
                    }
                }
            }
            if (e.target.classList.contains('data-source-radio')) {
                const group = e.target.closest('.field-group');
                toggleDataSource(group, e.target.value);
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
        document.getElementById('dynamic-form').addEventListener('submit', function(e) {
            console.log('Form submitted, checking data...');
            const formData = new FormData(this);
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
        });
        function toggleFieldSections(group, fieldType) {
            group.querySelector('.data-source-section').classList.add('d-none');
            group.querySelector('.database-source-section').classList.add('d-none');
            group.querySelector('.select-options').classList.add('d-none');
            group.querySelector('.checkbox-terms-section').classList.add('d-none');
            
            // Reset radio buttons for select
            if (group.querySelector('.data-source-radio[value="manual"]')) {
                group.querySelector('.data-source-radio[value="manual"]').checked = true;
            }
            if (fieldType === 'select') {
                group.querySelector('.data-source-section').classList.remove('d-none');
                group.querySelector('.select-options').classList.remove('d-none');
            } else if (fieldType === 'checkbox') {
                group.querySelector('.checkbox-terms-section').classList.remove('d-none');
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
            clone.querySelectorAll('input, select, textarea').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace('[0]', `[${fieldIndex}]`));
                }
                const id = el.getAttribute('id');
                if (id && id.includes('field0')) {
                    el.setAttribute('id', id.replace('field0', `field${fieldIndex}`));
                }
            });
            clone.querySelectorAll('label[for*="field0"]').forEach(label => {
                const forAttr = label.getAttribute('for');
                if (forAttr) {
                    label.setAttribute('for', forAttr.replace('field0', `field${fieldIndex}`));
                }
            });
            clone.querySelectorAll('input[type="text"]').forEach(el => el.value = '');
            clone.querySelectorAll('textarea').forEach(el => el.value = '');
            clone.querySelectorAll('input[type="radio"]').forEach(el => {
                el.checked = el.value === 'manual';
            });
            clone.querySelectorAll('select').forEach(el => {
                if (el.classList.contains('field-type')) {
                    el.selectedIndex = 0; // "text"
                } else if (el.classList.contains('validation-type')) {
                    el.selectedIndex = 0; // "nullable"
                } else if (el.classList.contains('database-source-select')) {
                    el.selectedIndex = 0;
                } else if (el.classList.contains('validation-rules-select')) {
                    Array.from(el.options).forEach(option => {
                        option.selected = false;
                    });
                }
            });
            const selectOptionsDiv = clone.querySelector('.select-options');
            if (selectOptionsDiv) {
                const optionRows = selectOptionsDiv.querySelectorAll('.option-row');
                if (optionRows.length > 1) {
                    for (let i = 1; i < optionRows.length; i++) {
                        optionRows[i].remove();
                    }
                }
                if (optionRows.length > 0) {
                    optionRows[0].querySelector('input').value = '';
                }
            }
            const validationRules = clone.querySelector('.validation-rules');
            if (validationRules) {
                validationRules.classList.add('d-none');
            }
            const databaseSourceSection = clone.querySelector('.database-source-section');
            if (databaseSourceSection) {
                databaseSourceSection.classList.add('d-none');
            }
            const dataSourceSection = clone.querySelector('.data-source-section');
            if (dataSourceSection) {
                dataSourceSection.classList.add('d-none');
            }
            const checkboxTermsSection = clone.querySelector('.checkbox-terms-section');
            if (checkboxTermsSection) {
                checkboxTermsSection.classList.add('d-none');
            }
            const selectOptionsSection = clone.querySelector('.select-options');
            if (selectOptionsSection) {
                selectOptionsSection.classList.add('d-none');
            }
            toggleFieldSections(clone, 'text');
            wrapper.appendChild(clone);
            fieldIndex++;
        }
        function removeField(button) {
            const groups = document.querySelectorAll('.field-group');
            if (groups.length > 1) {
                button.closest('.field-group').remove();
                reindexFields();
            }
        }
        function addOption(button) {
            const row = button.closest('.option-row');
            const parentDiv = row.parentNode;
            const fieldGroup = row.closest('.field-group');
            const fieldName = fieldGroup.querySelector('input[name*="[label]"]').getAttribute('name');
            const fieldIndexMatch = fieldName.match(/\[(\d+)\]/);
            const currentIndex = fieldIndexMatch ? fieldIndexMatch[1] : '0';
            const clone = row.cloneNode(true);
            clone.querySelector('input').value = '';
            const optionInput = clone.querySelector('input[name*="[options]"]');
            const currentName = optionInput.getAttribute('name');
            const newName = currentName.replace(/\[\d+\]\[\d+\]/, `[${currentIndex}][${Date.now()}]`);
            optionInput.setAttribute('name', newName);
            parentDiv.appendChild(clone);
        }
        function removeOption(button) {
            const selectOptionsDiv = button.closest('.select-options');
            if (selectOptionsDiv) {
                const rows = selectOptionsDiv.querySelectorAll('.option-row');
                if (rows.length > 1) {
                    button.closest('.option-row').remove();
                }
            }
        }
        function reindexFields() {
            const groups = document.querySelectorAll('.field-group');
            fieldIndex = 0;
            groups.forEach((group, index) => {
                group.querySelectorAll('input, select, textarea').forEach(el => {
                    const name = el.getAttribute('name');
                    if (name) {
                        const match = name.match(/fields\[(\d+)\]/);
                        if (match) {
                            const oldIndex = match[1];
                            const newName = name.replace(`[${oldIndex}]`, `[${index}]`);
                            el.setAttribute('name', newName);
                        }
                    }
                    const id = el.getAttribute('id');
                    if (id && id.includes('field')) {
                        const newId = id.replace(/field\d+/, `field${index}`);
                        el.setAttribute('id', newId);
                    }
                });
                group.querySelectorAll('label[for*="field"]').forEach(label => {
                    const forAttr = label.getAttribute('for');
                    if (forAttr) {
                        const newFor = forAttr.replace(/field\d+/, `field${index}`);
                        label.setAttribute('for', newFor);
                    }
                });
                fieldIndex++;
            });
        }
        const firstField = document.querySelector('.field-group');
        if (firstField) {
            toggleFieldSections(firstField, 'text');
            // Make sure first field starts with nullable validation type
            const validationTypeSelect = firstField.querySelector('.validation-type');
            if (validationTypeSelect) {
                validationTypeSelect.selectedIndex = 0; // "nullable"
            }
            const validationRules = firstField.querySelector('.validation-rules');
            if (validationRules) {
                validationRules.classList.add('d-none');
            }
        }
    });
</script>
@endsection