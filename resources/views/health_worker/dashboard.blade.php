@extends('layouts.app')
@section('page-title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard_card.css') }}">
@endsection

@section('contents')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome back {{ Auth::user()->last_name }}!</h4>
        </div>
        <!-- <div class="d-flex align-items-center flex-wrap text-nowrap">
            <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0" data-toggle="modal" data-target="#{{ Auth::user()->menstruation_status == 1 ? 'menstrualPeriodModal' : '404' }}" {{ Auth::user()->menstruation_status == 0 ? 'disabled' : '' }} >
                <i class="btn-icon-prepend fa-solid fa-file-circle-plus"></i>
                Add New Menstruation Period
            </button>
        </div> -->
    </div>

    <div class="stretch-card">
        <div class="row flex-grow">
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-blue shadow-sm">
                    <div class="inner">
                        <h3 id="assigned_feminine_count">{{ $assign_feminine_count }}</h3>
                        <p>Assigned Feminine</p>
                    </div>
                    <div class="icon">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                    <a href="{{ URL::to('health-worker/feminine-list') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('assets/template/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection