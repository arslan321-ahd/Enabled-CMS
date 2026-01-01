@extends('admin.partials.layouts')
@section('content')
@section('title', 'Distribution List')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
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
                                                <input type="checkbox" class="branch-filter" value=""
                                                    {{ empty($currentStatus) ? 'checked' : '' }}>
                                                <label class="form-check-label">All</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="branch-filter" value="new"
                                                    {{ $currentStatus === 'new' ? 'checked' : '' }}>
                                                <label class="form-check-label">New</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="branch-filter" value="active"
                                                    {{ $currentStatus === 'active' ? 'checked' : '' }}>
                                                <label class="form-check-label">Active</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="branch-filter" value="inactive"
                                                    {{ $currentStatus === 'inactive' ? 'checked' : '' }}>
                                                <label class="form-check-label">Inactive</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addBranch">
                                        <i class="fa-solid fa-plus me-1"></i> Add Branch
                                    </button>
                                </div>
                            </form>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-header-->
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
                                    <th>Action</th>
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
                                                <span class="badge bg-success-subtle text-success px-2">Active</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2">Inactive</span>
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
                                            <a href="javascript:void(0);" class="btn btn-sm btn-primary editBranchBtn"
                                                data-id="{{ $branch->id }}" data-name="{{ $branch->name }}"
                                                data-email="{{ $branch->email }}" data-phone="{{ $branch->phone }}"
                                                data-role="{{ $branch->role }}" data-status="{{ $branch->status }}"
                                                data-bs-toggle="modal" data-bs-target="#editBranchModal" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST"
                                                class="d-inline-block delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
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
                        <input type="email" class="form-control" name="email" placeholder="Enter email address"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" placeholder="Enter phone number">
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
<div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchLabel" aria-hidden="true">
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
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = false);
                this.checked = true;
                const status = this.value;
                let url = "{{ route('admin.branches') }}";
                if (status !== '') {
                    url += '?status=' + status;
                }
                window.location.href = url;
            });
        });
    });
</script>
@endsection
