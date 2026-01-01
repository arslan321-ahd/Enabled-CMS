@extends('admin.partials.layouts')
@section('content')
@section('title', 'Calendar')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Calendar</h4>
                <div class="">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Enable</a>
                        </li>
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </div>                                
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>
                    <div style='clear:both'></div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div> <!-- end col -->
    </div>
</div>
<script src="{{asset('assets/libs/fullcalendar/index.global.min.js')}}"></script>
<script src="{{asset('assets/js/pages/calendar.init.js')}}"></script>
@endsection