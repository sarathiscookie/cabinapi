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

    /* Helping object for translation */
    var translations = {
        userStatusResponseFailMsg: window.translations.userStatusResponseFailMsg
    };

    /* Data table functionality begin */
    var user_data = $('#user_data').DataTable({
        "lengthMenu": [10, 50, 100, 250, 500],
        "order": [[ 12, "desc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/admin/users/datatables',
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "hash" },
            { "data": "usrLastname" },
            { "data": "usrFirstname" },
            { "data": "usrName" },
            { "data": "usrEmail" },
            { "data": "money_balance" },
            { "data": "bookings" },
            { "data": "jumpto" },
            { "data": "lastlogin" },
            { "data": "rights" },
            { "data": "actionone" },
            { "data": "actiontwo" },
            { "data": "usrRegistrationDate" }
        ],
        "columnDefs": [
            {
                "orderable": false,
                "targets": [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12]
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

    /* Visiblity disabled of "usrRegistrationDate" column */
    user_data.column(12).visible( false );

    /* Bottom buttons for datatables */
    var buttons = new $.fn.dataTable.Buttons(user_data, {
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

    /* <tfoot> search functionality */
    $('.search-input').on( 'keyup change', function () {
        var i =$(this).attr('id');  // getting column index
        var v =$(this).val();  // getting search input value
        user_data.columns(i).search(v).draw();
    });

    /* Data table functionality end */

    /* Activate & Deactivate user functionality begin */
    $('#user_data tbody').on( 'click', 'a.userStatus', function (e) {
        e.preventDefault();
        var data_id     = $(this).data('id');
        var data_status = $(this).data('status');
        $.ajax({
            url: '/admin/users/status',
            data: { data_id: data_id, data_status: data_status },
            dataType: 'JSON',
            type: 'PUT'
        })
            .done(function( response ) {
                $('.responseStatusMessage').html('<div class="alert alert-success alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.statusMessage+'</div>');
                user_data.ajax.reload(null, false);
            })
            .fail(function() {
                $('.responseStatusMessage').html('<div class="alert alert-warning alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong>'+translations.userStatusResponseFailMsg+'</div>');
                user_data.ajax.reload(null, false);
            });
    });
    /* Activate & Deactivate user functionality end */

    /* Delete user functionality begin*/
    $('#user_data tbody').on( 'click', 'a.deleteUserList', function (e) {
        e.preventDefault();
        var data_id     = $(this).data('id');
        $.ajax({
            url: '/admin/users/destroy',
            data: { data_id: data_id },
            dataType: 'JSON',
            type: 'DELETE'
        })
            .done(function( response ) {
                $('.responseStatusMessage').html('<div class="alert alert-success alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+response.deleteResponseMsg+'</div>');
                user_data.ajax.reload(null, false);
            })
            .fail(function() {
                $('.responseStatusMessage').html('<div class="alert alert-warning alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong>'+translations.userStatusResponseFailMsg+'</div>');
                user_data.ajax.reload(null, false);
            });
    });
    /* Delete user functionality end*/

});