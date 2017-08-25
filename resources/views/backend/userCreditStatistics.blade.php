@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: User Credit Statistics')

@section('css')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
@endsection

@inject('dashboard', 'App\Http\Controllers\DashboardController')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('userCreditStatistics.heading')
                <small>@lang('userCreditStatistics.subHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('userCreditStatistics.breadcrumbOne')</a></li>
                <li class="active">@lang('userCreditStatistics.breadcrumbTwo')</li>
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
                            <i class="fa fa-th"></i>
                            <h3 class="box-title">@lang('userCreditStatistics.panelHeading')</h3>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body">
                            <!-- Date range -->
                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('userCreditStatistics.panelLabelDateRange'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="date_user_status_stat" placeholder="@lang('userCreditStatistics.dateRangePlaceholder')">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2 pull-left">
                                <div class="form-group">
                                    <label></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-primary" id="generateUserStatusStat" data-loading-text="Generating..." autocomplete="off">@lang('userCreditStatistics.generateButton')</button>
                                    </div>
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-4 pull-right alertUserStatusStat">
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <!-- Chart JS - User status statistics -->
                        <div class="box-footer" id="graphUserStatusStat">
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
    <!-- datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chartJS/Chart.min.js') }}"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <!-- Statistics Js -->
    <script src="{{ asset('js/userCreditStatistics.js') }}"></script>
@endsection