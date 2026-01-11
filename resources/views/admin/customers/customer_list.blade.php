@extends('admin.partials.layouts')
@section('content')
@section('title', 'Customers List')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Customers List</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Customers List</li>
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
                            <h4 class="card-title">Customers</h4>
                        </div><!--end col-->
                        <div class="col-auto">
                            <form class="row g-2">
                                <div class="col-auto">
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <div class="p-2">
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked id="filter-all">
                                                <label class="form-check-label" for="filter-all">
                                                    All
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked id="filter-one">
                                                <label class="form-check-label" for="filter-one">
                                                    New
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input type="checkbox" class="form-check-input" checked id="filter-two">
                                                <label class="form-check-label" for="filter-two">
                                                    Active
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" checked
                                                    id="filter-three">
                                                <label class="form-check-label" for="filter-three">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end col-->

                                <div class="col-auto">
                                    <a href="{{ route('forms.create') }}"
                                        class="btn btn-primary d-flex align-items-center"> <i
                                            class="fa-solid fa-plus me-1"></i> Add Customer</a>
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
                                    <th>#</th>
                                    <th>Form Name</th>
                                    <th>Branch Name</th>
                                    <th>Total Fields</th>
                                    <th>Total Submissions</th>
                                    <th>Form Link</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($forms as $key => $form)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- FORM NAME --}}
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ Storage::url($form->logo) }}" alt="Form Logo"
                                                    class="rounded-circle me-2"
                                                    style="width:40px; height:40px; object-fit:cover;">

                                                <span class="fw-medium">
                                                    {{ $form->title }}
                                                </span>
                                            </div>
                                        </td>


                                        {{-- BRANCH NAME --}}
                                        <td>
                                            {{ $form->user->name ?? 'N/A' }}
                                        </td>

                                        {{-- TOTAL FIELDS --}}
                                        <td>
                                            {{ $form->fields_count }}
                                        </td>

                                        {{-- TOTAL SUBMISSIONS --}}
                                        <td>
                                            {{ $form->submissions_count }}
                                        </td>

                                        {{-- FORM LINK --}}
                                        <td>
                                            <a href="{{ route('form.public', $form->slug) }}" target="_blank">
                                                {{ route('form.public', $form->slug) }}
                                            </a>
                                        </td>


                                        {{-- ACTIONS --}}
                                        <td>
                                            <a href="{{ route('forms.submissions', $form->id) }}"
                                                class="btn btn-sm btn-success" title="View Submissions">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="{{ route('forms.edit', $form->id) }}"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form id="delete-form-{{ $form->id }}"
                                                action="{{ route('forms.destroy', $form->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                    data-form-id="{{ $form->id }}" title="Delete">
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
<!-- Order Summary Modal -->
<div class="modal fade orderSummaryModal" id="" tabindex="-1" aria-labelledby="orderSummaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="orderSummaryModalLabel">Order Summary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('admin.customers.order_summary')
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
<script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listener to all delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const formId = this.getAttribute('data-form-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this! This will also delete all submissions!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form
                        document.getElementById(`delete-form-${formId}`).submit();
                    }
                });
            });
        });
        @if (session('success'))
            @php
                // Detect what type of action was performed
                $message = session('success');
                $icon = 'success';
                $title = 'Success!';

                if (strpos($message, 'created') !== false) {
                    $icon = 'success';
                    $title = 'Created!';
                } elseif (strpos($message, 'updated') !== false) {
                    $icon = 'success';
                    $title = 'Updated!';
                } elseif (strpos($message, 'deleted') !== false) {
                    $icon = 'success';
                    $title = 'Deleted!';
                }
            @endphp

            Swal.fire({
                icon: '{{ $icon }}',
                title: '{{ $title }}',
                text: '{{ $message }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                },
                background: '#f8f9fa',
                color: '#212529',
                iconColor: '{{ $icon === 'success' ? '#198754' : ($icon === 'info' ? '#0dcaf0' : '#198754') }}'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                background: '#f8d7da',
                color: '#721c24',
                iconColor: '#dc3545'
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonColor: '#dc3545',
                background: '#f8d7da',
                color: '#721c24',
                iconColor: '#dc3545'
            });
        @endif
    });
</script>
@endsection
