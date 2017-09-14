@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Bookings List')

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
                @lang('admin.bookings')
                <small>@lang('admin.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('admin.dashboard')</a></li>
                <li class="active">@lang('admin.bookings')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('admin.bookingDetails')</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <div class="responseMessage"></div>

                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('admin.cabinLabel'): </label>
                                    <select class="form-control admin_cabins_list" style="width: 100%;" id="admin_cabins_list">
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
                                    <label>@lang('admin.daterangeLabel'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="adminBookingsDaterange" placeholder="@lang('admin.dateRangePlaceholder')">
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

                            <div class="col-md-2 pull-left alertMsg">
                            </div>

                            <div class="col-md-2">
                                <div class="form-group pull-right">
                                    <label></label>
                                    <div class="input-group">
                                        <button class="btn btn-app paymentStatusBtn" data-loading-text="@lang('admin.statusUpdating')" autocomplete="off"><i class="fa fa-euro"></i> @lang('admin.paymentStatusUpdate')</button>
                                    </div>
                                </div>
                                <!-- /.form group -->
                            </div>

                            <table id="booking_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.bookingNumber')</th>
                                    <th>@lang('admin.email')</th>
                                    <th>@lang('admin.from')</th>
                                    <th>@lang('admin.to')</th>
                                    <th>@lang('admin.beds')</th>
                                    <th>@lang('admin.dorms')</th>
                                    <th>@lang('admin.sleeps')</th>
                                    <th>@lang('admin.status')</th>
                                    <th>@lang('admin.payStatus')</th>
                                    <th>@lang('admin.payType')</th>
                                    <th>@lang('admin.amount')</th>
                                    <th>@lang('admin.txid')</th>
                                    <th>@lang('admin.payupdate')</th>
                                    <th>@lang('admin.action')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('admin.searchBoookingNo')"></th>
                                    <th><input type="text" id="2"  class="form-control input-sm search-input" placeholder="@lang('admin.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <select class="form-control input-sm search-input" id="8">
                                            <option value="">(Select a status)</option>
                                            <option value="1">@lang('admin.bookingFix')</option>
                                            <option value="2">@lang('admin.cancelled')</option>
                                            <option value="3">@lang('admin.completed')</option>
                                            <option value="4">@lang('admin.request')</option>
                                            <option value="5">@lang('admin.bookingWaiting')</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control input-sm search-input" id="9">
                                            <option value="">(Select a pay status)</option>
                                            <option value="0">@lang('admin.paymentStatusFailed')</option>
                                            <option value="1">@lang('admin.paymentStatusDone')</option>
                                            <option value="2">@lang('admin.paymentStatusCabinOwner')</option>
                                            <option value="3">@lang('admin.waitingForPayment')</option>
                                        </select>
                                    </td>
                                    <th><input type="text" id="10"  class="form-control input-sm search-input" placeholder="@lang('admin.searchPaymentType')"></th>
                                    <td></td>
                                    <th><input type="text" id="12"  class="form-control input-sm search-input" placeholder="@lang('admin.searchTxID')"></th>
                                    <td></td>
                                    <td></td>
                                    @if(isset($userID))
                                        <input type="hidden" name="parameterId" id="parameterId" value="{{$userID}}">
                                    @endif
                                    @if(isset($count))
                                        <input type="hidden" name="parameterCount" id="parameterCount" value="{{$count}}">
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

                        <!-- modal -->
                        <div class="modalBookingData"></div>
                        <!-- /.modal -->

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
    <script>
        /* Helping object for translation */
        window.translations = {
            bookingStatusUpdateAlert: '{{ trans('admin.bookingStatusUpdateAlert') }}',
            wellDone: '{{ trans('admin.wellDone') }}',
            deleteBookingAlert: '{{ trans('admin.deleteBookingAlert') }}',
            cabinPlaceholder: '{{ trans('admin.cabinPlaceholder') }}'
        };
    </script>
    <!-- Admin Bookings Js -->
    <script src="{{ asset('js/bookings.js') }}"></script>
@endsection