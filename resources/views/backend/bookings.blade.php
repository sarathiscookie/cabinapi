@extends('backend.layouts.app')

@section('title', 'Cabin API: Bookings List')

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
                Bookings
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li class="active">Bookings</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Booking Details</h3>
                            {{--<div class="pull-right box-tools">
                                <button type="button" class="btn btn-primary btn-sm daterange pull-right">
                                    <i class="fa fa-calendar"></i></button>
                            </div>--}}
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body"> <!-- if we need responsive add class = "table-responsive" in this div -->
                            {{--<div id="reportrange" class="pull-right reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>--}}
                            <div class="responseMessage"></div>
                            <table id="booking_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book Num</th>
                                    <th>Email</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Beds</th>
                                    <th>Dorms</th>
                                    <th>Sleeps</th>
                                    <th>Status</th>
                                    <th>Pay Status</th>
                                    <th>Pay Type</th>
                                    <th>Total Amount</th>
                                    <th>Total Txid</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th>Number</th>
                                    <th>Email</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th>Pay Type</th>
                                    <td></td>
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
    <script>
        $(function () {
            /* Checking for the CSRF token */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#booking_data').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ route('bookings.datatables') }}',
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "hash" },
                    { "data": "invoice_number" },
                    { "data": "usrEmail" },
                    { "data": "checkin_from" },
                    { "data": "reserve_to" },
                    { "data": "beds" },
                    { "data": "dormitory" },
                    { "data": "sleeps" },
                    { "data": "status" },
                    { "data": "payment_status" },
                    { "data": "payment_type" },
                    { "data": "total_prepayment_amount" },
                    { "data": "txid" }
                ],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [1, 2, 3, 4, 5, 6, 7, 11, 12]
                    }
                ]
            });

            /* Send invoice */
            $('#dataTable tbody').on( 'click', 'button.sendInvoice', function (e) {
                var bookingId = $(this).closest('li').data('invoice');
                var $btn      = $(this).button('loading');
                $.ajax({
                    url: '/admin/bookings/voucher/' + bookingId,
                    data: { "_token": "" },
                    type: 'POST',
                    success: function(result) {
                        if(result){
                            $('.alert-invoice').show().delay(5000).fadeOut();
                            $btn.button('reset');
                        }
                    }
                });
            });


        });
    </script>
@endsection