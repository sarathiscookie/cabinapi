/**
 * Created by PhpStorm.
 * User: user
 * Date: 28-08-2017
 * Time: 13:44
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

    var booking_data = $('#booking_data').DataTable({
        "lengthMenu": [10, 50, 100, 250, 500],
        "order": [[ 1, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/admin/users/datatables',
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "hash" },
            { "data": "firstname" },
            { "data": "lastname" },
            { "data": "username" },
            { "data": "email" },
            { "data": "balance" },
            { "data": "bookings" },
            { "data": "jumpto" },
            { "data": "lastlogin" },
            { "data": "rights" },
            { "data": "actionone" },
            { "data": "actiontwo" }
        ],
        "columnDefs": [
            {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4, 7, 8, 9, 10, 11]
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
                    columns: [ 1, 2, 3, 4, 5, 6, 8, 9 ]
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 8, 9 ]
                }
            },
            {
                extend: 'pdf',
                orientation: 'portrait',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 8, 9 ]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 8, 9 ]
                }
            }
        ]
    }).container().appendTo($('#buttons'));

});