@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Mountain School List')

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
                @lang('cabinowner.bookings')
                <small>@lang('cabinowner.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('cabinowner.dashboard')</a></li>
                <li class="active">@lang('cabinowner.bookings')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('cabinowner.bookingDetails')</h3>
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
                                    <th>@lang('cabinowner.bookingNumber')</th>
                                    <th>@lang('cabinowner.comment')</th>
                                    <th>@lang('cabinowner.lastName')</th>
                                    <th>@lang('cabinowner.firstName')</th>
                                    <th>@lang('cabinowner.email')</th>
                                    <th>@lang('cabinowner.from')</th>
                                    <th>@lang('cabinowner.to')</th>
                                    <th>@lang('cabinowner.beds')</th>
                                    <th>@lang('cabinowner.dorms')</th>
                                    <th>@lang('cabinowner.sleeps')</th>
                                    <th>@lang('cabinowner.status')</th>
                                    <th>@lang('cabinowner.answered')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('cabinowner.searchBoookingNo')"></th>
                                    <th id="2"></th>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="5"  class="form-control input-sm search-input" placeholder="@lang('cabinowner.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select class="form-control input-sm search-input" id="11">
                                            <option value="">(@lang('cabinowner.SearchStatus'))</option>
                                            <option value="1">@lang('cabinowner.bookingFix')</option>
                                            <option value="2">@lang('cabinowner.cancelled')</option>
                                            <option value="3">@lang('cabinowner.completed')</option>
                                            <option value="4">@lang('cabinowner.request')</option>
                                            <option value="5">@lang('cabinowner.bookingWaiting')</option>
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
    <script src="{{ asset('js/mountSchoolBookings.js') }}"></script>
@endsection