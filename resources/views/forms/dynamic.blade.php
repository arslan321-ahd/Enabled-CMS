<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                            @foreach ($form->fields as $field)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        {{ $field->label }} *
                                    </label>
                                    @if ($field->type === 'select' && $field->name === 'brand')
                                        <select name="brand" class="form-control">
                                            <option value="">Select Brand</option>

                                            {{-- Database Brands --}}
                                            @foreach ($brands as $brand)
                                                <option value="brand_db_{{ $brand->id }}">
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach

                                            {{-- Admin Added Options --}}
                                            @foreach ($field->options ?? [] as $opt)
                                                <option value="brand_custom_{{ $opt }}">
                                                    {{ $opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($field->type === 'select' && $field->name === 'use_case')
                                        <select name="use_case" class="form-control">
                                            <option value="">Select Use Case</option>

                                            {{-- Database Use Cases --}}
                                            @foreach ($useCases as $useCase)
                                                <option value="usecase_db_{{ $useCase->id }}">
                                                    {{ $useCase->name }}
                                                </option>
                                            @endforeach

                                            {{-- Admin Added Options --}}
                                            @foreach ($field->options ?? [] as $opt)
                                                <option value="usecase_custom_{{ $opt }}">
                                                    {{ $opt }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- NORMAL SELECT --}}
                                    @elseif ($field->type === 'select')
                                        <select name="{{ $field->name }}" class="form-control">
                                            <option value="">Select</option>

                                            @foreach ($field->options ?? [] as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>

                                        {{-- TEXTAREA --}}
                                    @elseif ($field->type === 'textarea')
                                        <textarea name="{{ $field->name }}" class="form-control" rows="3"></textarea>

                                        {{-- INPUT --}}
                                    @else
                                        <input type="{{ $field->type }}" name="{{ $field->name }}"
                                            class="form-control">
                                    @endif
                                </div>
                            @endforeach
                            <div class="mt-4">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="emailCampaign">
                                    <label class="form-check-label" for="emailCampaign">
                                        I agree to the collection and use of my personal information as required under
                                        the Data Pricacy Act of 2012.
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dataConsent" required>
                                    <label class="form-check-label" for="dataConsent">
                                        I consent to receive marketing compaigns and promotional materials via email.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-2">
                            <button class="btn px-5 text-white" style="background-color:#09367e;">
                                Submit
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('dynamicForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('form.submit', $form->slug) }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    const data = await response.json();
                    throw data;
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message || 'form successfully submit',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
            })
            .catch(error => {

                let message = 'Something went wrong';

                if (error?.errors) {
                    message = Object.values(error.errors)[0][0];
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000
                });
            });
    });
</script>
