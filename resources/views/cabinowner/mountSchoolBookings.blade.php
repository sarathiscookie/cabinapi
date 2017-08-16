@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Mountain School Booking List')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <style type="text/css">
        .nounderline{
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@endsection

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
                                <div class="col-md-3" style="margin-bottom: 20px;">
                                    <div class="pull-left daterange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;" data-toggle="tooltip" data-placement="right" title="Zeitraum Auswahl">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span></span> <b class="caret"></b>
                                    </div>
                                </div>
                            </div>

                            <table id="booking_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.bookingNumber')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.ind_notice')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.lastName')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.firstName')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.email')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.from')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.to')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.beds')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.dorms')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.sleeps')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.status')</th>
                                    <th>@lang('cabinownerMountainSchoolBooking.answered')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('cabinownerMountainSchoolBooking.searchBoookingNo')"></th>
                                    <th id="2"></th>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="5"  class="form-control input-sm search-input" placeholder="@lang('cabinownerMountainSchoolBooking.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select class="form-control input-sm search-input" id="11">
                                            <option value="">(@lang('cabinownerMountainSchoolBooking.SearchStatus'))</option>
                                            <option value="1">@lang('cabinownerMountainSchoolBooking.bookingFix')</option>
                                            <option value="2">@lang('cabinownerMountainSchoolBooking.cancelled')</option>
                                            <option value="3">@lang('cabinownerMountainSchoolBooking.completed')</option>
                                            <option value="4">@lang('cabinownerMountainSchoolBooking.request')</option>
                                            <option value="5">@lang('cabinownerMountainSchoolBooking.bookingWaiting')</option>
                                        </select>
                                    </td>
                                    <td></td>
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

    <!-- Mountain School Booking Js -->
    <script src="{{ asset('js/mountSchoolBookings.js') }}"></script>
@endsection