@extends('admin.partials.layouts')
@section('content')
@section('title', 'Dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Dashboard</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>                            
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                            <i class="iconoir-community fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Total Customers</p>
                            <p class="mb-0 text-truncate text-muted"><span class="text-success">8.5%</span>
                                Increase from last month</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">                                        
                            <h3 class="mt-2 mb-0 fw-bold">2500</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{asset('assets/images/extra/line-chart.png')}}" alt="" class="img-fluid">
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                            <i class="iconoir-cart fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Order Now</p>
                            <p class="mb-0 text-truncate text-muted"><span class="text-success">1.7%</span>
                                Increase from last month</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">                                        
                            <h3 class="mt-2 mb-0 fw-bold">865</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{asset('assets/images/extra/bar.png')}}" alt="" class="img-fluid">
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                            <i class="iconoir-hourglass fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Reserve a Unit</p>
                            <p class="mb-0 text-truncate text-muted"><span class="text-danger">0.7%</span>
                                Decrease from last month</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">                                        
                            <h3 class="mt-2 mb-0 fw-bold">155</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{asset('assets/images/extra/donut.png')}}" alt="" class="img-fluid">
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                            <i class="iconoir-hexagon-dice fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate">
                            <p class="text-dark mb-0 fw-semibold fs-14">Total Branches</p>
                            <p class="mb-0 text-truncate text-muted"><span class="text-success">2.7%</span>
                                Increase from last month</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">                                        
                            <h3 class="mt-2 mb-0 fw-bold">65</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{asset('assets/images/extra/tree.png')}}" alt="" class="img-fluid">
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>                  
    </div>
    
    <div class="row justify-content-center">
        {{-- <div class="col-md-6 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Monthly Avg. Income</h4>                      
                        </div><!--end col-->
                        <div class="col-auto"> 
                            <div class="dropdown">
                                <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="icofont-calendar fs-5 me-1"></i> This Month<i class="las la-angle-down ms-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Today</a>
                                    <a class="dropdown-item" href="#">Last Week</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>               
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div id="monthly_income" class="apex-charts"></div>                                
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div> --}}
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Customers Overview</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div id="apex_area1" class="apex-charts"></div>           
                </div><!--end card-body--> 
            </div><!--end card--> 
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">Customers Insights</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div id="apex_column2" class="apex-charts"></div>
                </div><!--end card-body--> 
            </div><!--end card--> 
        </div>
    </div>
    <div class="row ">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">                      
                            <h4 class="card-title">System Logs</h4>                      
                        </div><!--end col-->
                    </div>  <!--end row-->                                  
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="tracking-list">
                        <div class="tracking-item">
                            <div class="tracking-icon icon-inner">                                                 
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="tracking-date">Sep 18, 2024<span class="d-block fs-12 text-muted">05:01 PM</span></div>
                            <p class="mb-0 text-muted">It is a long established fact that a reader will be distracted.</p>
                        </div>
                        <div class="tracking-item">
                            <div class="tracking-icon icon-inner">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="tracking-date">Aug 10, 2024<span  class="d-block fs-12 text-muted">11:19 AM</span></div>
                            <p class="mb-0 text-muted">There are many variations of passages of Lorem Ipsum available, but the majority </p>
                        </div>
                        <div class="tracking-item">
                            <div class="tracking-icon icon-inner">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="tracking-date">Aug 10, 2024<span  class="d-block fs-12 text-muted">11:19 AM</span></div>
                            <p class="mb-0 text-muted">There are many variations of passages of Lorem Ipsum available, but the majority </p>
                            </div>
                            <div class="tracking-item">
                            <div class="tracking-icon icon-inner">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="tracking-date">Aug 10, 2024<span  class="d-block fs-12 text-muted">11:19 AM</span></div>
                            <p class="mb-0 text-muted">There are many variations of passages of Lorem Ipsum available, but the majority </p>
                            </div>
                        
                        <div class="tracking-item">
                            <div class="tracking-icon icon-inner">
                                <i class="fas fa-circle"></i>
                            </div>
                            <div class="tracking-date">Jul 06, 2024<span  class="d-block fs-12 text-muted">02:02 PM</span></div>
                            <p class="mb-0 text-muted">The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested.</p>
                        </div>
                        </div>
                </div><!--end card-body-->
            </div><!--end card--> 
        </div> <!--end col--> 
    </div>
</div>
        <script src=" {{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
        <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
        <script src="https://apexcharts.com/samples/assets/ohlc.js"></script>
        <script src=" {{asset('assets/js/pages/apexcharts.init.js')}}"></script>
@endsection