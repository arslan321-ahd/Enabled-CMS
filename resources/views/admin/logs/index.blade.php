@extends('admin.partials.layouts')
@section('content')
@section('title', 'System Logs')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">System Logs</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a></li>
                    <li class="breadcrumb-item active">Logs</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card"><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="datatable_1">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                    <th>Performed By</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($logs as $index => $log)
                                    <tr class="{{ !$log->is_read ? 'bg-info-subtle' : '' }}" style="cursor:pointer"
                                        onclick="window.location='{{ route('admin.logs.show', $log->id) }}'">
                                        <td>{{ $index + 1 }}</td>

                                        <td>
                                            <span class="fw-semibold">{{ $log->title }}</span>
                                        </td>

                                        <td>
                                            <span class="text-muted">{{ $log->description }}</span>
                                        </td>

                                        <td>
                                            <span class="badge bg-info-subtle text-info px-2">
                                                {{ ucfirst($log->action) }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ optional($log->user)->name ?? 'System' }}
                                        </td>
                                        <td>
                                            @if (!$log->is_read)
                                                <span class="badge bg-danger-subtle text-danger px-2">Unread</span>
                                            @else
                                                <span class="badge bg-success-subtle text-success px-2">Read</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $log->created_at->format('d M Y h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No logs found.
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
<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
@endsection
