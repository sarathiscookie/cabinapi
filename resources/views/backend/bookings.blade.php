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
                            <table id="dataTable" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Book Num</th>
                                    <th>Email</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Beds</th>
                                    <th>Dorms</th>
                                    <th>Book Status</th>
                                    <th>Pay Status</th>
                                    <th>Pay Type</th>
                                    <th>Total Amount</th>
                                    <th>Txid</th>
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
                                    <th>Type</th>
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

            /* Functionality for data table begin */
            var table = $('#dataTable').DataTable({
                /*"processing": true,
                "serverSide": true,*/
                "ajax": '{!! route('bookings.datatables') !!}',
                "columns": [
                    {"data": "invoice_number", name: "invoice_number"},
                    {"data": "usrEmail", name: "usrEmail"/*, "render": function ( data, type, full, meta ) {
                        return '<span class="label label-info">'+data+'</span>';
                    }*/},
                    {"data": "checkin_from", name: "checkin_from"},
                    {"data": "reserve_to", name: "reserve_to"},
                    {"data": "beds", name: "beds"},
                    {"data": "dormitory", name: "dormitory"},
                    {"data": "status", name: "status"},
                    {"data": "payment_status", name: "payment_status"},
                    {"data": "payment_type", name: "payment_type"},
                    {"data": "total_prepayment_amount", name: "total_prepayment_amount"},
                    {"data": "txid", name: "txid"}
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
                            columns: [  0, 1, 2, 3, 6, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [  0, 1, 2, 3, 6, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 6, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        orientation: 'portrait',
                        pageSize: 'LEGAL',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 6, 7, 8, 9, 10 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [  0, 1, 2, 3, 6, 7, 8, 9, 10 ]
                        }
                    },
                ]
            }).container().appendTo($('#buttons'));

            // Apply the search
            table.columns().every( function () {
                const that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                });
            });
            /* Data table functionality end */
        });
    </script>
@endsection