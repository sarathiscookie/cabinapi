/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-08-2017
 * Time: 13:51
 */
$(function () {
    /* Checking for the CSRF token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Tooltip */
    $('[data-toggle="tooltip"]').tooltip();

    /* Helping object for translation */
    var translations = {
        bookingStatusUpdateAlert: window.translations.bookingStatusUpdateAlert,
        wellDone: window.translations.wellDone,
        deleteBookingAlert: window.translations.deleteBookingAlert,
        cabinPlaceholder: window.translations.cabinPlaceholder
    };

    //Initialize Select2 Elements
    $(".admin_cabins_list").select2({
        placeholder: translations.cabinPlaceholder
    });

    var booking_data;
    var daterange = '';
    var cabin = '';

    fetch_data('no');

    /*Function for data table begin*/
    function fetch_data(is_date_search, daterange, cabin)
    {
        booking_data = $('#booking_data_mschool').DataTable({
            "lengthMenu": [10, 50, 100, 250, 500],
            "order": [[ 1, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '/admin/mschool/bookings',
                "dataType": "json",
                "type": "POST",
                "data":{ is_date_search:is_date_search, daterange:daterange, cabin:cabin}
            },
            "columns": [
                { "data": "hash" },
                { "data": "invoice_number" },
                { "data": "usrLastname" },
                { "data": "usrFirstname" },
                { "data": "usrEmail" },
                { "data": "check_in" },
                { "data": "reserve_to" },
                { "data": "beds" },
                { "data": "dormitory" },
                { "data": "sleeps" },
                { "data": "status" },
                { "data": "action" }
            ],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [0, 2, 3, 4, 7, 8, 9, 10, 11]
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
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
            ]
        }).container().appendTo($('#buttons'));
    }
    /*Function for data table end*/

    /* Date range functionality begin */
    $('#daterange_Mschool').daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Letzten 7 Tage': [moment().subtract(7, 'days'), moment()],
            'Letzten 30 Tage': [moment().subtract(30, 'days'), moment()],
            'Dieser Monat': [moment().startOf('month'), moment().endOf('month')],
            'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD.MM.YYYY',
            applyLabel: "Bestätigen",
            cancelLabel: "Löschen",
            daysOfWeek: [
                "So",
                "Mo",
                "Di",
                "Mi",
                "Do",
                "Fr",
                "Sa"
            ]
        }
    });

    $('#daterange_Mschool').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    $('#daterange_Mschool').on('cancel.daterangepicker', function(ev, picker) {
        var data        = $(this).val('');
        booking_data.destroy();
        fetch_data('no')
    });

    $('#generateAdminBookings').on('click', function() {
        var cabin     = $('.admin_cabins_list').val();
        var dates     = $('#daterange_Mschool').val();
        var daterange = dates.replace(/\s/g, '');
        if(daterange !== '' && cabin !== '')
        {
            booking_data.destroy();
            fetch_data('yes', daterange, cabin)
        }
        else {
            $('.alertMessage').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Leere Felder bitte ausfüllen</div>');
        }
    });
    /* Date range functionality end */

    /* Delete functionality */
    $('#booking_data_mschool tbody').on( 'click', 'a.deleteEvent', function (e) {
        e.preventDefault();
        var bookingId = $(this).data('id');
        var r = confirm(translations.deleteBookingAlert);
        if (r == true) {
            $.ajax({
                url: '/admin/mschool/bookings/' + bookingId,
                dataType: 'JSON',
                type: 'DELETE',
                success: function(result) {
                    if(result) {
                        booking_data
                            .row( $(this).parents('tr') )
                            .remove()
                            .draw();
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+result.message+' </div>');
                        $('.responseMessage').show().delay(5000).fadeOut();
                    }
                }
            });
        }
    });

    /* <tfoot> search functionality */
    $('.search-input-mschool').on( 'keyup change', function () {
        var i =$(this).attr('id');  // getting column index
        var v =$(this).val();  // getting search input value
        booking_data.columns(i).search(v).draw();
    });

});