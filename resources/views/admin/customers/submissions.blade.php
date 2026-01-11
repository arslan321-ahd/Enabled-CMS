@extends('admin.partials.layouts')
@section('content')
@section('title', 'Form Submissions')

@php
    // Get the first 4 fields from the form
    $firstFourFields = $form->fields->sortBy('order')->take(4);
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Submissions for: {{ $form->title }}</h4>
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Back to Forms
                    </a>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Form Details</h6>
                                    <p class="mb-1"><strong>Form Name:</strong> {{ $form->title }}</p>
                                    <p class="mb-1"><strong>Total Submissions:</strong> {{ $submissions->total() }}
                                    </p>
                                    <p class="mb-1"><strong>Total Fields:</strong> {{ $form->fields->count() }}</p>
                                    <p class="mb-0"><strong>Status:</strong>
                                        <span class="badge bg-{{ $form->active ? 'success' : 'danger' }}">
                                            {{ $form->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            @if ($firstFourFields->count() > 0)
                                <div class="card mt-3">
                                    <div class="card-header bg-info text-white py-2">
                                        <h6 class="mb-0">Showing Fields in Table:</h6>
                                    </div>
                                    <div class="card-body p-2">
                                        <ol class="mb-0 ps-3">
                                            @foreach ($firstFourFields as $field)
                                                <li><small>{{ $field->label }}</small></li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-12">
                            @if ($submissions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table mb-0 checkbox-all" id="datatable_1">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Submitted At</th>

                                                {{-- Dynamic headers for first 4 fields --}}
                                                @foreach ($firstFourFields as $field)
                                                    <th>{{ $field->label }}</th>
                                                @endforeach

                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissions as $submission)
                                                @php
                                                    // Get all values for this submission indexed by field_id
                                                    $submissionValues = [];
                                                    foreach ($submission->values as $value) {
                                                        $submissionValues[$value->form_field_id] = $value->value;
                                                    }
                                                @endphp

                                                <tr>
                                                    <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                                                    </td>
                                                    <td>
                                                        <small>{{ $submission->created_at->format('d M Y') }}</small><br>
                                                        <small
                                                            class="text-muted">{{ $submission->created_at->format('h:i A') }}</small>
                                                    </td>

                                                    {{-- Display first 4 field values --}}
                                                    @foreach ($firstFourFields as $field)
                                                        <td>
                                                            @if (isset($submissionValues[$field->id]))
                                                                @php
                                                                    $value = $submissionValues[$field->id];
                                                                    // Truncate long values
                                                                    if (strlen($value) > 50) {
                                                                        $value = substr($value, 0, 50) . '...';
                                                                    }
                                                                @endphp
                                                                {{ $value }}
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach

                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-sm btn-info view-submission"
                                                            data-bs-toggle="modal" data-bs-target="#submissionModal"
                                                            data-date="{{ $submission->created_at->format('d M Y, h:i A') }}"
                                                            data-values="{{ json_encode($submission->values) }}">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{ $submissions->links() }}
                            @else
                                <div class="alert alert-info">
                                    <i class="fa-solid fa-info-circle"></i> No submissions found for this form.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for viewing complete submission details -->
<div class="modal fade" id="submissionModal" tabindex="-1" aria-labelledby="submissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="submissionModalLabel">Form Submission Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title text-center" id="modal-form-name">{{ $form->name }}</h4>
                            </div>
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div>
                            <!-- Submission Date -->
                            <div class="d-flex justify-content-between mb-2">
                                <p class="text-body fw-semibold">
                                    <i class="iconoir-calendar text-secondary fs-20 align-middle me-1"></i>
                                    Submitted At :
                                </p>
                                <p class="text-body-emphasis fw-semibold" id="modal-date"></p>
                            </div>

                            <!-- Dynamic Form Fields -->
                            <div id="modal-fields-container">
                                <!-- Fields will be populated via JavaScript -->
                            </div>
                        </div>
                    </div><!--card-body-->
                </div><!--end card-->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.view-submission');

        // Define icon mapping for different field types
        const fieldIcons = {
            'text': 'iconoir-edit-pencil',
            'textarea': 'iconoir-align-left',
            'email': 'iconoir-mail',
            'number': 'iconoir-hashtag',
            'tel': 'iconoir-phone',
            'date': 'iconoir-calendar',
            'select': 'iconoir-list',
            'checkbox': 'iconoir-check-circle',
            'radio': 'iconoir-radio',
            'file': 'iconoir-attachment',
            'default': 'iconoir-info-circle'
        };

        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Set submission date
                document.getElementById('modal-date').textContent = this.dataset.date;

                // Parse form data
                const values = JSON.parse(this.dataset.values);
                const container = document.getElementById('modal-fields-container');
                container.innerHTML = '';

                if (values && values.length > 0) {
                    // Sort values by field order or name
                    values.sort((a, b) => {
                        const orderA = a.field?.order || 0;
                        const orderB = b.field?.order || 0;
                        return orderA - orderB;
                    });

                    // Create HTML for each field
                    values.forEach(item => {
                        const fieldType = item.field?.type || 'default';
                        const iconClass = fieldIcons[fieldType] || fieldIcons.default;

                        // Format the value for display
                        let displayValue = item.value ||
                            '<span class="text-muted">Empty</span>';

                        // For arrays (like checkboxes), join with comma
                        if (Array.isArray(item.value)) {
                            displayValue = item.value.join(', ');
                        }

                        // For textarea, show with line breaks
                        if (fieldType === 'textarea' && item.value) {
                            displayValue = item.value.replace(/\n/g, '<br>');
                        }

                        // Truncate very long values
                        if (typeof displayValue === 'string' && displayValue.length >
                            100 && fieldType !== 'textarea') {
                            displayValue = displayValue.substring(0, 100) + '...';
                        }

                        // Create field HTML
                        const fieldHtml = `
                            <div class="d-flex justify-content-between mb-2">
                                <p class="text-body fw-semibold">
                                    <i class="${iconClass} text-secondary fs-20 align-middle me-1"></i>
                                    ${item.field?.label || item.field?.name || 'Unknown Field'} :
                                </p>
                                <p class="text-body-emphasis fw-semibold text-end" style="max-width: 60%; word-break: break-word;">
                                    ${displayValue}
                                </p>
                            </div>
                        `;

                        container.innerHTML += fieldHtml;
                    });
                } else {
                    container.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="iconoir-info-circle fs-40 mb-2"></i>
                            <p class="mb-0">No form data available</p>
                        </div>
                    `;
                }
            });
        });
    });
</script>

{{-- <style>
    .view-submission:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }

    /* Responsive table */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }

        .table th,
        .table td {
            padding: 0.5rem;
        }
    }

    /* Modal styling */
    .modal-body .card {
        border: 1px solid #e9ecef;
        border-radius: 0.5rem;
    }

    .modal-body .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem;
    }

    .modal-body .card-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 0;
    }

    .modal-body .text-body.fw-semibold {
        color: #6c757d;
        min-width: 40%;
    }

    .modal-body .text-body-emphasis.fw-semibold {
        color: #495057;
    }
</style> --}}
@endsection
