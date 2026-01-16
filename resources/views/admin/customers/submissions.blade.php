@extends('admin.partials.layouts')
@section('content')
@section('title', 'Form Submissions')
<link href="{{ asset('assets/libs/simple-datatables/style.css') }}" rel="stylesheet" type="text/css" />
@php
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
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <p class="mb-1"><strong>Form Name:</strong> {{ $form->title }}</p>
                            <p class="mb-1"><strong>Total Submissions:</strong> {{ $submissions->total() }}</p>
                            <p class="mb-1"><strong>Total Fields:</strong> {{ $form->fields->count() }}</p>
                            <p class="mb-0">
                                <strong>Status:</strong>
                                <span class="badge bg-{{ $form->active ? 'success' : 'danger' }}">
                                    {{ $form->active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($submissions->count())
                            <div class="table-responsive">
                                <table class="table mb-0 checkbox-all" id="datatable_1">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Submitted At</th>
                                            @foreach ($firstFourFields as $field)
                                                <th>{{ $field->label }}</th>
                                            @endforeach
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($submissions as $submission)
                                            @php
                                                // Create a map of field_id => display_value for this submission
                                                $displayValues = [];
                                                foreach ($submission->values as $value) {
                                                    $displayValues[$value->form_field_id] = $value->display_value;
                                                }

                                                // Prepare values data for modal
                                                $modalValues = [];
                                                foreach ($submission->values as $value) {
                                                    $modalValues[] = [
                                                        'id' => $value->id,
                                                        'form_field_id' => $value->form_field_id,
                                                        'value' => $value->value,
                                                        'display_value' => $value->display_value,
                                                        'field' => $value->field
                                                            ? [
                                                                'id' => $value->field->id,
                                                                'label' => $value->field->label,
                                                                'type' => $value->field->type,
                                                                'data_source' => $value->field->data_source,
                                                            ]
                                                            : null,
                                                    ];
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <small>{{ $submission->created_at->format('d M Y') }}</small><br>
                                                    <small
                                                        class="text-muted">{{ $submission->created_at->format('h:i A') }}</small>
                                                </td>
                                                @foreach ($firstFourFields as $field)
                                                    <td>{{ $displayValues[$field->id] ?? '-' }}</td>
                                                @endforeach
                                                <td>
                                                    <button class="btn btn-sm btn-info view-submission"
                                                        data-bs-toggle="modal" data-bs-target="#submissionModal"
                                                        data-date="{{ $submission->created_at->format('d M Y, h:i A') }}"
                                                        data-values='@json($modalValues)'
                                                        data-review='@json($submission->review)'>
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
                            <div class="alert alert-info">No submissions found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ================= MODAL ================= --}}
<div class="modal fade" id="submissionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Form Submission Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <p class="fw-semibold">
                                <i class="iconoir-calendar me-1"></i> Submitted At :
                            </p>
                            <p class="fw-semibold" id="modal-date"></p>
                        </div>
                        <div id="modal-fields-container"></div>
                        <div id="modal-review-container" class="d-none">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="fw-semibold mb-0">
                                    <i class="iconoir-star me-1"></i> Rating :
                                </p>
                                <div id="modal-review-stars"></div>
                            </div>
                            <div id="modal-review-comment" class="d-none">
                                <textarea class="form-control" rows="3" readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/libs/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/pages/datatable.init.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const fieldIcons = {
            text: 'iconoir-edit-pencil',
            textarea: 'iconoir-align-left',
            email: 'iconoir-mail',
            number: 'iconoir-hashtag',
            tel: 'iconoir-phone',
            date: 'iconoir-calendar',
            select: 'iconoir-list',
            checkbox: 'iconoir-check-circle',
            radio: 'iconoir-radio',
            file: 'iconoir-attachment',
            default: 'iconoir-info-circle'
        };

        // Function to get display value for dynamic sources
        async function getDynamicDisplayValue(dataSource, value) {
            if (!dataSource || !value) return value;

            try {
                const response = await fetch(`/admin/get-dynamic-value?source=${dataSource}&id=${value}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    return data.name || value;
                }
                return value;
            } catch (error) {
                console.error('Error fetching dynamic value:', error);
                return value;
            }
        }

        document.querySelectorAll('.view-submission').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modal-date').textContent = this.dataset.date;
                const values = JSON.parse(this.dataset.values);
                const review = this.dataset.review ? JSON.parse(this.dataset.review) : null;
                const fieldsContainer = document.getElementById('modal-fields-container');
                fieldsContainer.innerHTML = '';

                // Process values immediately
                values.forEach((item, index) => {
                    const icon = fieldIcons[item.field?.type] || fieldIcons.default;
                    let displayValue = item.display_value || item.value;

                    // Format checkbox values
                    if (item.field?.type === 'checkbox') {
                        displayValue = item.value == 1 ? 'Accepted' : 'Not Accepted';
                    }

                    // Handle empty values
                    if (!displayValue || displayValue === '') {
                        displayValue = '<span class="text-muted">Empty</span>';
                    }

                    fieldsContainer.innerHTML += `
                    <div class="d-flex justify-content-between mb-2" id="field-${index}">
                        <p class="fw-semibold">
                            <i class="${icon} me-1"></i> ${item.field?.label || 'Unknown Field'} :
                        </p>
                        <p class="fw-semibold text-end" style="max-width:60%; word-break: break-word;">
                            ${displayValue}
                        </p>
                    </div>
                `;

                    // If it's a dynamic select field, fetch the display name async
                    if (item.field?.data_source && item.field?.type === 'select' && item
                        .value) {
                        getDynamicDisplayValue(item.field.data_source, item.value)
                            .then(dynamicValue => {
                                const fieldElement = document.getElementById(
                                    `field-${index}`);
                                if (fieldElement) {
                                    const valueElement = fieldElement.querySelector(
                                        '.fw-semibold.text-end');
                                    if (valueElement) {
                                        valueElement.innerHTML = dynamicValue;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error updating dynamic value:',
                                    error);
                            });
                    }
                });

                // Handle review
                const reviewBox = document.getElementById('modal-review-container');
                const starsBox = document.getElementById('modal-review-stars');
                const commentBox = document.getElementById('modal-review-comment');
                const textarea = commentBox.querySelector('textarea');

                reviewBox.classList.add('d-none');
                starsBox.innerHTML = '';
                commentBox.classList.add('d-none');
                textarea.value = '';

                if (review && (review.rating || review.comment)) {
                    reviewBox.classList.remove('d-none');
                    if (review.rating) {
                        for (let i = 1; i <= 5; i++) {
                            starsBox.innerHTML += `
                            <i class="fa-solid fa-star me-1 ${i <= review.rating ? 'text-warning' : 'text-muted'}"></i>
                        `;
                        }
                    }
                    if (review.comment) {
                        commentBox.classList.remove('d-none');
                        textarea.value = review.comment;
                    }
                }
            });
        });
    });
</script>
@endsection
