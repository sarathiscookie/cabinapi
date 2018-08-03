@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Guests Statistics')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('statisticsGuests.heading')
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('statisticsGuests.breadcrumbOne')</a></li>
                <li><i class="fa fa-pie-chart"></i>@lang('statisticsGuests.breadcrumbTwo')</li>
                <li class="active">@lang('statisticsGuests.breadcrumbThree')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">

                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-pie-chart"></i>
                            <h3 class="box-title">@lang('statisticsGuests.panelHeading')</h3>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body">
                            <!-- Date range -->
                            <div class="col-md-4 pull-left">
                                <div class="form-group">
                                    <label>@lang('statisticsGuests.panelLabelDateRange'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="guests_count_stat" class="form-control pull-right" id="guests_count_stat" placeholder="@lang('statisticsGuests.dateRangePlaceholder')" value="{{ $defaultDate }}">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2 pull-left">
                                <div class="form-group">
                                    <label></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-primary" id="generateGuestsCountStat" data-loading-text="loading..." autocomplete="off">@lang('statisticsGuests.generateButton')</button>
                                    </div>
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-3 pull-left alertGuestsCountStat">
                            </div>

                            <div class="col-md-3 pull-right">
                                <div class="form-group pull-right response_array_sum">
                                </div>
                                <!-- /.form group -->
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <!-- Chart JS - Guest count statistics -->
                        <div class="box-footer" id="graphGuestsCountStat">
                            <canvas id="chartGuestsCountStatistics"></canvas>
                        </div>
                        <!-- /.box-footer -->
                    </div>

                </section>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chartJS/Chart.min.js') }}"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Statistics Js -->
    <script src="{{ asset('js/statisticsGuests.js') }}"></script>
@endsection