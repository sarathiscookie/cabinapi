@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Booking List')

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
                @lang('mountainschool.bookings')
                <small>@lang('mountainschool.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('mountainschool.dashboard')</a></li>
                <li class="active">@lang('mountainschool.bookings')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('mountainschool.bookingDetails')</h3>
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
                                        <th>@lang('mountainschool.bookingNumber')</th>
                                        <th>@lang('mountainschool.indTourNumber')</th>
                                        <th>@lang('mountainschool.cabinname')</th>
                                        <th>@lang('mountainschool.from')</th>
                                        <th>@lang('mountainschool.to')</th>
                                        <th>@lang('mountainschool.beds')</th>
                                        <th>@lang('mountainschool.dorms')</th>
                                        <th>@lang('mountainschool.sleeps')</th>
                                        <th>@lang('mountainschool.status')</th>
                                        <th>@lang('mountainschool.edit')</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('mountainschool.searchBoookingNo')"></th>
                                        <th><input type="text" id="2"  class="form-control input-sm search-input" placeholder="@lang('mountainschool.searchIndTourNo')"></th>
                                        <th colspan="6"><input type="text" id="3"  class="form-control input-sm search-input" placeholder="@lang('mountainschool.searchCabinName')"></th>
                                        <th>
                                            <select class="form-control input-sm search-input" id="8">
                                                <option value="">(@lang('mountainschool.SearchStatus'))</option>
                                                <option value="1">@lang('mountainschool.bookingFix')</option>
                                                <option value="2">@lang('mountainschool.cancelled')</option>
                                                <option value="3">@lang('mountainschool.completed')</option>
                                                <option value="4">@lang('mountainschool.request')</option>
                                                <option value="5">@lang('mountainschool.bookingWaiting')</option>
                                            </select>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

            /* Datatable functionality begins */
            var booking_data;
            var daterange = '';
            var parameterId = '';

            if($('#parameterId').val() != '') {
                var parameterId    = $('#parameterId').val();
            }

            fetch_data('no', null, parameterId);

            function fetch_data(is_date_search, daterange, parameterId)
            {
                booking_data = $('#booking_data').DataTable({
                    "lengthMenu": [10, 50, 100, 250, 500],
                    "order": [[ 1, "desc" ]],
                    "bSortCellsTop": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": '{{ route('mountainschool.bookings.datatables') }}',
                        "dataType": "json",
                        "type": "POST",
                        "data":{ _token: "{{csrf_token()}}", is_date_search:is_date_search, daterange:daterange, parameterId:parameterId}
                    },
                    "columns": [
                        { "data": "hash" },
                        { "data": "invoice_number" },
                        { "data": "ind_tour_no" },
                        { "data": "cabin_name" },
                        { "data": "check_in" },
                        { "data": "reserve_to" },
                        { "data": "beds" },
                        { "data": "dormitory" },
                        { "data": "sleeps" },
                        { "data": "status" },
                        { "data": "edit" }
                    ],
                    "columnDefs": [
                        {
                            "orderable": false,
                            "targets": [0, 3, 4, 5, 6, 7, 8, 10]
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

                /* Bottom buttons for datatables */
                var buttons = new $.fn.dataTable.Buttons(booking_data, {
                    buttons: [
                        {
                            extend: 'csv',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8]
                            }
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8]
                            },
                            customize: function( xlsx ) {
                                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                                var col   = $('col', sheet);
                                $(col[0]).attr('width', 15);
                                $(col[1]).attr('width', 15);
                                $(col[2]).attr('width', 15);
                                $(col[3]).attr('width', 25);
                                $(col[4]).attr('width', 8);
                                $(col[5]).attr('width', 8);
                                $(col[6]).attr('width', 5);
                                $(col[7]).attr('width', 5);
                                $(col[8]).attr('width', 5);

                                $('row c[r^="B"]', sheet).attr( 's', '55' );
                                $('row c[r^="C"]', sheet).attr( 's', '55' );
                                $('row c[r^="D"]', sheet).attr( 's', '55' );
                            }
                        },
                        {
                            extend: 'print',
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8]
                            }
                        }
                    ]
                }).container().appendTo($('#buttons'));
            }

            // Datatable search min 3 char length needed
            $('input[type=search]') .unbind() // Unbind previous default bindings
                .bind("input", function(e) { // Bind our desired behavior
                    // If the length is 3 or more characters, or the user pressed ENTER, search
                    if(this.value.length >= 3 || e.keyCode == 13) {
                        booking_data.search(this.value).draw();
                    }

                    if(this.value == "") {
                        booking_data.search("").draw();
                    }
                    return;
                });
            /* Datatable functionality ends */

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
                    fetch_data('yes', daterange, parameterId)
                }
            });

            $('.daterange').on('cancel.daterangepicker', function(ev, picker) {
                var data        = $(this).val('');
                booking_data.destroy();
                fetch_data('no', null, parameterId)
            });

            /* Date range functionality end */
            /* <tfoot> search functionality */
            $('.search-input').on( 'keyup change', function () {
                var i =$(this).attr('id');  // getting column index
                var v =$(this).val();  // getting search input value
                booking_data.columns(i).search(v).draw();
            });

        });
    </script>
@endsection