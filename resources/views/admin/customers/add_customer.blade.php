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
                                    </div>  <!--end row-->                                  
                                </div><!--end card-header-->
                                <div class="card-body pt-0">
                                    <form>
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
                                                <label class="form-label">City / Province <span class="text-muted">(Optional)</span></label>
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
                                    </form>
               
                                </div><!--end card-body--> 
                            </div><!--end card--> 
                        </div>                                                    
                    </div>
                </div>
@endsection