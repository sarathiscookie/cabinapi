@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Inquiry List')

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
                @lang('inquiry.bookings')
                <small>@lang('inquiry.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('inquiry.dashboard')</a></li>
                <li><a href="/cabinowner/bookings"><i class="fa fa-table"></i> @lang('inquiry.breadcrumbBooking')</a></li>
                <li class="active">@lang('inquiry.bookings')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('inquiry.bookingDetails')</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="inquiryStatusResponse"></div>
                            <div class="row">
                                <div class="col-md-3" style="margin-bottom: 20px;">
                                    <div class="pull-left daterange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;" data-toggle="tooltip" data-placement="right" title="Zeitraum Auswahl">
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span></span> <b class="caret"></b>
                                    </div>
                                </div>
                            </div>

                            <table id="inquiry_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('inquiry.bookingNumber')</th>
                                    <th>@lang('inquiry.lastName')</th>
                                    <th>@lang('inquiry.firstName')</th>
                                    <th>@lang('inquiry.email')</th>
                                    <th>@lang('inquiry.from')</th>
                                    <th>@lang('inquiry.to')</th>
                                    <th>@lang('inquiry.beds')</th>
                                    <th>@lang('inquiry.dorms')</th>
                                    <th>@lang('inquiry.sleeps')</th>
                                    <th>@lang('inquiry.amount')</th>
                                    <th>@lang('inquiry.answered')</th>
                                    <th>@lang('inquiry.inquiryStatus')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('inquiry.searchBoookingNo')"></th>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="4"  class="form-control input-sm search-input" placeholder="@lang('inquiry.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    @if(isset($bookId))
                                        <input type="hidden" name="parameterId" id="parameterId" value="{{$bookId}}">
                                    @endif
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

    <!-- Inquiry List Js -->
    <script src="{{ asset('js/inquiryList.js') }}"></script>
@endsection