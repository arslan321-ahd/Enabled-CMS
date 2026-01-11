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
                            <p class="text-dark mb-0 fw-semibold fs-14">Total Form Submissions</p>
                            <p class="mb-0 text-truncate text-muted">All customer form submissions</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalSubmissions }}</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{ asset('assets/images/extra/line-chart.png') }}" alt=""
                                class="img-fluid">
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
                            <p class="text-dark mb-0 fw-semibold fs-14">Total Forms</p>
                            <p class="mb-0 text-truncate text-muted">Customer forms created</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalForms }}</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{ asset('assets/images/extra/bar.png') }}" alt="" class="img-fluid">
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
                            <p class="text-dark mb-0 fw-semibold fs-14">Today's Submissions</p>
                            <p class="mb-0 text-truncate text-muted">Submitted today</p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">
                            <h3 class="mt-2 mb-0 fw-bold">{{ $todaySubmissions }}</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{ asset('assets/images/extra/donut.png') }}" alt="" class="img-fluid">
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
                            <p class="mb-0 text-truncate text-muted">
                                Active system branches
                            </p>
                        </div><!--end media-body-->
                    </div><!--end media-->
                    <div class="row d-flex justify-content-center">
                        <div class="col">
                            <!-- Dynamic count from controller -->
                            <h3 class="mt-2 mb-0 fw-bold">{{ $totalBranches }}</h3>
                        </div>
                        <!--end col-->
                        <div class="col align-self-center">
                            <img src="{{ asset('assets/images/extra/tree.png') }}" alt="" class="img-fluid">
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
        <div class="col-md-6 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="card-title">Customers Overview</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
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
                    </div> <!--end row-->
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
                            <h4 class="card-title">Logs</h4>
                        </div><!--end col-->
                    </div> <!--end row-->
                </div><!--end card-header-->
                <div class="card-body pt-0">
                    <div class="tracking-list">

                        @forelse($logs as $log)
                            <div class="tracking-item">
                                <div class="tracking-icon icon-inner">
                                    <i class="fas fa-circle"></i>
                                </div>

                                <div class="tracking-date">
                                    {{ $log->created_at->format('M d, Y') }}
                                    <span class="d-block fs-12 text-muted">
                                        {{ $log->created_at->format('h:i A') }}
                                    </span>
                                </div>

                                <p class="mb-0 text-muted">
                                    <strong>{{ $log->title }}</strong><br>
                                    {{ $log->description }}
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                No logs available
                            </div>
                        @endforelse

                    </div>
                </div>
                <!--end card-body-->
            </div><!--end card-->
        </div> <!--end col-->
    </div>
</div>

{{-- SIMPLIFIED APPROACH: Direct chart initialization --}}
@php
    // Get actual data with fallbacks
    $monthlyLabels = $monthlySubmissions['labels'] ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    $monthlyData = $monthlySubmissions['data'] ?? [0, 0, 0, 0, 0, 0];
    $topFormNames = $topForms['form_names'] ?? ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5', 'Form 6'];
    $topFormCounts = $topForms['submission_counts'] ?? [0, 0, 0, 0, 0, 0];
@endphp

{{-- Load ApexCharts library --}}
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

<script>
    // Initialize charts when everything is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing charts...');
        console.log('Data received:', {
            monthlyLabels: @json($monthlyLabels),
            monthlyData: @json($monthlyData),
            topFormNames: @json($topFormNames),
            topFormCounts: @json($topFormCounts)
        });
        
        // Initialize Area Chart
        if (document.querySelector("#apex_area1")) {
            console.log('Initializing area chart...');
            
            var areaOptions = {
                series: [{
                    name: 'Form Submissions',
                    data: @json($monthlyData)
                }],
                chart: {
                    height: 350,
                    type: 'area',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ["#22c55e"],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: '#e9ecef',
                    strokeDashArray: 3,
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    }
                },
                xaxis: {
                    categories: @json($monthlyLabels),
                    axisBorder: {
                        show: true,
                        color: '#e9ecef'
                    },
                    axisTicks: {
                        show: true,
                        color: '#e9ecef'
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.floor(val);
                        }
                    },
                    title: {
                        text: 'Submissions'
                    }
                },
                tooltip: {
                    theme: 'light',
                    x: {
                        show: true
                    },
                    y: {
                        title: {
                            formatter: function() {
                                return 'Submissions: ';
                            }
                        }
                    }
                },
                title: {
                    text: 'Monthly Form Submissions',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: '#495057'
                    }
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                }
            };
            
            try {
                var areaChart = new ApexCharts(document.querySelector("#apex_area1"), areaOptions);
                areaChart.render();
                console.log('Area chart rendered successfully');
            } catch (error) {
                console.error('Error rendering area chart:', error);
            }
        } else {
            console.error('Area chart element (#apex_area1) not found!');
        }
        
        // Initialize Column Chart
        if (document.querySelector("#apex_column2")) {
            console.log('Initializing column chart...');
            
            var columnOptions = {
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: false,
                        columnWidth: '55%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                colors: ["var(--bs-primary)"],
                series: [{
                    name: 'Submissions',
                    data: @json($topFormCounts)
                }],
                xaxis: {
                    categories: @json($topFormNames),
                    axisBorder: {
                        show: true,
                        color: '#bec7e0',
                    },
                    axisTicks: {
                        show: true,
                        color: '#bec7e0',
                    },
                    labels: {
                        rotate: -45,
                        rotateAlways: true,
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    title: {
                        text: 'Number of Submissions'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " submissions";
                        }
                    }
                },
                title: {
                    text: 'Top Forms by Submissions',
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        color: '#495057'
                    }
                },
                grid: {
                    borderColor: '#f1f3fa'
                }
            };
            
            try {
                var columnChart = new ApexCharts(document.querySelector("#apex_column2"), columnOptions);
                columnChart.render();
                console.log('Column chart rendered successfully');
            } catch (error) {
                console.error('Error rendering column chart:', error);
            }
        } else {
            console.error('Column chart element (#apex_column2) not found!');
        }
    });
</script>

{{-- REMOVE THESE DUPLICATE SCRIPTS --}}
<script src="{{ asset('assets/js/pages/apexcharts.init.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/apexcharts.init.js') }}"></script>

@endsection