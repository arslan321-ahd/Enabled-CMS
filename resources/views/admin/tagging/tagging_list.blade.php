@extends('admin.partials.layouts')
@section('content')
@section('title', 'Tagging List')
@php
    $currentStatus = request('status');
@endphp
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Tagging List</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li><!--end nav-item-->
                        <li class="breadcrumb-item active">Tagging List</li>
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
                            <h4 class="card-title">Tagging</h4>
                        </div>
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
                                                <input type="checkbox" class="form-check-input filter-checkbox"
                                                    value="" {{ empty($currentStatus) ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input filter-checkbox"
                                                    value="online" {{ $currentStatus === 'online' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Online
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input filter-checkbox"
                                                    value="offline" {{ $currentStatus === 'offline' ? 'checked' : '' }}>
                                                <label class="form-check-label">
                                                    Offline
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addTagging">
                                        <i class="fa-solid fa-plus me-1"></i> Add Tagging
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
                                    <th class="ps-0" style="width: 16px;">
                                        ID
                                    </th>
                                    <th class="ps-0">Source</th>
                                    <th>Tagging</th>
                                    <th>Refrence Link</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($taggings as $key => $tagging)
                                    <tr>
                                        <td style="width: 16px;">
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            {{ $tagging->source }}
                                        </td>
                                        <td>
                                            @if ($tagging->status === 'online')
                                                <span class="badge bg-success-subtle text-success px-2">
                                                    Online
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2">
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($tagging->ref_url)
                                                <a href="{{ $tagging->ref_url }}" target="_blank">
                                                    {{ $tagging->ref_url }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary editTaggingBtn"
                                                data-id="{{ $tagging->id }}" data-source="{{ $tagging->source }}"
                                                data-status="{{ $tagging->status }}"
                                                data-ref_url="{{ $tagging->ref_url }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <form action="{{ route('admin.tagging.delete', $tagging->id) }}"
                                                method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                    data-bs-toggle="tooltip" title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No tagging records found.
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
            <form method="POST" action="{{ route('taggings.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <input type="text" class="form-control" name="source"
                            placeholder="Enter title or source">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagging Status</label>
                        <select name="status" class="form-select">
                            <option value="" disabled selected>Select status</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference URL</label>
                        <input type="url" class="form-control" name="ref_url"
                            placeholder="Enter reference link">
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
<div class="modal fade" id="editTaggingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Edit Tagging</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editTaggingForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <input type="text" class="form-control" name="source" id="edit_source">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tagging Status</label>
                        <select name="status" class="form-select" id="edit_status">
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reference URL</label>
                        <input type="url" class="form-control" name="ref_url" id="edit_ref_url">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
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
        document.querySelectorAll('.editTaggingBtn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('edit_source').value = this.dataset.source;
                document.getElementById('edit_status').value = this.dataset.status;
                document.getElementById('edit_ref_url').value = this.dataset.ref_url;
                document.getElementById('editTaggingForm').dataset.id = id;
                new bootstrap.Modal(document.getElementById('editTaggingModal')).show();
            });
        });
        document.getElementById('editTaggingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const id = form.dataset.id;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Updating...';
            submitBtn.disabled = true;
            fetch(`/taggings-update/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'editTaggingModal'));
                    modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message || 'Tagging updated successfully',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Failed to update tagging. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    console.error('Error:', error);
                });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This record will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.filter-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = false);
                this.checked = true
                const status = this.value;
                let url = "{{ route('admin.tagging.list') }}";
                if (status !== '') {
                    url += '?status=' + status;
                }
                window.location.href = url;
            });
        });
    });
</script>
@endsection
