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
                    <form method="POST" action="{{ route('forms.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label>Select User</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- Select User --</option>
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
                            <div class="field-group mb-3">
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
                                        <select name="fields[0][validation_rules][]" class="form-control validation-rules-select" multiple>
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
                                <div class="row mt-2 select-options d-none">
                                    <div class="col-11">
                                        <div class="option-row d-flex align-items-center mb-2">
                                            <input type="text" name="fields[0][options][]" class="form-control me-2"
                                                placeholder="Option value">
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
    let fieldIndex = 1;
    
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('field-type')) {
            const group = e.target.closest('.field-group');
            const options = group.querySelector('.select-options');
            if (e.target.value === 'select') {
                options.classList.remove('d-none');
            } else {
                options.classList.add('d-none');
            }
        }
        
        if (e.target.classList.contains('validation-type')) {
            const group = e.target.closest('.field-group');
            const validationRules = group.querySelector('.validation-rules');
            
            if (e.target.value === 'validation') {
                validationRules.classList.remove('d-none');
            } else {
                validationRules.classList.add('d-none');
                // Clear selected rules when switching to nullable
                const rulesSelect = validationRules.querySelector('.validation-rules-select');
                rulesSelect.selectedIndex = -1;
            }
        }
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-add')) {
            const wrapper = document.getElementById('fields-wrapper');
            const firstGroup = document.querySelector('.field-group');
            const clone = firstGroup.cloneNode(true);
            
            // Update all names with new index
            clone.querySelectorAll('input, select').forEach(el => {
                const name = el.getAttribute('name');
                if (name) {
                    el.setAttribute('name', name.replace('[0]', `[${fieldIndex}]`));
                }
            });
            
            // Reset values
            clone.querySelectorAll('input[type="text"]').forEach(el => el.value = '');
            clone.querySelectorAll('select').forEach(el => {
                if (el.classList.contains('field-type') || el.classList.contains('validation-type')) {
                    el.selectedIndex = 0;
                } else if (el.classList.contains('validation-rules-select')) {
                    el.selectedIndex = -1;
                }
            });
            
            // Hide options and validation rules by default
            clone.querySelector('.select-options').classList.add('d-none');
            clone.querySelector('.validation-rules').classList.add('d-none');
            
            wrapper.appendChild(clone);
            fieldIndex++;
        }
        
        if (e.target.classList.contains('btn-remove')) {
            const groups = document.querySelectorAll('.field-group');
            if (groups.length > 1) {
                e.target.closest('.field-group').remove();
            }
        }
        
        if (e.target.classList.contains('btn-option-add')) {
            const row = e.target.closest('.option-row');
            const parentDiv = row.parentNode;
            const fieldGroup = row.closest('.field-group');
            const fieldName = fieldGroup.querySelector('input[name*="[label]"]').getAttribute('name');
            const fieldIndexMatch = fieldName.match(/\[(\d+)\]/);
            const currentIndex = fieldIndexMatch ? fieldIndexMatch[1] : '0';
            
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
</script>

@endsection