@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Booking List')

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
                                    <th>@lang('cabinowner.amount')</th>
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
                                    {{--<th><input type="text" id="3"  class="form-control input-sm search-input" placeholder="@lang('cabinowner.lastName')"></th>
                                    <th><input type="text" id="4"  class="form-control input-sm search-input" placeholder="@lang('cabinowner.firstName')"></th>--}}
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
    <script>
        $(function () {
            /* Checking for the CSRF token */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /* Tooltip */
            $('[data-toggle="tooltip"]').tooltip();

            var booking_data;
            var daterange = '';

            fetch_data('no');

            function fetch_data(is_date_search, daterange)
            {
                booking_data = $('#booking_data').DataTable({
                    "lengthMenu": [10, 50, 100, 250, 500],
                    "order": [[ 1, "desc" ]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": '{{ route('cabinowner.bookings.datatables') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", is_date_search:is_date_search, daterange:daterange}
                    },
                    "columns": [
                        { "data": "hash" },
                        { "data": "invoice_number" },
                        { "data": "comment" },
                        { "data": "usrLastname" },
                        { "data": "usrFirstname" },
                        { "data": "usrEmail" },
                        { "data": "checkin_from" },
                        { "data": "reserve_to" },
                        { "data": "beds" },
                        { "data": "dormitory" },
                        { "data": "sleeps" },
                        { "data": "status" },
                        { "data": "prepayment_amount" },
                        { "data": "answered" }
                    ],
                    "columnDefs": [
                        {
                            "orderable": false,
                            "targets": [0, 2, 3, 4, 5, 8, 9, 10, 11, 12, 13]
                        }
                    ],
                    "language": {
                        "sEmptyTable":   	"Keine Daten in der Tabelle vorhanden",
                        "sInfo":         	"_START_ bis _END_ von _TOTAL_ Einträgen",
                        "sInfoEmpty":    	"0 bis 0 von 0 Einträgen",
                        "sInfoFiltered": 	"(gefiltert von _MAX_ Einträgen)",
                        "sInfoPostFix":  	"",
                        "sInfoThousands":  	".",
                        "sLengthMenu":   	"_MENU_ Einträge anzeigen",
                        "sLoadingRecords": 	"Wird geladen...",
                        "sProcessing":   	"Bitte warten...",
                        "sSearch":       	"Suchen",
                        "sZeroRecords":  	"Keine Einträge vorhanden.",
                        "oPaginate": {
                            "sFirst":    	"Erste",
                            "sPrevious": 	"Zurück",
                            "sNext":     	"Nächste",
                            "sLast":     	"Letzte"
                        },
                        "oAria": {
                            "sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
                            "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                        }
                    }
                });

                /* Visiblity disabled of "comment" column */
                booking_data.column(2).visible( false );

                /* Bottom buttons for datatables */
                var buttons = new $.fn.dataTable.Buttons(booking_data, {
                    buttons: [
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                            },
                            customize: function( xlsx ) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                var col   = $('col', sheet);
                                $(col[0]).attr('width', 15);
                                $(col[1]).attr('width', 15);
                                $(col[2]).attr('width', 10);
                                $(col[3]).attr('width', 10);
                                $(col[4]).attr('width', 10);
                                $(col[5]).attr('width', 8);
                                $(col[6]).attr('width', 8);
                                $(col[7]).attr('width', 5);
                                $(col[8]).attr('width', 5);
                                $(col[9]).attr('width', 6);
                                $(col[10]).attr('width', 6);
                                $(col[11]).attr('width', 10);
                                $('row c[r^="B"]', sheet).attr( 's', '55' );
                                $('row c[r^="C"]', sheet).attr( 's', '55' );
                                $('row c[r^="D"]', sheet).attr( 's', '55' );
                                $('row c[r^="E"]', sheet).attr( 's', '55' );
                            }
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                            }
                        },
                    ]
                }).container().appendTo($('#buttons'));
            }



            /* Date range functionality begin */
            $('.daterange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD.MM.YYYY',
                    cancelLabel: 'Löschen',
                    applyLabel: 'Bestätigen'
                }
            });

            $('.daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
                var data        = $('.daterange').val();
                var daterange   = data.replace(/\s/g, '');
                if(daterange != '')
                {
                    booking_data.destroy();
                    fetch_data('yes', daterange)
                }
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                var data        = $(this).val('');
                booking_data.destroy();
                fetch_data('no')
            });

            /* Date range functionality end */

            /* Send Message */
            $('#booking_data tbody').on( 'click', 'button.messageStatusUpdate', function(e){
                e.preventDefault();
                var $btn       = $(this).button('loading');
                var bookingId  = $(this).siblings('.message_status_update').attr('value');

                var JSONObject = {
                    "id": bookingId,
                    "comment": $('#messageTxt_'+bookingId).val()
                };
                var jsonData = JSON.stringify(JSONObject);
                $.ajax({
                    url: '/cabinowner/message/send',
                    data: { "data": jsonData },
                    dataType: 'JSON',
                    type: 'POST',
                    success: function(result) {
                        if(result) {
                            if(result.message == 'success')
                            {
                                $('.alert-message-failed').hide();
                                $btn.button('reset');
                                $('.alert-message').show();
                                setTimeout(function() { $('#messageModal_'+bookingId).modal('hide'); }, 3000);
                                $('#messageModal_'+bookingId).on('hidden.bs.modal', function () {
                                    booking_data.ajax.reload(null, false);
                                })
                            }
                            else {
                                $btn.button('reset');
                                $('.alert-message-failed').show();
                            }
                        }
                    }
                });
            });

            /* Cancel booking */
            $('#booking_data tbody').on( 'click', 'button.cancel', function(e){
                e.preventDefault();
                var data = $(this).children('.spanCancel').data('cancel');
                $.ajax({
                    url: '/cabinowner/booking/cancel',
                    data: { data: data },
                    dataType: 'JSON',
                    type: 'POST',
                })
                    .done(function( response ) {
                        $('.response').html('<div class="alert alert-success alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.message+'</div>');
                        $('.cancelDiv').hide();
                        $('#bookingModal_'+data).on('hidden.bs.modal', function () {
                            $('#bookingModal_'+data).html("");
                            booking_data.ajax.reload(null, false);
                        })
                    })
                    .fail(function() {
                        $('.response').html('<div class="alert alert-warning alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong>Hat leider nicht geklappt. Bitte versuchen Sie es erneut</div>');
                        $('#bookingModal_'+data).on('hidden.bs.modal', function () {
                            $('#bookingModal_'+data).html("");
                            booking_data.ajax.reload(null, false);
                        })
                    });
            });

            /* <tfoot> search functionality */
            $('.search-input').on( 'keyup change', function () {
                var i =$(this).attr('id');  // getting column index
                var v =$(this).val();  // getting search input value
                booking_data.columns(i).search(v).draw();
            });

        });
    </script>
@endsection