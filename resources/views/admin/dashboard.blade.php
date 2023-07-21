@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_card.css') }}">

    <style>
        #birthdate-error { margin-right: 1rem !important; }

        .datepicker table tr td.disabled, 
        .datepicker table tr td.disabled:hover {
            cursor: not-allowed !important;
        }

        #status_chart, #bhw_chart {
            width: 100%;
            height: 430px;
        }
    </style>
@endsection

@section('contents')

    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome to Menstrual Monitoring App!</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-toggle="modal" data-target="#newFeminineModal">
                <i class="btn-icon-prepend" data-feather="user-plus"></i>
                Add New Feminine
            </button>
        </div>
    </div>

    <div class="stretch-card">
        <div class="row flex-grow">
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-blue shadow-sm">
                    <div class="inner">
                        <h3 id="feminine_count">{{ $count['feminine_count'] }}</h3>
                        <p>Feminine Registered</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <a href="{{ URL::to('admin/feminine-list') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-green shadow-sm">
                    <div class="inner">
                        <h3 id="active_feminine_count">{{ $count['active_feminine_count'] }}</h3>
                        <p>Total Active</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-cyan shadow-sm">
                    <div class="inner">
                        <h3 id="inactive_feminine_count">{{ $count['inactive_feminine_count'] }}</h3>
                        <p>Total Inactive</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Feminine Status Chart</h5>
                    <div id="status_chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newFeminineModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="newFeminineLbl" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newFeminineLbl">Register New Feminine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        @include('forms.add_new_form')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/template/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/form_validation.js') }}"></script>

    <script src="{{ asset('assets/amcharts/amcharts.com_lib_5_index.js') }}"></script>
    <script src="{{ asset('assets/amcharts/amcharts.com_lib_5_percent.js') }}"></script>
    <script src="{{ asset('assets/amcharts/amcharts.com_lib_5_themes_Animated.js') }}"></script>

    <script src="{{ asset('assets/js/admin/pie_chart.js') }}"></script>
@endsection