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
                    "url": '/cabinowner/mschool/bookings',
                "dataType": "json",
                "type": "POST",
                "data":{ is_date_search:is_date_search, daterange:daterange}
            },
            "columns": [
        { "data": "hash" },
        { "data": "invoice_number" },
        { "data": "ind_notice" },
        { "data": "usrLastname" },
        { "data": "usrFirstname" },
        { "data": "usrEmail" },
        { "data": "check_in" },
        { "data": "reserve_to" },
        { "data": "beds" },
        { "data": "dormitory" },
        { "data": "sleeps" },
        { "data": "status" },
        { "data": "answered" }
    ],
        "columnDefs": [
        {
            "orderable": false,
            "targets": [0, 2, 3, 4, 5, 8, 9, 10, 11, 12]
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
    $('.daterange_Mschool').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD.MM.YYYY',
            cancelLabel: 'Löschen',
            applyLabel: 'Bestätigen'
        }
    });

    $('.daterange_Mschool').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
        var data        = $('.daterange_Mschool').val();
        var daterange   = data.replace(/\s/g, '');
        if(daterange != '')
        {
            booking_data.destroy();
            fetch_data('yes', daterange)
        }
    });

    $('.daterange_Mschool').on('cancel.daterangepicker', function(ev, picker) {
        var data        = $(this).val('');
        booking_data.destroy();
        fetch_data('no')
    });

    /* Date range functionality end */

    /* Send Message */
    $('#booking_data_mschool tbody').on( 'click', 'button.messageStatusUpdateMschool', function(e){
        e.preventDefault();
        var $btn       = $(this).button('loading');
        var bookingId  = $(this).siblings('.message_status_update_mschool').attr('value');

        var JSONObject = {
            "id": bookingId,
            "comment": $('#messageMschoolTxt_'+bookingId).val()
        };
        var jsonData = JSON.stringify(JSONObject);
        $.ajax({
            url: '/cabinowner/mschool/message/send',
            data: { "data": jsonData },
            dataType: 'JSON',
            type: 'POST',
            success: function(result) {
                if(result) {
                    if(result.message == 'success')
                    {
                        $('.alert-message-failed-mschool').hide();
                        $btn.button('reset');
                        $('.alert-message-mschool').show();
                        setTimeout(function() { $('#messageMschoolModal_'+bookingId).modal('hide'); }, 3000);
                        $('#messageMschoolModal_'+bookingId).on('hidden.bs.modal', function () {
                            booking_data.ajax.reload(null, false);
                        })
                    }
                    else {
                        $btn.button('reset');
                        $('.alert-message-failed-mschool').show();
                    }
                }
            }
        });
    });

    /* <tfoot> search functionality */
    $('.search-input-mschool').on( 'keyup change', function () {
        var i =$(this).attr('id');  // getting column index
        var v =$(this).val();  // getting search input value
        booking_data.columns(i).search(v).draw();
    });

});