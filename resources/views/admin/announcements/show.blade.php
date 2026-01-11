@extends('admin.partials.layouts')
@section('content')
@section('title', 'Announcements')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h2>{{ $announcement->title }}</h2>
                    <div class="mb-2">
                        <span class="badge bg-purple-subtle text-purple px-2 py-1 fw-semibold">
                            {{ $announcement->category }}
                        </span> |
                        <small class="text-muted">{{ $announcement->created_at->format('d M Y') }}</small>
                    </div>
                    @if ($announcement->attachment)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $announcement->attachment) }}"
                                alt="{{ $announcement->title }}" class="img-fluid rounded">
                        </div>
                    @endif
                    <div class="announcement-content">
                        {!! $announcement->content !!}
                    </div>
                    <hr>
                    <h5>Author:&nbsp;{{ Auth::user()->name }}</h5>
                    <a href="{{ route('admin.announcements') }}" class="btn btn-secondary mb-3">Back to
                        Announcements</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
