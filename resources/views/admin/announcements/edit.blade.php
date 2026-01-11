@extends('admin.partials.layouts')
@section('content')
@section('title', 'Post Announcement')
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Edit Announcement</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Announcement</li>
                    </ol>
                </div>
            </div><!--end page-title-box-->
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Edit Announcement</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('announcements.update', $announcement) }}" method="POST"
                        enctype="multipart/form-data" id="announcementEditForm">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Announcement Title</label>
                                <input type="text" class="form-control" name="title"
                                    value="{{ old('title', $announcement->title) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Announcement Category</label>
                                <input type="text" class="form-control" name="category"
                                    value="{{ old('category', $announcement->category) }}">
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label">Attachment</label>
                                <input type="file" class="form-control" name="attachment">
                                @if ($announcement->attachment)
                                    <div class="mt-2">
                                        <p class="mb-1 text-muted">Current Image:</p>
                                        <img src="{{ asset('storage/' . $announcement->attachment) }}"
                                            class="img-thumbnail" style="max-width:150px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <label class="form-label">Announcement Content</label>
                        <div id="editor" class="border rounded p-2 mb-3" style="min-height:200px;">
                            {!! old('content', $announcement->content) !!}
                        </div>
                        <textarea name="content" id="content" hidden></textarea>
                        <button type="submit" class="btn btn-primary mt-3">
                            Update Announcement
                        </button>
                    </form>
                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST"
                        class="delete-announcement-form mt-3 d-flex ">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-delete-announcement">
                            Delete Announcement
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/quill/quill.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('announcementEditForm').addEventListener('submit', function(e) {
        let editorContent = document.getElementById('editor').innerHTML;
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = editorContent;
        const tooltips = tempDiv.querySelectorAll('.ql-tooltip');
        tooltips.forEach(tooltip => tooltip.remove());
        document.getElementById('content').value = tempDiv.innerHTML;
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-delete-announcement').forEach(function(button) {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This announcement will be permanently deleted!',
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
@endsection
