@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Mountain School Booking List')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <style type="text/css">
        .nounderline{
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@endsection

@inject('dashboard', 'App\Http\Controllers\DashboardController')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('cabinownerMountainSchoolBooking.heading')
                <small>@lang('cabinownerMountainSchoolBooking.subHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('cabinownerMountainSchoolBooking.breadcrumbDashboard')</a></li>
                <li><a href="/cabinowner/bookings"><i class="fa fa-table"></i> @lang('cabinownerMountainSchoolBooking.breadcrumbBooking')</a></li>
                <li class="active">@lang('cabinownerMountainSchoolBooking.breadcrumbMountainSchool')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('cabinownerMountainSchoolBooking.detailsHeading')</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="responseMessage"></div>
                            <div class="row">
                                <div class="col-md-3 pull-left">
                                    <div class="form-group">
                                        <label>@lang('bookingStatistics.panelLabelCabin'): </label>
                                        <select class="form-control admin_cabins_list" style="width: 100%;" id="cabin">
                                            <option></option>
                                            @foreach($dashboard->cabins() as $cabin )
                                                <option>{{$cabin->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>

                                <!-- Date range -->
                                <div class="col-md-3 pull-left">
                                    <div class="form-group">
                                        <label>@lang('bookingStatistics.panelLabelDateRange'): </label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="daterange_Mschool" placeholder="Zeitraum Auswahl">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <!-- /.form group -->
                                </div>

                                <div class="col-md-2 pull-left">
                                    <div class="form-group">
                                        <label></label>
                                        <div class="input-group">
                                            <button type="button" class="btn btn-primary" id="generateAdminBookings" data-loading-text="Generating..." autocomplete="off">@lang('statisticsAdmin.generateButton')</button>
                                        </div>
                                    </div>
                                    <!-- /.form group -->
                                </div>
                            </div>


                            <table id="booking_data_mschool" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.bookingNumber')</th>
                                    {{--<th>@lang('cabinownerMountainSchoolBooking.ind_notice')</th>--}}
                                    <th>@lang('cabinownerMountainSchoolBooking.lastName')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.firstName')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.email')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.from')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.to')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.beds')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.dorms')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.sleeps')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.status')</th>
                                    {{--<th>@lang('cabinownerMountainSchoolBooking.answered')</th>--}}
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input-mschool" placeholder="@lang('cabinownerMountainSchoolBooking.searchBoookingNo')"></th>
                                    {{--<th id="2"></th>--}}
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="5"  class="form-control input-sm search-input-mschool" placeholder="@lang('cabinownerMountainSchoolBooking.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select class="form-control input-sm search-input-mschool" id="11">
                                            <option value="">(@lang('cabinownerMountainSchoolBooking.SearchStatus'))</option>
                                            <option value="1">@lang('cabinownerMountainSchoolBooking.bookingFix')</option>
                                            <option value="2">@lang('cabinownerMountainSchoolBooking.cancelled')</option>
                                            <option value="3">@lang('cabinownerMountainSchoolBooking.completed')</option>
                                            <option value="4">@lang('cabinownerMountainSchoolBooking.request')</option>
                                            <option value="5">@lang('cabinownerMountainSchoolBooking.bookingWaiting')</option>
                                        </select>
                                    </td>
                                    {{--<td></td>--}}
                                </tr>
                                </tfoot>
                            </table>
                            <!-- Export buttons are append here -->
                            <div class="panel-body">
                                <div id="buttons"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

@endsection

@section('scripts')
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <!-- Mountain School Booking Js -->
    <script src="{{ asset('js/mSchoolBookings.js') }}"></script>
@endsection