@extends('admin.partials.layouts')
@section('content')
@section('title', 'Add Customer')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Add Customer</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Add Customer</li>
                    </ol>
                </div>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Altitude - SM MegaMall</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <form method="POST" action="{{ route('forms.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label>Select User</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- Select User --</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Form Title --}}
                            <div class="col-4 mb-3">
                                <label>Form Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter form title"
                                    required>
                            </div>

                            {{-- Form Logo --}}
                            <div class="col-4 mb-3">
                                <label>Form Logo</label>
                                <input type="file" name="logo" class="form-control">
                            </div>
                        </div>
                        {{-- Dynamic Fields Wrapper --}}
                        <div id="fields-wrapper">
                            <div class="row">
                                <div class="field-group mb-3 d-flex align-items-end">
                                    <div class="col-4 me-2">
                                        <label>Field Label</label>
                                        <input type="text" name="fields[0][label]" class="form-control" required>
                                    </div>

                                    <div class="col-2 me-2">
                                        <label>Field Type</label>
                                        <select name="fields[0][type]" class="form-control">
                                            <option value="text">Text</option>
                                            <option value="email">Email</option>
                                            <option value="number">Number</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="select">Select</option>
                                        </select>
                                    </div>

                                    <div class="col-4 me-2">
                                        <label>Validation Rules</label>
                                        <input type="text" name="fields[0][validation]" class="form-control"
                                            placeholder="required|string|max:255">
                                    </div>

                                    <div class="buttons">
                                        <button type="button" class="btn btn-success btn-add">+</button>
                                        <button type="button" class="btn btn-danger btn-remove">−</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save Form</button>
                    </form>


                    {{-- <form>
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter full name" required>
                            </div>

                            <!-- Contact Number -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" placeholder="Enter contact number" required>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-mail Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" placeholder="Enter email address" required>
                            </div>

                            <!-- City / Province -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City / Province <span
                                        class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" placeholder="Enter city or province">
                            </div>

                            <!-- Order -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Order reference" required>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" placeholder="Enter total amount" required>
                            </div>

                            <!-- Order Type -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Type <span class="text-danger">*</span></label>
                                <select class="form-select" required>
                                    <option value="" selected disabled>Select order type</option>
                                    <option value="order_now">Order Now</option>
                                    <option value="reserve_unit">Reserve a Unit</option>
                                </select>
                            </div>

                            <!-- Where did you hear about us -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Where did you hear about us?</label>
                                <select class="form-select">
                                    <option value="" selected disabled>Select source</option>
                                    <option value="facebook">Facebook</option>
                                    <option value="instagram">Instagram</option>
                                    <option value="google">Google</option>
                                    <option value="friend">Friend / Referral</option>
                                    <option value="website">Website</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('admin.customers.list') }}" class="btn btn-danger">
                                Cancel
                            </a>
                        </div>
                    </form> --}}

                </div><!--end card-body-->
            </div><!--end card-->
        </div>
    </div>
</div>
<script>
    let fieldIndex = 1; // next index for new fields

    document.addEventListener('click', function(e) {
        // Add new field group
        if (e.target && e.target.classList.contains('btn-add')) {
            const wrapper = document.getElementById('fields-wrapper');
            const newField = document.querySelector('.field-group').cloneNode(true);

            // Update input names and clear values
            newField.querySelectorAll('input, select').forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/\d+/, fieldIndex);
                input.setAttribute('name', newName);
                if (input.tagName === 'INPUT') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
            });

            wrapper.appendChild(newField);
            fieldIndex++;
        }

        // Remove a field group
        if (e.target && e.target.classList.contains('btn-remove')) {
            const wrapper = document.getElementById('fields-wrapper');
            const groups = wrapper.querySelectorAll('.field-group');
            if (groups.length > 1) { // always keep at least one
                e.target.closest('.field-group').remove();
            }
        }
    });
</script>
@endsection
