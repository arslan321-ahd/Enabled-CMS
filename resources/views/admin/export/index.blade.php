@extends('admin.partials.layouts')
@section('content')
@section('title', 'Export Data')

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Export Data</h4>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">Enable</li>
                    <li class="breadcrumb-item active">Export</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Export</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('admin.export.download') }}" id="exportForm">
                        @csrf

                        {{-- CATEGORY --}}
                        <div class="mb-4">
                            <label class="form-label">Select Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" required
                                onchange="toggleCategoryFilters()">
                                <option value="">-- Select Category --</option>
                                <option value="brands">Brands</option>
                                <option value="taggings">Taggings</option>
                                <option value="use_cases">Use Cases</option>
                                <option value="customer_list">Customer List (Form Submissions)</option>
                            </select>
                        </div>
                        {{-- BRANDS FILTER --}}
                        <div id="brandsFilters" class="category-filters">
                            <label class="form-label">Brand Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- TAGGINGS FILTER --}}
                        <div id="taggingsFilters" class="category-filters">
                            <label class="form-label">Tagging Status</label>
                            <select name="status" class="form-control">
                                <option value="">All</option>
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>

                        {{-- USE CASES FILTER --}}
                        <div id="use_casesFilters" class="category-filters">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Select Brand</label>
                                    <select name="brand_id" class="form-control">
                                        <option value="">All Brands</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Use Case Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- CUSTOMER LIST FILTER --}}
                        <div id="customer_listFilters" class="category-filters">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Select Form</label>
                                    <select name="form_id" class="form-control">
                                        <option value="all">All Forms</option>
                                        @foreach ($forms as $form)
                                            <option value="{{ $form->id }}">{{ $form->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- DATE FILTER --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                        {{-- EXPORT BUTTON --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-file-excel me-2"></i> Export Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    function toggleCategoryFilters() {
        document.querySelectorAll('.category-filters').forEach(el => el.style.display = 'none');

        const category = document.getElementById('category').value;
        if (!category) return;

        const target = document.getElementById(category + 'Filters');
        if (target) target.style.display = 'block';
    }
</script>

<style>
    .category-filters {
        display: none;
        margin-bottom: 20px;
    }
</style>
@endsection
