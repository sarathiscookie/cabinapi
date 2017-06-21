/**
 * Created by user on 21-06-2017.
 */
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
        "dom": '<"toolbar">frtip',
        "responsive": true,
        "ajax": {
            "type": "POST",
            "url": '/admin/bookings/datatables',
            "contentType": 'application/json; charset=utf-8',
            "data": { "_token": "" }
        },
        /*"ajax": '/bookings/datatables',*/
        "dataType": "jsonp",
        "columns": [
        {"data": function(data){
            return '<input type="checkbox" name="id[]" value="'+ data._id +'" />';
        }, "orderable": false, "searchable": false, "name":"_id" },
        {"data": function ( data ) {
            if(!data.invoice_number){
                return '<span class="label label-default">No data</span>'
            }
            else {
                return '<a class="nounderline modalBooking" data-toggle="modal" data-target="#bookingModal_'+ data._id +'" data-modalID="'+ data._id +'">'+data.invoice_number+'</a><div class="modal fade" id="bookingModal_'+ data._id +'" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel"><div class="modal-dialog"> <div class="modal-content"><div class="modal-header"> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Booking Details</h4></div><div class="alert alert-success alert-dismissible alert-invoice" style="display: none;"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> Well Done</h4>voucher send successfully</div><div class="modal-body"><ul class="list-group"><li class="list-group-item"><h4 class="list-group-item-heading">Cabin Name</h4><p class="list-group-item-text">'+ data.cabinname +'</p></li><li class="list-group-item"><h4 class="list-group-item-heading">Reference no</h4><p class="list-group-item-text">'+ data.reference_no +'</p></a><li class="list-group-item"><h4 class="list-group-item-heading">Club Member</h4><p class="list-group-item-text">'+ data.clubmember +'</p></li><li class="list-group-item" data-invoice="'+ data._id +'"><h4 class="list-group-item-heading">Voucher</h4><button class="btn btn-primary btn-sm sendInvoice" data-loading-text="Sending..." autocomplete="off"><i class="fa fa-envelope"></i> Send</button></li></ul></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>';
            }
        }, "name": "invoice_number"},
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
        "targets": "_all"
    }],
});

    $('#dataTable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search ' + title + '"/>' );
    });

    var buttons = new $.fn.dataTable.Buttons(table, {
        buttons: [
            {
                extend: 'csv',
                exportOptions: {
                    columns: [ 2, 3, 4, 7, 8, 9, 10 ]
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [ 2, 3, 4, 7, 8, 9, 10 ]
                }
            },
            {
                extend: 'pdf',
                orientation: 'portrait',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 2, 3, 4, 7, 8, 9, 10 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 2, 3, 4, 7, 8, 9, 10 ]
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

    /* Functionality for date range begin */
    $("div.toolbar").html('<label>Daterange: </label> <input id="date_range" type="text">');

    $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            "cancelLabel": "Clear",
            "format": 'DD.MM.YYYY'
        }
    });
    $("#date_range").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + ' to ' + picker.endDate.format('DD.MM.YYYY'));
        table.draw();
    });

    $("#date_range").on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        table.draw();
    });


    /* Funtionality for daterange in datatables */
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {

            var grab_daterange = $("#date_range").val();
            var give_results_daterange = grab_daterange.split(" to ");
            var filterstart = give_results_daterange[0];
            var filterend = give_results_daterange[1];
            var iStartDateCol = 3; //using column 2 in this instance
            var iEndDateCol = 3;
            var tabledatestart = aData[iStartDateCol];
            var tabledateend= aData[iEndDateCol];

            if ( !filterstart && !filterend )
            {
                return true;
            }
            else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && filterend === "")
            {
                return true;
            }
            else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isAfter(tabledatestart)) && filterstart === "")
            {
                return true;
            }
            else if ((moment(filterstart).isSame(tabledatestart) || moment(filterstart).isBefore(tabledatestart)) && (moment(filterend).isSame(tabledateend) || moment(filterend).isAfter(tabledateend)))
            {
                return true;
            }
            return false;
        }
    );

    /* Send invoice */
    $('#dataTable tbody').on( 'click', 'button.sendInvoice', function (e) {
        var bookingId = $(this).closest('li').data('invoice');
        var $btn      = $(this).button('loading');
        $.ajax({
            url: '/admin/bookings/voucher/' + bookingId,
            data: { "_token": "" },
            type: 'POST',
            success: function(result) {
                if(result){
                    $('.alert-invoice').show().delay(5000).fadeOut();
                    $btn.button('reset');
                }
            }
        });
    });

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
                    if(result) {
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> Well Done</h4>'+result.message+'</div>')
                        $('.responseMessage').show().delay(5000).fadeOut();
                    }
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