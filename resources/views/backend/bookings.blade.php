@extends('backend.layouts.app')

@section('title', 'Cabin API: Bookings List')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
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
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="responseMessage"></div>
                            <table id="dataTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Book Num</th>
                                    <th>Email</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Beds</th>
                                    <th>Dorms</th>
                                    <th>Sleeps</th>
                                    <th>Book Status</th>
                                    <th>Pay Status</th>
                                    <th>Pay Type</th>
                                    <th>Total Amount</th>
                                    <th>Txid</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th>Number</th>
                                    <th>Email</th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th>Type</th>
                                    <td></td>
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
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
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

            /* Functionality for data table begin */
            var table = $('#dataTable').DataTable({
                /*"processing": true,
                "serverSide": true,*/
                "ajax": '{!! route('bookings.datatables') !!}',
                "columns": [
                    {"data": "invoice_number", "name": "invoice_number"},
                    {"data": "usrEmail", "name": "usrEmail", "render": function ( data, type, full, meta ) {
                        if( data === 'cabinowner' ){
                            return '<span class="label label-info">Booked by cabin owner</span>';
                        }
                        else{
                            return data;
                        }
                    }},
                    {"data": "checkin_from", "name": "checkin_from", "render": function ( data, type, full, meta ) {
                        if (!data) {
                            return '<span class="label label-default">No data</span>'
                        }
                        else {
                            var date       = new Date(data);
                            var dd         = date.getDate();
                            var mm         = date.getMonth()+1; //January is 0!
                            var yyyy       = date.getFullYear();
                            if( dd < 10 ){
                                dd='0'+dd;
                            }
                            if( mm < 10 ){
                                mm='0'+mm;
                            }
                            var dateformat = dd+'.'+mm+'.'+yyyy;
                            return dateformat;
                        }
                    }},
                    {"data": "reserve_to", "name": "reserve_to", "render": function ( data, type, full, meta ) {
                        if (!data) {
                            return '<span class="label label-default">No data</span>'
                        }
                        else {
                            var date       = new Date(data);
                            var dd         = date.getDate();
                            var mm         = date.getMonth()+1; //January is 0!
                            var yyyy       = date.getFullYear();
                            if( dd < 10 ){
                                dd='0'+dd;
                            }
                            if( mm < 10 ){
                                mm='0'+mm;
                            }
                            var dateformat = dd+'.'+mm+'.'+yyyy;
                            return dateformat;
                        }
                    }},
                    {"data": "beds", "name": "beds"},
                    {"data": "dormitory", "name": "dormitory"},
                    {"data": "sleeps", "name": "sleeps"},
                    {"data": "status", "name": "status", "render": function ( data, type, full, meta ) {
                        if( data === '1' ){
                            return '<span class="label label-primary">New</span>';
                        }
                        else if( data === '2' ){
                            return '<span class="label label-warning">Cancelled</span>';
                        }
                        else if( data === '3' ){
                            return '<span class="label label-success">Completed</span>';
                        }
                        else if( data === '4' ){
                            return '<span class="label label-info">Request</span>';
                        }
                        else if( data === '5' ){
                            return '<span class="label label-danger">Failed</span>';
                        }
                        else{
                            return '<span class="label label-default">No data</span>';
                        }
                    }},
                    {"data": "payment_status", "name": "payment_status", "render": function ( data, type, full, meta ) {
                        if( data === '1' ){
                            return '<span class="label label-success">Done</span>';
                        }
                        else if( data === '0' ){
                            return '<span class="label label-danger">Failed</span>';
                        }
                        else{
                            return '<span class="label label-default">No data</span>';
                        }
                    }},
                    {"data": "payment_type", "name": "payment_type"},
                    {"data": "total_prepayment_amount", "name": "total_prepayment_amount"},
                    {"data": "txid", "name": "txid"},
                    {"data": "action", "name": "action", "orderable": false, "searchable": false}
                ],
                "columnDefs": [{
                    "defaultContent": "-",
                    "targets"  : "_all",
                }],
            });

            $('#dataTable tfoot th').each( function () {
                var title = $(this).text();
                $(this).html( '<input type="text" placeholder="Search ' + title + '"/>' );
            });

            var buttons = new $.fn.dataTable.Buttons(table, {
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [  0, 1, 2, 3, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        orientation: 'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 7, 8, 9, 10 ]
                        }
                    },
                ]
            }).container().appendTo($('#buttons'));

            // Apply the search
            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                });
            });
            /* Data table functionality end */

            /* Delete function */
            $('#dataTable tbody').on( 'click', 'a.deleteEvent', function (e) {
                var bookingId = $(this).data('id');
                var r = confirm("Do you want to delete this booking?");
                if (r == true) {
                    $.ajax({
                        url: '/admin/bookings/' + bookingId,
                        data: { "_token": "{{ csrf_token() }}" },
                        type: 'DELETE',
                        success: function(result) {
                            $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> Well Done</h4>'+result.message+'</div>')
                            $('.responseMessage').show().delay(5000).fadeOut();
                        }
                    });
                    table
                        .row( $(this).parents('tr') )
                        .remove()
                        .draw();
                }
                e.preventDefault();
            });

        });
    </script>
@endsection