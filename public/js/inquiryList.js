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
            "searchDelay": 350,
            "ajax": {
                "url": '/cabinowner/inquiry/bookings',
                "dataType": "json",
                "type": "POST",
                "data":{ is_date_search:is_date_search, daterange:daterange}
            },
            "columns": [
                { "data": "hash" },
                { "data": "invoice_number" },
                { "data": "usrLastname" },
                { "data": "usrFirstname" },
                { "data": "usrEmail" },
                { "data": "checkin_from" },
                { "data": "reserve_to" },
                { "data": "beds" },
                { "data": "dormitory" },
                { "data": "sleeps" },
                { "data": "prepayment_amount" },
                { "data": "answered" },
                { "data": "inquirystatus" }
            ],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [0, 2, 3, 4, 7, 8, 9, 10, 11, 12]
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
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
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
                        $(col[9]).attr('width', 10);
                        $('row c[r^="B"]', sheet).attr( 's', '55' );
                        $('row c[r^="C"]', sheet).attr( 's', '55' );
                        $('row c[r^="D"]', sheet).attr( 's', '55' );
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                }
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
            type: 'POST'
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