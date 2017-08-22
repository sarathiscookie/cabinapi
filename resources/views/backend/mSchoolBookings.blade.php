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
                @lang('adminMschoolBooking.heading')
                <small>@lang('adminMschoolBooking.subHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('adminMschoolBooking.breadcrumbDashboard')</a></li>
                <li class="active">@lang('adminMschoolBooking.breadcrumbMountainSchool')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('adminMschoolBooking.detailsHeading')</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="responseMessage"></div>
                            <div class="row">
                                <div class="col-md-3 pull-left">
                                    <div class="form-group">
                                        <label>@lang('adminMschoolBooking.panelLabelCabin'): </label>
                                        <select class="form-control admin_cabins_list" style="width: 100%;" id="cabin">
                                            <option></option>
                                            <option value="allCabins">All Cabins</option>
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
                                        <label>@lang('adminMschoolBooking.panelLabelDateRange'): </label>

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

                                <div class="col-md-2 pull-left alertMessage">
                                </div>
                            </div>


                            <table id="booking_data_mschool" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('adminMschoolBooking.bookingNumber')</th>
                                    <th>@lang('adminMschoolBooking.lastName')</th>
                                    <th>@lang('adminMschoolBooking.firstName')</th>
                                    <th>@lang('adminMschoolBooking.email')</th>
                                    <th>@lang('adminMschoolBooking.from')</th>
                                    <th>@lang('adminMschoolBooking.to')</th>
                                    <th>@lang('adminMschoolBooking.beds')</th>
                                    <th>@lang('adminMschoolBooking.dorms')</th>
                                    <th>@lang('adminMschoolBooking.sleeps')</th>
                                    <th>@lang('adminMschoolBooking.status')</th>
                                    <th>@lang('adminMschoolBooking.action')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input-mschool" placeholder="@lang('adminMschoolBooking.searchBoookingNo')"></th>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="4"  class="form-control input-sm search-input-mschool" placeholder="@lang('adminMschoolBooking.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select class="form-control input-sm search-input-mschool" id="10">
                                            <option value="">(@lang('adminMschoolBooking.SearchStatus'))</option>
                                            <option value="1">@lang('adminMschoolBooking.bookingFix')</option>
                                            <option value="2">@lang('adminMschoolBooking.cancelled')</option>
                                            <option value="3">@lang('adminMschoolBooking.completed')</option>
                                            <option value="4">@lang('adminMschoolBooking.request')</option>
                                            <option value="5">@lang('adminMschoolBooking.bookingWaiting')</option>
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

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            bookingStatusUpdateAlert: '{{ trans('adminMschoolBooking.bookingStatusUpdateAlert') }}',
            wellDone: '{{ trans('adminMschoolBooking.wellDone') }}',
            deleteBookingAlert: '{{ trans('adminMschoolBooking.deleteBookingAlert') }}',
            panelLabelCabin: '{{ trans('adminMschoolBooking.panelLabelCabin') }}'
        };
    </script>

    <!-- Mountain School Booking Js -->
    <script src="{{ asset('js/mSchoolBookings.js') }}"></script>
@endsection