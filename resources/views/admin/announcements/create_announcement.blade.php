@extends('admin.partials.layouts')
@section('content')
@section('title', 'Post Announcement')
<link rel="stylesheet" href="{{ asset('assets/libs/quill/quill.snow.css') }}">
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Post Announcement</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Post Announcement</li>
                    </ol>
                </div>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">New Announcement</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data"
                        id="announcementForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Announcement Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}"
                                    placeholder="Enter announcement title">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Announcement Category</label>
                                <input type="text" class="form-control" name="category" value="{{ old('category') }}"
                                    placeholder="Enter announcement category">
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="form-label">Attachment</label>
                                <input type="file" class="form-control" name="attachment">
                            </div>
                        </div>

                        {{-- Editor --}}
                        <label class="form-label">Announcement Content</label>
                        <div id="editor" class="border rounded p-2" style="min-height:200px;">
                            {!! old('content') !!}
                        </div>
                        <textarea name="content" id="content" hidden></textarea>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Post Announcement</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/quill/quill.js') }}"></script>
<script src="{{ asset('assets/js/pages/form-editor.init.js') }}"></script>
<script>
    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        // Get the editor content
        let editorContent = document.getElementById('editor').innerHTML;

        // Remove Quill tooltip elements
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = editorContent;

        // Remove all .ql-tooltip elements
        const tooltips = tempDiv.querySelectorAll('.ql-tooltip');
        tooltips.forEach(tooltip => tooltip.remove());

        // Set the cleaned content
        document.getElementById('content').value = tempDiv.innerHTML;
    });
</script>
@endsection
