@extends('admin.partials.layouts')
@section('content')
@section('title', 'Distribution List')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
@php
    $canEdit = auth()->user()->canAccess('branches', 'edit');
    $canDelete = auth()->user()->canAccess('branches', 'delete');
    $canCreate = auth()->user()->canAccess('branches', 'create');
    $hasActions = $canEdit || $canDelete || auth()->user()->isAdmin();
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Branches List</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Branches List</li>
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
                            <h4 class="card-title">Branches</h4>
                        </div><!--end col-->
                        <div class="col-auto">
                            <form class="row g-2" id="filterForm">
                                <div class="col-auto">
                                    <a class="btn bg-primary-subtle text-primary dropdown-toggle d-flex align-items-center arrow-none"
                                        data-bs-toggle="dropdown" href="#" data-bs-auto-close="outside">
                                        <i class="iconoir-filter-alt me-1"></i> Filter
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <div class="p-2">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="branch-filter" value="all"
                                                    id="branch-all"
                                                    {{ empty($status) && empty(request('status')) ? 'checked' : (in_array('all', (array) request('status', [])) ? 'checked' : '') }}>
                                                <label class="form-check-label" for="branch-all">All</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="branch-filter" value="new"
                                                    id="branch-new"
                                                    {{ in_array('new', (array) request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="branch-new">New</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="branch-filter" value="active"
                                                    id="branch-active"
                                                    {{ in_array('active', (array) request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="branch-active">Active</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="branch-filter" value="inactive"
                                                    id="branch-inactive"
                                                    {{ in_array('inactive', (array) request('status', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="branch-inactive">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if (auth()->user()->canAccess('branches', 'create'))
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addBranch">
                                            <i class="fa-solid fa-plus me-1"></i> Add Branch
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                        < </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table mb-0 checkbox-all" id="datatable_1">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-0" style="width: 16px;">
                                            ID
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        @if ($hasActions)
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($branches as $key => $branch)
                                        <tr>
                                            <td style="width: 16px;">
                                                {{ $key + 1 }}
                                            </td>
                                            <td class="ps-0">
                                                <div class="d-flex align-items-center">
                                                    <span
                                                        class="thumb-lg d-flex justify-content-center align-items-center 
         bg-purple-subtle text-purple rounded-circle me-2">
                                                        @php
                                                            $words = explode(' ', $branch->name);
                                                            $initials = '';
                                                            foreach ($words as $word) {
                                                                $initials .= strtoupper(substr($word, 0, 1));
                                                            }
                                                        @endphp
                                                        {{ $initials }}
                                                    </span>
                                                    <span class="font-13 fw-medium">{{ $branch->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $branch->email }}"
                                                    class="d-inline-block align-middle mb-0 text-body">
                                                    {{ $branch->email }}
                                                </a>
                                            </td>
                                            <td>{{ $branch->phone ?? '-' }}</td>
                                            <td>
                                                @if ($branch->status == 1)
                                                    <span
                                                        class="badge bg-success-subtle text-success px-2">Active</span>
                                                @else
                                                    <span
                                                        class="badge bg-danger-subtle text-danger px-2">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($branch->role === 'admin')
                                                    <span class="badge bg-primary-subtle text-primary px-2">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-danger px-2">User</span>
                                                @endif
                                            </td>
                                            <td>{{ $branch->created_at->format('d M Y h:i A') }}</td>
                                            <td>
                                                @if (auth()->user()->isAdmin())
                                                    <a href="#" class="btn btn-sm btn-secondary"
                                                        data-bs-toggle="modal" data-bs-target="#permissionModal"
                                                        data-user-id="{{ $branch->id }}">
                                                        <i class="fa-solid fa-gear"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canAccess('branches', 'edit'))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-sm btn-primary editBranchBtn"
                                                        data-id="{{ $branch->id }}" data-name="{{ $branch->name }}"
                                                        data-email="{{ $branch->email }}"
                                                        data-phone="{{ $branch->phone }}"
                                                        data-role="{{ $branch->role }}"
                                                        data-status="{{ $branch->status }}" data-bs-toggle="modal"
                                                        data-bs-target="#editBranchModal">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->canAccess('branches', 'delete'))
                                                    <form action="{{ route('branches.destroy', $branch->id) }}"
                                                        method="POST" class="d-inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addBranch" tabindex="-1" aria-labelledby="addBranchLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark" id="addBranchLabel">Add Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.branches.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter full name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email"
                                placeholder="Enter email address" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone"
                                placeholder="Enter phone number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select">
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password"
                                required>
                            <ul class="small text-muted mb-0">
                                <li>Minimum 8 characters</li>
                                <li>At least 1 uppercase letter (A–Z)</li>
                                <li>At least 1 lowercase letter (a–z)</li>
                                <li>At least 1 number (0–9)</li>
                                <li>At least 1 special character (!@#$%^&*)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark" id="editBranchLabel">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="editBranchForm">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="branch_id" id="branch_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="edit_phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" id="edit_role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" id="edit_status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <small>(leave blank to keep current)</small></label>
                            <input type="password" class="form-control" name="password" id="edit_password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="updateBranchBtn">
                            <span id="updateBtnText">Update</span>
                            <span id="updateBtnSpinner" class="spinner-border spinner-border-sm ms-2 d-none"
                                role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="permissionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Module Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="permissionForm" action="{{ route('admin.permissions.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="permission_user_id">
                    <div class="modal-body">
                        <div id="permissionsLoading" class="text-center d-none">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading permissions...</p>
                        </div>
                        <table class="table" id="permissionsTable">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>View</th>
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="permissionsTableBody">
                                @foreach ($modules as $module)
                                    <tr data-module-id="{{ $module->id }}">
                                        <td>{{ ucfirst($module->name) }}</td>
                                        <td>
                                            <input type="hidden" name="modules[{{ $module->id }}][view]"
                                                value="0">
                                            <input type="checkbox" name="modules[{{ $module->id }}][view]"
                                                value="1" class="perm-checkbox"
                                                id="module_{{ $module->id }}_view">
                                        </td>
                                        <td>
                                            <input type="hidden" name="modules[{{ $module->id }}][create]"
                                                value="0">
                                            <input type="checkbox" name="modules[{{ $module->id }}][create]"
                                                value="1" class="perm-checkbox"
                                                id="module_{{ $module->id }}_create">
                                        </td>
                                        <td>
                                            <input type="hidden" name="modules[{{ $module->id }}][edit]"
                                                value="0">
                                            <input type="checkbox" name="modules[{{ $module->id }}][edit]"
                                                value="1" class="perm-checkbox"
                                                id="module_{{ $module->id }}_edit">
                                        </td>
                                        <td>
                                            <input type="hidden" name="modules[{{ $module->id }}][delete]"
                                                value="0">
                                            <input type="checkbox" name="modules[{{ $module->id }}][delete]"
                                                value="1" class="perm-checkbox"
                                                id="module_{{ $module->id }}_delete">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save Permissions</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modulesData" data-modules='@json($modules)' style="display: none;"></div>
    <script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
    <script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('permissionModal');
            const permissionForm = document.getElementById('permissionForm');
            const permissionsLoading = document.getElementById('permissionsLoading');
            const permissionsTable = document.getElementById('permissionsTable');
            const originalTableHTML = document.getElementById('permissionsTableBody').innerHTML;
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.closest('tr').querySelector('.font-13.fw-medium')?.textContent ||
                    'User';
                modal.querySelector('.modal-title').textContent = `Permissions for ${userName}`;
                document.getElementById('permission_user_id').value = userId;
                document.getElementById('permissionsTableBody').innerHTML = originalTableHTML;
                permissionsTable.classList.add('d-none');
                permissionsLoading.classList.remove('d-none');
                loadUserPermissions(userId);
            });

            function loadUserPermissions(userId) {
                fetch(`/admin/users/${userId}/permissions`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                        }
                        return response.json();
                    })
                    .then(permissions => {
                        permissionsLoading.classList.add('d-none');
                        permissionsTable.classList.remove('d-none');
                        document.querySelectorAll('.perm-checkbox').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        if (permissions && typeof permissions === 'object') {
                            Object.keys(permissions).forEach(moduleId => {
                                const perms = permissions[moduleId];
                                const viewCheckbox = document.getElementById(`module_${moduleId}_view`);
                                const createCheckbox = document.getElementById(
                                    `module_${moduleId}_create`);
                                const editCheckbox = document.getElementById(`module_${moduleId}_edit`);
                                const deleteCheckbox = document.getElementById(
                                    `module_${moduleId}_delete`);
                                if (viewCheckbox) viewCheckbox.checked = Boolean(perms.can_view);
                                if (createCheckbox) createCheckbox.checked = Boolean(perms.can_create);
                                if (editCheckbox) editCheckbox.checked = Boolean(perms.can_edit);
                                if (deleteCheckbox) deleteCheckbox.checked = Boolean(perms.can_delete);
                            });
                        }
                    })
                    .catch(error => {
                        permissionsLoading.classList.add('d-none');
                        permissionsTable.classList.remove('d-none');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not load existing permissions. You can still set new permissions.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
            }
            permissionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
                submitBtn.disabled = true;
                const formData = new FormData(this);
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw new Error(err.message || 'Failed to save');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Permissions saved successfully!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            setTimeout(() => {
                                const modalInstance = bootstrap.Modal.getInstance(modal);
                                if (modalInstance) modalInstance.hide();
                            }, 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to save permissions'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Something went wrong. Please try again.'
                        });
                    })
                    .finally(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    });
            });
            modal.addEventListener('hidden.bs.modal', function() {
                permissionsLoading.classList.add('d-none');
                permissionsTable.classList.remove('d-none');
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
            @if (session('status') === 'branch-created')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Branch added successfully.'
                });
            @endif
            @if (session('status') === 'branch-updated')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Branch updated successfully.'
                });
            @endif
            @if (session('status') === 'branch-deleted')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Branch deleted successfully.'
                });
            @endif
            @if (session('status') === 'tagging-created')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Tagging added successfully.'
                });
            @endif
            @if (session('status') === 'tagging-updated')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Tagging updated successfully.'
                });
            @endif
            @if (session('status') === 'tagging-deleted')
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Tagging deleted successfully.'
                });
            @endif
            @if ($errors->any())
                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please fix the errors and try again.'
                });
            @endif
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const updateForm = document.getElementById('editBranchForm');
            const updateBtn = document.getElementById('updateBranchBtn');
            const btnText = document.getElementById('updateBtnText');
            const spinner = document.getElementById('updateBtnSpinner');
            updateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const branchId = document.getElementById('branch_id').value;
                const formData = new FormData(updateForm);
                spinner.classList.remove('d-none');
                btnText.textContent = 'Updating...';
                updateBtn.disabled = true;
                fetch(`/admin/branches/${branchId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        spinner.classList.add('d-none');
                        btnText.textContent = 'Update';
                        updateBtn.disabled = false;
                        if (data.success) {
                            const modal = bootstrap.Modal.getInstance(
                                document.getElementById('editBranchModal')
                            );
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Branch updated successfully',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Something went wrong.'
                            });
                        }
                    })
                    .catch(() => {
                        spinner.classList.add('d-none');
                        btnText.textContent = 'Update';
                        updateBtn.disabled = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Try again.'
                        });
                    });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.editBranchBtn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const email = this.dataset.email;
                    const phone = this.dataset.phone;
                    const role = this.dataset.role;
                    const status = this.dataset.status;
                    document.getElementById('branch_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_phone').value = phone;
                    document.getElementById('edit_role').value = role;
                    document.getElementById('edit_status').value = status;
                    document.getElementById('edit_password').value = '';
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
                timerProgressBar: true
            });
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This action cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': form.querySelector(
                                            'input[name=_token]').value,
                                        'Accept': 'application/json'
                                    },
                                    body: new FormData(form)
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        form.closest('tr').remove();
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Deleted',
                                            text: data.message
                                        });
                                    } else {
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: data.message
                                        });
                                    }
                                })
                                .catch(() => {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Something went wrong'
                                    });
                                });
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.branch-filter');
            const allCheckbox = document.querySelector('.branch-filter[value="all"]');

            function updateFilterUrl() {
                const checkedValues = [];
                checkboxes.forEach(cb => {
                    if (cb.checked && cb.value !== '') {
                        checkedValues.push(cb.value);
                    }
                });
                console.log('Checked values:', checkedValues);
                let url = "{{ route('admin.branches') }}";
                const filteredValues = checkedValues.filter(value => value !== 'all');
                if (filteredValues.length > 0) {
                    const params = new URLSearchParams();
                    filteredValues.forEach(value => {
                        params.append('status[]', value);
                    });
                    url += '?' + params.toString();
                } else if (checkedValues.includes('all') || checkedValues.length === 0) {
                    url = "{{ route('admin.branches') }}";
                }
                console.log('Navigating to:', url);
                window.location.href = url;
            }
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked);
            if (!hasChecked && allCheckbox) {
                allCheckbox.checked = true;
            }
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    if (this.value === 'all') {
                        if (this.checked) {
                            checkboxes.forEach(cb => {
                                if (cb !== this) {
                                    cb.checked = false;
                                }
                            });
                        }
                    } else {
                        if (this.checked && allCheckbox) {
                            allCheckbox.checked = false;
                        }
                        const specificCheckboxes = Array.from(checkboxes).filter(cb => cb.value !==
                            'all');
                        const hasSpecificChecked = specificCheckboxes.some(cb => cb.checked);
                        if (!hasSpecificChecked && allCheckbox) {
                            allCheckbox.checked = true;
                        }
                    }
                    setTimeout(updateFilterUrl, 50);
                });
            });
        });
    </script>
@endsection
