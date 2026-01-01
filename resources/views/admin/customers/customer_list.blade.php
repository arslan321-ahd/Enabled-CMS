@extends('admin.partials.layouts')
@section('content')
@section('title', 'Customers List')
    <link href="{{asset('assets/libs/simple-datatables/style.css')}}" rel="stylesheet" type="text/css" />
    <div class="container-fluid"> 
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Customers List</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                            </li>
                            <li class="breadcrumb-item active">Customers List</li>
                        </ol>
                    </div>                                
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">                      
                                <h4 class="card-title">Customers</h4>                      
                            </div><!--end col-->
                            <div class="col-auto"> 
                                <form class="row g-2">
                                    <div class="col-auto">
                                        <a class="btn bg-primary-subtle text-primary dropdown-toggle d-flex align-items-center arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" data-bs-auto-close="outside">
                                            <i class="iconoir-download me-1"></i> Export
                                        </a>
                                        {{-- <div class="dropdown-menu dropdown-menu-start">
                                            <div class="p-2">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" checked id="filter-all">
                                                    <label class="form-check-label" for="filter-all">
                                                        All 
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" checked id="filter-one">
                                                    <label class="form-check-label" for="filter-one">
                                                        New
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" checked id="filter-two">
                                                    <label class="form-check-label" for="filter-two">
                                                        Active
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" checked id="filter-three">
                                                    <label class="form-check-label" for="filter-three">
                                                        Inactive
                                                    </label>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div><!--end col-->
                                    
                                    <div class="col-auto">
                                        <a href="{{route('admin.customers.add')}}" class="btn btn-primary d-flex align-items-center"> <i class="fa-solid fa-plus me-1"></i> Add Customer</a>
                                    </div>
                                </form>    
                            </div><!--end col-->
                        </div><!--end row-->                                  
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        
                        <div class="table-responsive">
                            <table class="table mb-0 checkbox-all" id="datatable_1">
                                <thead class="table-light">
                                    <tr>
                                    <th style="width: 16px;">
                                        <div class="form-check mb-0 ms-n1">
                                            <input type="checkbox" class="form-check-input" name="select-all" id="select-all">                                                    
                                        </div>
                                    </th>
                                    <th class="ps-0">Full Name</th>
                                    <th>Email</th>
                                    <th>Contact No</th>
                                    <th>Order</th>
                                    <th>Order Type</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width: 16px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="check"  id="customCheck1">
                                            </div>
                                        </td>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <span class="thumb-lg d-flex justify-content-center align-items-center 
                                                            bg-purple-subtle text-purple rounded-circle me-2">
                                                    AT
                                                </span>
                                                <span class="font-13 fw-medium">
                                                    Andy Timmons
                                                </span>
                                            </div>
                                        </td>

                                        <td><a href="" class="d-inline-block align-middle mb-0 text-body">dummy@dummy.com</a> </td>
                                        <td>(+1) 123 456 789</td>
                                        <td>Eco Flow</td>
                                        <td>Order Now</td>
                                        <td>$2,500</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success" data-bs-toggle=".orderSummaryModal" data-bs-target="#orderSummaryModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Order Summary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 16px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="check"  id="customCheck1">
                                            </div>
                                        </td>
                                        <td class="ps-0">
                                            <div class="d-flex align-items-center">
                                                <span class="thumb-lg d-flex justify-content-center align-items-center 
                                                            bg-purple-subtle text-purple rounded-circle me-2">
                                                    AD
                                                </span>
                                                <span class="font-13 fw-medium">
                                                    Andri Daniels
                                                </span>
                                            </div>
                                        </td>

                                        <td><a href="" class="d-inline-block align-middle mb-0 text-body">dummy@dummy.com</a> </td>
                                        <td>(+1) 123 456 789</td>
                                        <td>Eco Flow</td>
                                        <td>Order Now</td>
                                        <td>$2,500</td>
                                        <td>
                                            <a href="#" data-bs-toggle="modal" data-bs-target=".orderSummaryModal" class="btn btn-sm btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Order Summary"><i class="fa-solid fa-eye"></i></a>
                                            <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>                                                       
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                 
    </div>
    <!-- Order Summary Modal -->
    <div class="modal fade orderSummaryModal" id="" tabindex="-1" aria-labelledby="orderSummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-dark" id="orderSummaryModalLabel">Order Summary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.customers.order_summary')
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/libs/simple-datatables/umd/simple-datatables.js')}}"></script>
    <script src="{{asset('assets/js/pages/datatable.init.js')}}"></script>
@endsection
