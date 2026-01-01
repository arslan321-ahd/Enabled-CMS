@extends('admin.partials.layouts')
@section('content')
@section('title', 'Announcements')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Announcements</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Announcements</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        @foreach ($announcements as $announcement)
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            {{-- Display attachment if exists, otherwise a default image --}}
                            <img src="{{ $announcement->attachment ? asset('storage/' . $announcement->attachment) : asset('assets/images/extra/card/img-1.jpg') }}"
                                alt="{{ $announcement->title }}" class="img-fluid rounded" />

                            <div class="mt-3">
                                {{-- Category Badge --}}
                                <span class="badge bg-purple-subtle text-purple px-2 py-1 fw-semibold">
                                    {{ $announcement->category }}
                                </span> |

                                {{-- Date --}}
                                <p class="mb-0 text-muted fs-12 d-inline-block">
                                    {{ $announcement->created_at->format('d M Y') }}
                                </p>
                            </div>

                            {{-- Announcement Title --}}
                            <a href="{{ route('announcements.show', $announcement) }}"
                                class="d-block fs-22 fw-semibold text-body my-2 text-truncate">
                                {{ $announcement->title }}
                            </a>

                            {{-- Short content snippet --}}
                            <p class="text-muted">
                                {{ Str::limit(strip_tags($announcement->content), 100) }}
                            </p>

                            <hr class="hr-dashed">

                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('assets/images/users/avatar-10.jpg') }}" alt="Admin"
                                            class="thumb-md rounded-circle">
                                    </div>
                                    <div class="flex-grow-1 ms-2 text-truncate text-start">
                                        <h6 class="m-0 text-dark">Admin</h6>
                                        <p class="mb-0 text-muted">by <a href="#">admin</a></p>
                                    </div>
                                </div>
                                <div class="align-self-center">
                                    <a href="{{ route('announcements.show', $announcement) }}"
                                        class="btn btn-sm btn-primary">
                                        Read more <i class="fas fa-long-arrow-alt-right"></i>
                                    </a>
                                    @auth
                                        @if (auth()->user()->role === 'admin')
                                            <a href="{{ route('announcements.edit', $announcement) }}"
                                                class="btn btn-sm btn-warning">
                                                Edit <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
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
        /* ================= ANNOUNCEMENTS ================= */
        @if (session('status') === 'announcement-created')
            Toast.fire({
                icon: 'success',
                title: 'Success',
                text: 'Announcement created successfully.'
            });
        @endif

        @if (session('status') === 'announcement-updated')
            Toast.fire({
                icon: 'success',
                title: 'Success',
                text: 'Announcement updated successfully.'
            });
        @endif

        @if (session('status') === 'announcement-deleted')
            Toast.fire({
                icon: 'success',
                title: 'Success',
                text: 'Announcement deleted successfully.'
            });
        @endif
        /* ================= VALIDATION ERRORS ================= */
        @if ($errors->any())
            Toast.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please fix the errors and try again.'
            });
        @endif
    });
</script>
@endsection
