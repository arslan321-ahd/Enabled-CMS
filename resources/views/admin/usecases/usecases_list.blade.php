@extends('admin.partials.layouts')
@section('content')
@section('title', 'Use Cases')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Use Cases List</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a></li>
                        <li class="breadcrumb-item active">Use Cases List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Use Cases</h4>
                        </div>
                        <div class="col-auto">
                            <form class="row g-2" id="useCaseFilterForm">
                                <div class="col-auto">
                                    <a class="btn bg-primary-subtle text-primary dropdown-toggle d-flex align-items-center arrow-none"
                                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                        aria-expanded="false" data-bs-auto-close="outside">
                                        <i class="iconoir-filter-alt me-1"></i> Filter
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <div class="p-2">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="use-case-filter" value="all"
                                                    id="filter-all"
                                                    {{ empty(request('status')) ? 'checked' : (in_array('all', (array) request('status', [])) ? 'checked' : '') }}>
                                                <label class="form-check-label" for="filter-all">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="use-case-filter" value="active"
                                                    id="filter-two"
                                                    {{ in_array('active', (array) request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="filter-two">
                                                    Active
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="use-case-filter" value="inactive"
                                                    id="filter-three"
                                                    {{ in_array('inactive', (array) request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="filter-three">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUseCaseModal">
                                        <i class="fa-solid fa-plus me-1"></i> Add Use Case
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="datatable_1">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-0" style="width: 16px;">ID</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($useCases as $index => $useCase)
                                    <tr>
                                        <td style="width: 16px;">{{ $index + 1 }}</td>
                                        <td>{{ $useCase->name }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($useCase->brand->logo)
                                                    <img src="{{ asset('storage/' . $useCase->brand->logo) }}"
                                                        width="32" height="32" class="rounded-circle"
                                                        alt="logo">
                                                @endif
                                                <span>{{ $useCase->brand->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($useCase->status === 'active')
                                                <span class="badge bg-success-subtle text-success px-2">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $useCase->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <button type="button" data-id="{{ $useCase->id }}"
                                                class="btn btn-sm btn-primary btn-edit-use-case"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" data-id="{{ $useCase->id }}"
                                                class="btn btn-sm btn-danger btn-delete-use-case"
                                                data-bs-toggle="tooltip" title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No use cases found. Add your first use case!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Use Case Modal -->
<div class="modal fade" id="addUseCaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark">Add Use Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUseCaseForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Brand <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-select" required>
                            <option value="" disabled selected>Select a brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="brand_id_error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Use Case Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter use case name"
                            required>
                        <div class="invalid-feedback" id="name_error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="" disabled selected>Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="status_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="addUseCaseSubmit">
                        <span class="submit-text">Save</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Use Case Modal -->
<div class="modal fade" id="editUseCaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark">Edit Use Case</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUseCaseForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="use_case_id" id="edit_use_case_id">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Brand <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-select" id="edit_brand_id" required>
                            <option value="" disabled>Select a brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="edit_brand_id_error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Use Case Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="edit_name" required>
                        <div class="invalid-feedback" id="edit_name_error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" id="edit_status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="invalid-feedback" id="edit_status_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editUseCaseSubmit">
                        <span class="submit-text">Update</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Show/Hide Loading Functions
        function showLoading(button) {
            if (!button) return;
            button.disabled = true;
            const submitText = button.querySelector('.submit-text');
            const spinner = button.querySelector('.spinner-border');
            if (submitText) submitText.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');
        }

        function hideLoading(button) {
            if (!button) return;
            button.disabled = false;
            const submitText = button.querySelector('.submit-text');
            const spinner = button.querySelector('.spinner-border');
            if (submitText) submitText.classList.remove('d-none');
            if (spinner) spinner.classList.add('d-none');
        }

        function showButtonLoading(button) {
            if (!button) return;
            const originalHTML = button.innerHTML;
            button.setAttribute('data-original-html', originalHTML);
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            button.disabled = true;
        }

        function hideButtonLoading(button) {
            if (!button) return;
            const originalHTML = button.getAttribute('data-original-html');
            if (originalHTML) {
                button.innerHTML = originalHTML;
            }
            button.disabled = false;
        }

        // Reset Form Errors
        function resetFormErrors(formId = '') {
            const errorElements = document.querySelectorAll('.invalid-feedback');
            errorElements.forEach(el => {
                if (formId === '' || el.id.startsWith(formId)) {
                    el.textContent = '';
                    el.previousElementSibling?.classList.remove('is-invalid');
                }
            });
        }

        // Display Form Errors
        function displayErrors(errors, formId = '') {
            resetFormErrors(formId);

            for (const [field, messages] of Object.entries(errors)) {
                const input = document.querySelector(`[name="${field}"]`);
                const errorElement = document.getElementById(`${formId}${field}_error`);

                if (input) {
                    input.classList.add('is-invalid');
                }
                if (errorElement) {
                    errorElement.textContent = messages[0];
                }
            }
        }

        // Add Use Case Form Submission
        document.getElementById('addUseCaseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('addUseCaseSubmit');
            const formData = new FormData(this);

            showLoading(submitBtn);
            resetFormErrors();

            fetch('{{ route('admin.use-cases.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        if (data.errors) {
                            displayErrors(data.errors);
                        }
                        throw new Error(data.message || 'Failed to create use case');
                    }
                    return data;
                })
                .then(data => {
                    if (data.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message
                        });

                        // Close modal and reload
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'addUseCaseModal'));
                        if (modal) modal.hide();

                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: error.message || 'Failed to create use case'
                    });
                })
                .finally(() => {
                    hideLoading(submitBtn);
                });
        });

        // Edit Use Case - Open Modal
        document.querySelectorAll('.btn-edit-use-case').forEach(btn => {
            btn.addEventListener('click', function() {
                const useCaseId = this.dataset.id;
                const editButton = this;

                showButtonLoading(editButton);
                resetFormErrors('edit_');

                fetch(`/admin/use-cases/${useCaseId}/edit`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load use case data');
                        return res.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            const useCase = data.data;

                            document.getElementById('edit_use_case_id').value = useCase.id;
                            document.getElementById('edit_brand_id').value = useCase
                                .brand_id;
                            document.getElementById('edit_name').value = useCase.name;
                            document.getElementById('edit_status').value = useCase.status;

                            const editModal = new bootstrap.Modal(document.getElementById(
                                'editUseCaseModal'));
                            editModal.show();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toast.fire({
                            icon: 'error',
                            title: error.message || 'Failed to load use case data'
                        });
                    })
                    .finally(() => {
                        hideButtonLoading(editButton);
                    });
            });
        });

        // Edit Use Case Form Submission
        document.getElementById('editUseCaseForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('editUseCaseSubmit');
            const useCaseId = document.getElementById('edit_use_case_id').value;
            const formData = new FormData(this);

            if (!useCaseId) {
                Toast.fire({
                    icon: 'error',
                    title: 'Invalid use case ID'
                });
                return;
            }

            showLoading(submitBtn);
            resetFormErrors('edit_');

            formData.append('_method', 'PUT');

            fetch(`/admin/use-cases/${useCaseId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        if (data.errors) {
                            displayErrors(data.errors, 'edit_');
                        }
                        throw new Error(data.message || 'Failed to update use case');
                    }
                    return data;
                })
                .then(data => {
                    if (data.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message
                        });

                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'editUseCaseModal'));
                        if (modal) modal.hide();

                        setTimeout(() => location.reload(), 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: error.message || 'Failed to update use case'
                    });
                })
                .finally(() => {
                    hideLoading(submitBtn);
                });
        });

        // Delete Use Case
        document.querySelectorAll('.btn-delete-use-case').forEach(btn => {
            btn.addEventListener('click', function() {
                const useCaseId = this.dataset.id;
                const deleteButton = this;
                const originalContent = this.innerHTML;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This use case will be deleted permanently.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteButton.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i>';
                        deleteButton.disabled = true;

                        fetch(`/admin/use-cases/${useCaseId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message
                                    });
                                    setTimeout(() => location.reload(), 1000);
                                } else {
                                    throw new Error(data.message ||
                                        'Delete failed');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Toast.fire({
                                    icon: 'error',
                                    title: error.message ||
                                        'Failed to delete use case'
                                });
                            })
                            .finally(() => {
                                deleteButton.innerHTML = originalContent;
                                deleteButton.disabled = false;
                            });
                    }
                });
            });
        });

        // Reset form when modal is hidden
        const addModal = document.getElementById('addUseCaseModal');
        const editModal = document.getElementById('editUseCaseModal');

        if (addModal) {
            addModal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('addUseCaseForm').reset();
                resetFormErrors();
                const submitBtn = document.getElementById('addUseCaseSubmit');
                if (submitBtn) hideLoading(submitBtn);
            });
        }

        if (editModal) {
            editModal.addEventListener('hidden.bs.modal', function() {
                const submitBtn = document.getElementById('editUseCaseSubmit');
                if (submitBtn) hideLoading(submitBtn);
                resetFormErrors('edit_');
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.use-case-filter');
        const allCheckbox = document.querySelector('.use-case-filter[value="all"]');
        
        // Function to update URL with selected filters
        function updateUseCaseFilterUrl() {
            // Collect all checked values
            const checkedValues = [];
            checkboxes.forEach(cb => {
                if (cb.checked && cb.value !== '') {
                    checkedValues.push(cb.value);
                }
            });
            
            // Build URL with multiple status parameters
            let url = "{{ route('admin.use-cases.index') }}"; // Update with your actual route
            
            // Remove "all" from params if other filters are selected
            const filteredValues = checkedValues.filter(value => value !== 'all');
            
            if (filteredValues.length > 0) {
                const params = new URLSearchParams();
                filteredValues.forEach(value => {
                    params.append('status[]', value);
                });
                url += '?' + params.toString();
            } else if (checkedValues.includes('all') || checkedValues.length === 0) {
                // If only "all" is checked or nothing is checked, go to base URL
                url = "{{ route('admin.use-cases.index') }}";
            }
            
            console.log('Use Case filter - Navigating to:', url);
            window.location.href = url;
        }
        
        // Set initial state - check "all" if no checkboxes are checked
        const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
        if (!hasChecked && allCheckbox) {
            allCheckbox.checked = true;
        }
        
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                // If this is the "all" checkbox
                if (this.value === 'all') {
                    if (this.checked) {
                        // Uncheck all other checkboxes
                        checkboxes.forEach(cb => {
                            if (cb !== this) {
                                cb.checked = false;
                            }
                        });
                    }
                } else {
                    // If a specific checkbox is checked
                    if (this.checked && allCheckbox) {
                        allCheckbox.checked = false;
                    }
                    
                    // If all specific checkboxes are unchecked, check "all"
                    const specificCheckboxes = Array.from(checkboxes).filter(cb => cb.value !== 'all');
                    const hasSpecificChecked = specificCheckboxes.some(cb => cb.checked);
                    
                    if (!hasSpecificChecked && allCheckbox) {
                        allCheckbox.checked = true;
                    }
                }
                
                // Wait a moment for UI to update, then update URL
                setTimeout(updateUseCaseFilterUrl, 50);
            });
        });
    });
</script>
@endsection
