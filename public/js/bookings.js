/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-08-2017
 * Time: 10:01
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

    /* Datatable functionality begins */
    var booking_data;
    var daterange = '';
    var cabin = '';
    var parameterId = '';

    if($('#parameterId').val() != '') {
        var parameterId    = $('#parameterId').val();
    }

    fetch_data('no', null, null, parameterId);

    function fetch_data(is_date_search, daterange, cabin, parameterId)
    {
        booking_data = $('#booking_data').DataTable({
            "lengthMenu": [10, 50, 100, 250, 500],
            "order": [[ 1, "desc" ]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '/admin/bookings/datatables',
                "dataType": "json",
                "type": "POST",
                "data":{is_date_search:is_date_search, daterange:daterange, cabin:cabin, parameterId:parameterId}
            },
            "deferRender": true,
            "columns": [
                { "data": "hash" },
                { "data": "invoice_number" },
                { "data": "order_number" },
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
                { "data": "payment_status_update" },
                { "data": "action" }
                ],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [0, 2, 5, 6, 7, 8, 9, 12, 13]
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

    /* Payment status change */
    $('.paymentStatusBtn').on('click', function(e){
        e.preventDefault();
        if(!$('.checked').is(':checked')) {
            confirm(translations.bookingStatusUpdateAlert);
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
                        booking_data.ajax.reload(null, false);
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+translations.wellDone+'</h4>'+result.message+'</div>');
                        $('.responseMessage').show().delay(5000).fadeOut();
                        $btn.button('reset');
                    }
                }
            });
        }
    });

    /* Payment status update individually */
    $('#booking_data tbody').on( 'click', 'button.paymentStatusUpdate', function(e){
        e.preventDefault();
        var bookingId  = $(this).siblings('.pay_status_update').attr('value');
        var $btn       = $(this).button('loading');

        $.ajax({
            url: '/admin/bookings/payment/status/individual',
            data: { "bookingId": bookingId },
            dataType: 'JSON',
            type: 'PUT'
        })
            .done(function( result ) {
                $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+translations.wellDone+'</h4>'+result.message+'</div>');
                $btn.button('reset');
                booking_data.ajax.reload(null, false);
            })
            .fail(function() {
                $('.responseMessage').html('<div class="alert alert-warning alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS! </strong>Hat leider nicht geklappt. Bitte versuchen Sie es erneut</div>');
                booking_data.ajax.reload(null, false);
            });
    });

    /* Send invoice functionality */
    $('#booking_data tbody').on( 'click', 'button.sendInvoice', function (e) {
        e.preventDefault();
        var bookingId = $(this).closest('li').data('invoice');
        var $btn      = $(this).button('loading');
        $.ajax({
            url: '/admin/bookings/voucher/' + bookingId,
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
        var r = confirm(translations.deleteBookingAlert);
        if (r == true) {
            $.ajax({
                url: '/admin/bookings/' + bookingId,
                dataType: 'JSON',
                type: 'DELETE',
                success: function(result) {
                    if(result) {
                        booking_data
                            .row( $(this).parents('tr') )
                            .remove()
                            .draw();
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+translations.wellDone+'</h4>'+result.message+'</div>')
                        $('.responseMessage').show().delay(5000).fadeOut();
                    }
                }
            });
        }
    });

    /* Date range functionality begin */

    $('#adminBookingsDaterange').daterangepicker({
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
            ],
        }
    });

    $('#adminBookingsDaterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    $('#adminBookingsDaterange').on('cancel.daterangepicker', function(ev, picker) {
        var data        = $(this).val('');
        booking_data.destroy();
        fetch_data('no', null, null, parameterId);
    });

    $('#generateAdminBookings').on('click', function() {
        var cabin     = $('.admin_cabins_list').val();
        var dates     = $('#adminBookingsDaterange').val();
        var daterange = dates.replace(/\s/g, '');
        if(daterange !== '' && cabin !== '')
        {
            booking_data.destroy();
            fetch_data('yes', daterange, cabin, parameterId);
        }
        else {
            $('.alertMsg').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Leere Felder bitte ausfüllen</div>');
        }
    });

    /* Date range functionality end */

    /* <tfoot> search functionality */
    $('.search-input').on( 'keyup change', function () {
        var i =$(this).attr('id');  // getting column index
        var v =$(this).val();  // getting search input value
        booking_data.columns(i).search(v).draw();
    });

});