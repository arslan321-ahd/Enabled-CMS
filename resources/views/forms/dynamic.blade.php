<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Logo --}}
            @if ($form->logo)
                <div class="text-center mb-2">
                    <img src="{{ asset('storage/' . $form->logo) }}" alt="Form Logo" style="max-height: 100px;">
                </div>
            @endif

            {{-- Title --}}
            <div class="text-center mb-4">
                <h4 class="fw-bold text-uppercase">
                    {{ $form->title }}
                </h4>
            </div>

            {{-- Card --}}
            <div class="card shadow-sm">
                <div class="card-body p-3">
                    <form id="dynamicForm">
                        @csrf

                        <div class="row">
                            @php
                                $checkboxFields = [];
                                $otherFields = [];

                                // Separate checkbox fields from other fields
                                foreach ($form->fields as $field) {
                                    if ($field->type === 'checkbox') {
                                        $checkboxFields[] = $field;
                                    } else {
                                        $otherFields[] = $field;
                                    }
                                }
                            @endphp

                            {{-- Display all non-checkbox fields first --}}
                            @foreach ($otherFields as $field)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        {{ $field->label }}
                                        @if ($field->required)
                                            <span class="text-danger">*</span>
                                        @endif
                                    </label>

                                    {{-- Dynamic Select Field (tagging, brand, usecases) --}}
                                    @if ($field->type === 'select' && $field->data_source)
                                        @php
                                            // Fetch data based on data_source
                                            $dynamicOptions = [];
                                            switch ($field->data_source) {
                                                case 'tagging':
                                                    $dynamicOptions = \App\Models\Tagging::where(
                                                        'status',
                                                        'online',
                                                    )->get();
                                                    break;
                                                case 'brand':
                                                    $dynamicOptions = \App\Models\Brand::where(
                                                        'status',
                                                        'active',
                                                    )->get();
                                                    break;
                                                case 'usecases':
                                                    $dynamicOptions = \App\Models\UseCase::with('brand')
                                                        ->where('status', 'active')
                                                        ->get()
                                                        ->map(function ($useCase) {
                                                            return (object) [
                                                                'id' => $useCase->id,
                                                                'name' =>
                                                                    $useCase->name .
                                                                    ' (' .
                                                                    ($useCase->brand->name ?? '') .
                                                                    ')',
                                                            ];
                                                        });
                                                    break;
                                            }
                                        @endphp

                                        <select name="{{ $field->name }}" class="form-control"
                                            @if ($field->required) required @endif>
                                            <option value="">-- Select {{ $field->label }} --</option>
                                            @foreach ($dynamicOptions as $option)
                                                <option value="{{ $option->id }}">
                                                    {{ $option->name ?? $option->source }}</option>
                                            @endforeach
                                        </select>

                                        {{-- Custom Select Field (manual options) --}}
                                    @elseif ($field->type === 'select' && $field->options)
                                        <select name="{{ $field->name }}" class="form-control"
                                            @if ($field->required) required @endif>
                                            <option value="">Select {{ $field->label }}</option>
                                            @foreach (json_decode($field->options, true) ?? [] as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>

                                        {{-- Textarea Field --}}
                                    @elseif ($field->type === 'textarea')
                                        <textarea name="{{ $field->name }}" class="form-control" rows="3"
                                            @if ($field->required) required @endif></textarea>

                                        {{-- Checkbox Field (will be shown at the end) --}}
                                    @elseif ($field->type === 'checkbox')
                                        {{-- Handled separately at the end --}}

                                        {{-- Input Fields (text, email, number) --}}
                                    @else
                                        <input type="{{ $field->type }}" name="{{ $field->name }}"
                                            class="form-control" @if ($field->required) required @endif>
                                    @endif

                                    {{-- Validation error placeholder --}}
                                    <div class="invalid-feedback" id="error-{{ $field->name }}"></div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Checkbox Fields (shown at the end) --}}
                        @if (count($checkboxFields) > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="fw-bold mb-3">Terms & Conditions:</h6>
                                    @foreach ($checkboxFields as $checkbox)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox"
                                                name="{{ $checkbox->name }}" id="checkbox_{{ $checkbox->id }}"
                                                value="1" @if ($checkbox->required) required @endif>
                                            <label class="form-check-label" for="checkbox_{{ $checkbox->id }}">
                                                {!! nl2br(e($checkbox->checkbox_terms)) !!}
                                                @if ($checkbox->required)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <div class="invalid-feedback" id="error-{{ $checkbox->name }}">
                                                Please accept this to continue.
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <button type="submit" class="btn px-5 text-white" style="background-color:#09367e;">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- Success & Review Section (Hidden by default) --}}
            <div class="row justify-content-center d-none" id="successSection">
                <div class="col-lg-8">
                    <div class="card shadow-sm pb-2 mb-4">
                        <div class="card-body p-4 text-center">
                            {{-- Success Icon --}}
                            <div class="success-icon mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745"
                                    class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                </svg>
                            </div>

                            {{-- Success Message --}}
                            <h4 class="text-success mb-2">Form submitted successfully!</h4>
                            <p class="text-muted mb-4">Thank you for your submission.</p>

                            {{-- Review Section --}}
                            <div class="review-section mt-4 pt-4 border-top">
                                <h5 class="mb-3">How was your experience?</h5>
                                <p class="text-muted mb-4">Your feedback helps us improve</p>

                                {{-- Emoji Ratings --}}
                                <div class="d-flex justify-content-center gap-3 mb-4">
                                    <span class="emoji-rating" data-rating="1"
                                        style="font-size: 2.5rem; cursor: pointer;">😡</span>
                                    <span class="emoji-rating" data-rating="2"
                                        style="font-size: 2.5rem; cursor: pointer;">😕</span>
                                    <span class="emoji-rating" data-rating="3"
                                        style="font-size: 2.5rem; cursor: pointer;">😐</span>
                                    <span class="emoji-rating" data-rating="4"
                                        style="font-size: 2.5rem; cursor: pointer;">🙂</span>
                                    <span class="emoji-rating" data-rating="5"
                                        style="font-size: 2.5rem; cursor: pointer;">😄</span>
                                </div>

                                {{-- Comment Box (Hidden by default) --}}
                                <div class="comment-section d-none mt-4">
                                    <textarea class="form-control" id="reviewComment" rows="4"
                                        placeholder="Tell us more about your experience (optional)..."></textarea>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary" id="submitReview">Submit
                                            Feedback</button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            id="skipReview">Skip</button>
                                    </div>
                                </div>

                                {{-- Already Submitted Message --}}
                                <div class="already-submitted d-none mt-3">
                                    <p class="text-success">Thank you for your feedback!</p>
                                    <a href="{{ url('/') }}" class="btn btn-outline-primary">Go to Homepage</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentSubmissionId = null;
        let selectedRating = 0;
        const form = document.getElementById('dynamicForm');
        const mainFormSection = document.querySelector('.card.shadow-sm');
        const successSection = document.getElementById('successSection');
        const reviewUrl = "{{ url('/user/review') }}";
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitMainForm();
        });
        function submitMainForm() {
            clearErrors();
            let isValid = true;
            form.querySelectorAll('[required]').forEach(field => {
                if (field.type === 'checkbox' && !field.checked) markInvalid(field) && (isValid =
                false);
                else if (field.type !== 'checkbox' && !field.value) markInvalid(field) && (isValid =
                    false);
            });
            if (!isValid) {
                toastError('Please fill all required fields');
                return;
            }
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            fetch("{{ route('form.submit', $form->slug) }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(data => {
                    if (!data.submission_id) throw new Error('Submission ID missing');
                    currentSubmissionId = data.submission_id;
                    mainFormSection.classList.add('d-none');
                    successSection.classList.remove('d-none');
                    successSection.scrollIntoView({
                        behavior: 'smooth'
                    });

                    form.reset();
                })
                .catch(err => handleErrors(err))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                });
        }
        document.querySelectorAll('.emoji-rating').forEach(emoji => {
            emoji.addEventListener('click', function() {
                document.querySelectorAll('.emoji-rating').forEach(e => {
                    e.style.opacity = '0.5';
                    e.style.transform = 'scale(1)';
                });
                this.style.opacity = '1';
                this.style.transform = 'scale(1.2)';
                selectedRating = parseInt(this.dataset.rating, 10);
                document.querySelector('.comment-section')?.classList.remove('d-none');
            });
        });
        document.getElementById('submitReview')?.addEventListener('click', submitReview);
        document.getElementById('skipReview')?.addEventListener('click', showThankYouMessage);
        function submitReview() {
            if (!currentSubmissionId) {
                toastError('Submission not found. Please submit the form first.');
                return;
            }
            if (selectedRating === 0) {
                showThankYouMessage();
                return;
            }
            fetch(reviewUrl, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        submission_id: currentSubmissionId,
                        rating: selectedRating,
                        comment: document.getElementById('reviewComment').value
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(() => showThankYouMessage())
                .catch(() => showThankYouMessage());
        }

        function showThankYouMessage() {
            document.querySelector('.comment-section')?.classList.add('d-none');
            document.querySelector('.already-submitted')?.classList.remove('d-none');
        }
        function clearErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.style.display = 'none');
        }
        function markInvalid(field) {
            field.classList.add('is-invalid');
            const error = document.getElementById(`error-${field.name}`);
            if (error) error.style.display = 'block';
        }
        function handleErrors(err) {
            if (err.errors) {
                Object.keys(err.errors).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) markInvalid(field);
                });
            } else {
                toastError(err.message || 'Something went wrong');
            }
        }

        function toastError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }

    });
</script>
<style>
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }

    .form-check-input.is-invalid {
        border-color: #dc3545;
    }
    .emoji-rating {
        transition: all 0.3s ease;
        opacity: 0.5;
    }
    .emoji-rating:hover {
        opacity: 0.8;
        transform: scale(1.1);
    }
    .success-icon {
        color: #28a745;
    }
    .review-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
    }
    .comment-section textarea {
        resize: none;
    }
    .already-submitted {
        animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }
</style>
