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

    var booking_data;
    var daterange = '';

    /* Helping object for translation */
    var translations = {
        bookingStatusUpdateAlert: window.translations.bookingStatusUpdateAlert,
        wellDone: window.translations.wellDone,
        deleteBookingAlert: window.translations.deleteBookingAlert,
    };

    fetch_data('no');

    function fetch_data(is_date_search, daterange)
    {
        booking_data = $('#booking_data').DataTable({
                "order": [[ 1, "desc" ]],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '/admin/bookings/datatables',
                "dataType": "json",
                "type": "POST",
                "data":{is_date_search:is_date_search, daterange:daterange}
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
        { "data": "payment_status_update" },
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
    }

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
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+translations.wellDone+'</h4>'+result.message+'</div>')
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
        $.ajax({
            url: '/admin/bookings/payment/status/individual',
            data: { bookingId: bookingId },
            dataType: 'JSON',
            type: 'PUT',
            success: function(result) {
                if(result) {
                    booking_data.ajax.reload(null, false);
                    $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+translations.wellDone+'</h4>'+result.message+'</div>')
                    $('.responseMessage').show().delay(5000).fadeOut();
                }
            }
        });
    });

    /* Send invoice functionality */
    $('#booking_data tbody').on( 'click', 'button.sendInvoice', function (e) {
        e.preventDefault();
        var bookingId = $(this).closest('li').data('invoice');
        var $btn      = $(this).button('loading');
        $.ajax({
            url: '/admin/bookings/voucher/',
            data: { bookingId: bookingId },
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
    $('.daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD.MM.YYYY',
            cancelLabel: 'Clear'
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

    /* <tfoot> search functionality */
    $('.search-input').on( 'keyup change', function () {
        var i =$(this).attr('id');  // getting column index
        var v =$(this).val();  // getting search input value
        booking_data.columns(i).search(v).draw();
    });

});