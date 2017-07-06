@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Bookings List')

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
                            <div class="text-right">
                                <button class="btn btn-app text-right paymentStatusBtn" data-loading-text="@lang('admin.statusUpdating')" autocomplete="off"><i class="fa fa-euro"></i> @lang('admin.paymentStatusUpdate')</button>
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
                                    <th>@lang('admin.action')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="1"  class="search-input" placeholder="@lang('admin.searchBoookingNo')"></th>
                                    <th><input type="text" id="2"  class="search-input" placeholder="@lang('admin.SearchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="10"  class="search-input" placeholder="@lang('admin.searchPaymentType')"></th>
                                    <td></td>
                                    <th><input type="text" id="12"  class="search-input" placeholder="@lang('admin.searchTxID')"></th>
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

            var booking_data = $('#booking_data').DataTable({
                "order": [[ 1, "desc" ]],
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
                    { "data": "txid" },
                    { "data": "action" }
                ],
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [0, 2, 5, 6, 7, 8, 9, 12, 13]
                    }
                ]
            });

            /* Bottom buttons for datatables */
            var buttons = new $.fn.dataTable.Buttons(booking_data, {
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 8, 9, 10, 11 ]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 8, 9, 10, 11 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        orientation: 'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 8, 9, 10, 11 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 8, 9, 10, 11 ]
                        }
                    },
                ]
            }).container().appendTo($('#buttons'));

            /* Payment status change */
            $('.paymentStatusBtn').on('click', function(e){
                e.preventDefault();
                if(!$('.checked').is(':checked')) {
                    confirm("@lang('admin.bookingStatusUpdateAlert')");
                }
                else {
                    var bookingId = new Array();
                    var $btn      = $(this).button('loading');
                    $("input:checked").each(function() {
                        bookingId.push($(this).val());
                    });
                    $.ajax({
                        url: '/admin/bookings/payment/status',
                        data: { "bookingId": bookingId },
                        dataType: 'JSON',
                        type: 'PUT',
                        success: function(result) {
                            if(result) {
                                booking_data.ajax.reload();
                                $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> @lang('admin.wellDone')</h4>'+result.message+'</div>')
                                $('.responseMessage').show().delay(5000).fadeOut();
                                $btn.button('reset');
                            }
                        }
                    });
                }
            });

            /* Send invoice functionality */
            $('#booking_data tbody').on( 'click', 'button.sendInvoice', function (e) {
                e.preventDefault();
                var bookingId = $(this).closest('li').data('invoice');
                var $btn      = $(this).button('loading');
                $.ajax({
                    url: '/admin/bookings/voucher/' + bookingId,
                    data: { "_token": "{{ csrf_token() }}" },
                    dataType: 'JSON',
                    type: 'POST',
                    success: function(result) {
                        if(result){
                            $('.alert-invoice').show().delay(5000).fadeOut();
                            $btn.button('reset');
                        }
                    }
                });
            });

            /* Delete functionality */
            $('#booking_data tbody').on( 'click', 'a.deleteEvent', function (e) {
                e.preventDefault();
                var bookingId = $(this).data('id');
                var r = confirm("@lang('admin.deleteBookingAlert')");
                if (r == true) {
                    $.ajax({
                        url: '/admin/bookings/' + bookingId,
                        data: { "_token": "{{ csrf_token() }}" },
                        dataType: 'JSON',
                        type: 'DELETE',
                        success: function(result) {
                            if(result) {
                                booking_data
                                    .row( $(this).parents('tr') )
                                    .remove()
                                    .draw();
                                $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> @lang('admin.wellDone')</h4>'+result.message+'</div>')
                                $('.responseMessage').show().delay(5000).fadeOut();
                            }
                        }
                    });
                }
            });

            /* Footer search functionality */
            $('.search-input').on( 'keyup change', function () {
                var i =$(this).attr('id');  // getting column index
                var v =$(this).val();  // getting search input value
                booking_data.columns(i).search(v).draw();
            } );

        });
    </script>
@endsection