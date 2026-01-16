@extends('admin.partials.layouts')
@section('content')
@section('title', 'Log Detail')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">{{ $log->title }}</h4>
                </div>

                <div class="card-body">
                    <p><strong>Description:</strong></p>
                    <p class="text-muted">{{ $log->description }}</p>

                    <hr>

                    <p><strong>Action:</strong> {{ ucfirst($log->action) }}</p>
                    <p><strong>Performed By:</strong> {{ optional($log->user)->name ?? 'System' }}</p>
                    <p><strong>Date:</strong> {{ $log->created_at->format('d M Y h:i A') }}</p>
                </div>

                <div class="card-footer text-end">
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-secondary">
                        Back to Logs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
