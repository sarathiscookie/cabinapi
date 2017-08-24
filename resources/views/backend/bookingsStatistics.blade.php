@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Bookings Statistics')

@section('css')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@inject('dashboard', 'App\Http\Controllers\DashboardController')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('bookingStatistics.bookingsHeading')
                <small>@lang('bookingStatistics.controlPanelHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('bookingStatistics.dashboardLabel')</a></li>
                <li><a href="/admin/bookings"><i class="fa fa-table"></i> @lang('bookingStatistics.bookingBreadcrumb')</a></li>
                <li class="active">@lang('bookingStatistics.statisticsBreadcrumb')</li>
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
                            <h3 class="box-title">@lang('bookingStatistics.panelHeading')</h3>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->

                        <!-- Select box for cabins or regions -->
                        <div class="box-body">
                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('bookingStatistics.cabinLabel'): </label>
                                    <select class="form-control cabins_book_statistics" style="width: 100%;" id="cabin">
                                        <option></option>
                                        <option value="allCabins">All Cabins</option>
                                        <optgroup label="Cabins">
                                            @foreach($dashboard->cabins() as $cabin )
                                                <option>{{$cabin->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <!-- Date range -->
                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('bookingStatistics.daterangeLabel'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="daterange_book_statistics" placeholder="@lang('bookingStatistics.dateRangePlaceholder')">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2 pull-left">
                                <div class="form-group">
                                    <label></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-primary" id="generateBookingStat" data-loading-text="Generating..." autocomplete="off">@lang('statisticsAdmin.generateButton')</button>
                                    </div>
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-4 pull-right alertBookingStat">
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <!-- Chart JS - Booking Statistics -->
                        <div class="box-footer" id="graphBookingStatus">
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
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chartJS/Chart.min.js') }}"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            cabinPlaceholder: '{{ trans('bookingStatistics.cabinPlaceholder') }}'
        };
    </script>

    <!-- Statistics Js -->
    <script src="{{ asset('js/bookingsStatistics.js') }}"></script>
@endsection