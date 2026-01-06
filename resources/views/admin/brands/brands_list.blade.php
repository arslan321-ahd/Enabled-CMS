@extends('admin.partials.layouts')
@section('content')
@section('title', 'Brands List')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Brands List</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Brands List</li>
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
                            <h4 class="card-title">Brands</h4>
                        </div>
                        <div class="col-auto">
                            <form class="row g-2">
                                <div class="col-auto">
                                    <a class="btn bg-primary-subtle text-primary dropdown-toggle d-flex align-items-center arrow-none"
                                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                        aria-expanded="false" data-bs-auto-close="outside">
                                        <i class="iconoir-filter-alt me-1"></i> Filter
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <div class="p-2">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked id="filter-all">
                                                <label class="form-check-label" for="filter-all">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked id="filter-two">
                                                <label class="form-check-label" for="filter-two">
                                                    Online
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked
                                                    id="filter-three">
                                                <label class="form-check-label" for="filter-three">
                                                    Offline
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addTagging"><i class="fa-solid fa-plus me-1"></i> Add
                                        Brands</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-0">Name</th>
                                    <th>Status</th>
                                    <th>Reference Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $brand)
                                    <tr>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ asset('storage/' . $brand->logo) }}" width="32"
                                                    height="32" class="rounded-circle" alt="logo">
                                                <span>{{ $brand->name }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            @if ($brand->status === 'active')
                                                <span class="badge bg-success-subtle text-success px-2">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2">Inactive</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if ($brand->reference_url)
                                                <a href="{{ $brand->reference_url }}" target="_blank">
                                                    {{ $brand->reference_url }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            <a href="javascript:void(0)" data-id="{{ $brand->id }}"
                                                class="btn btn-sm btn-primary btn-edit-brand" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <a href="javascript:void(0)" data-id="{{ $brand->id }}"
                                                class="btn btn-sm btn-danger btn-delete-brand" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No brands found
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
<div class="modal fade" id="addTagging" tabindex="-1" aria-labelledby="addTaggingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="addTaggingLabel">Add Tagging</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addBrandForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label" for="add_name">Brand Name</label>
                        <input type="text" class="form-control" id="add_name" name="name"
                            placeholder="Enter brand name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="add_logo">Brand Logo</label>
                        <input type="file" class="form-control" id="add_logo" name="logo" accept="image/*"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="add_status">Status</label>
                        <select class="form-select" id="add_status" name="status" required>
                            <option value="" disabled selected>Select status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="add_reference_url">Reference URL</label>
                        <input type="url" class="form-control" id="add_reference_url" name="reference_url"
                            placeholder="https://example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark">Edit Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="editBrandForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="brand_id" id="edit_brand_id">

                    <div class="mb-3">
                        <label class="form-label">Brand Name</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand Logo</label>
                        <input type="file" class="form-control" name="logo" id="edit_logo">
                        <small class="text-muted">Leave empty to keep current logo</small>
                        <div class="mt-2">
                            <img id="current_logo" src="" class="img-thumbnail" width="80"
                                alt="Current Logo" style="display: none;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reference URL</label>
                        <input type="url" class="form-control" name="reference_url" id="edit_reference_url">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="editBrandSubmit">
                        <span class="submit-text">Update Brand</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
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

        // Add Brand AJAX
        document.getElementById('addBrandForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('admin.brands.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Toast.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message
                        });

                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById(
                            'addTagging'));
                        modal.hide();

                        // Optionally reload or append new row
                        setTimeout(() => location.reload(), 500);
                    }
                })
                .catch(err => {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong, please try again.'
                    });
                    console.error(err);
                });
        });
    });
</script>

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

        // Show loading spinner for form submit buttons
        function showLoading(button) {
            if (!button || !button.querySelector) return;

            button.disabled = true;
            const submitText = button.querySelector('.submit-text');
            const spinner = button.querySelector('.spinner-border');

            if (submitText) submitText.classList.add('d-none');
            if (spinner) spinner.classList.remove('d-none');
        }

        // Hide loading spinner
        function hideLoading(button) {
            if (!button || !button.querySelector) return;

            button.disabled = false;
            const submitText = button.querySelector('.submit-text');
            const spinner = button.querySelector('.spinner-border');

            if (submitText) submitText.classList.remove('d-none');
            if (spinner) spinner.classList.add('d-none');
        }

        // Show loading on edit button itself (different structure)
        function showEditButtonLoading(button) {
            if (!button) return;

            const originalHTML = button.innerHTML;
            button.setAttribute('data-original-html', originalHTML);
            button.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            button.disabled = true;
        }

        // Hide loading on edit button
        function hideEditButtonLoading(button) {
            if (!button) return;

            const originalHTML = button.getAttribute('data-original-html');
            if (originalHTML) {
                button.innerHTML = originalHTML;
            }
            button.disabled = false;
        }

        // Open Edit Modal - FIXED VERSION
        document.querySelectorAll('.btn-edit-brand').forEach(btn => {
            btn.addEventListener('click', function() {
                const brandId = this.dataset.id;
                const editButton = this;

                // Show loading on the edit button itself
                showEditButtonLoading(editButton);

                fetch(`/admin/brands/${brandId}/edit`)
                    .then(res => {
                        if (!res.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return res.json();
                    })
                    .then(data => {
                        console.log('Brand data loaded:', data);

                        document.getElementById('edit_brand_id').value = data.id;
                        document.getElementById('edit_name').value = data.name;
                        document.getElementById('edit_status').value = data.status;
                        document.getElementById('edit_reference_url').value = data
                            .reference_url || '';

                        if (data.logo) {
                            const logoElement = document.getElementById('current_logo');
                            logoElement.src = `/storage/${data.logo}`;
                            logoElement.style.display = 'block';
                        } else {
                            document.getElementById('current_logo').style.display = 'none';
                        }

                        // Initialize and show modal
                        const editModal = new bootstrap.Modal(document.getElementById(
                            'editBrandModal'));
                        editModal.show();
                    })
                    .catch(error => {
                        console.error('Error loading brand data:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed to load brand data'
                        });
                    })
                    .finally(() => {
                        // Hide loading on edit button
                        hideEditButtonLoading(editButton);
                    });
            });
        });

        // Submit Edit Form
        document.getElementById('editBrandForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('editBrandSubmit');
            const brandId = document.getElementById('edit_brand_id').value;
            const formData = new FormData(this);

            if (!brandId) {
                Toast.fire({
                    icon: 'error',
                    title: 'Invalid brand ID'
                });
                return;
            }

            showLoading(submitBtn);

            // Add _method for Laravel to recognize PUT request
            formData.append('_method', 'PUT');

            fetch(`/admin/brands/${brandId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw new Error(data.message || 'Update failed');
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

                        // Close modal and reload page after delay
                        setTimeout(() => {
                            const modal = bootstrap.Modal.getInstance(document
                                .getElementById('editBrandModal'));
                            if (modal) modal.hide();
                            location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toast.fire({
                        icon: 'error',
                        title: error.message || 'Failed to update brand'
                    });
                })
                .finally(() => {
                    hideLoading(submitBtn);
                });
        });

        // Submit Add Form with spinner
        document.getElementById('addBrandForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('addBrandSubmit');
            showLoading(submitBtn);
        });

        // Delete Brand
        document.querySelectorAll('.btn-delete-brand').forEach(btn => {
            btn.addEventListener('click', function() {
                const brandId = this.dataset.id;
                const deleteButton = this;

                // Store original content
                const originalContent = this.innerHTML;

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This brand will be deleted permanently.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading on delete button
                        deleteButton.innerHTML =
                            '<i class="fa-solid fa-spinner fa-spin"></i>';
                        deleteButton.disabled = true;

                        fetch(`/admin/brands/${brandId}`, {
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
                                        'Failed to delete brand'
                                });
                            })
                            .finally(() => {
                                // Reset button state
                                deleteButton.innerHTML = originalContent;
                                deleteButton.disabled = false;
                            });
                    }
                });
            });
        });

        // Reset form when modal is closed
        const editModal = document.getElementById('editBrandModal');
        if (editModal) {
            editModal.addEventListener('hidden.bs.modal', function() {
                // Reset any loading states
                const submitBtn = document.getElementById('editBrandSubmit');
                if (submitBtn) hideLoading(submitBtn);
            });
        }
    });
</script>
@endsection
