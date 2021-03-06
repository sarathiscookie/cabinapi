
$(function () {
    /* Checking for the CSRF token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Tooltip */
    $('[data-toggle="tooltip"]').tooltip();



    /* Data table functionality begin */
    var user_data = $('#user_data').DataTable({
        "lengthMenu": [10, 50, 100, 250, 500],
        "order": [[1, "asc" ]],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/cabinowner/msusers/datatables',
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "usrLogo" },
            { "data": "usrCompany" },
            { "data": "usrEmail" },
            { "data": "usrLastname" },
            { "data": "usrFirstname" },
        ],
        "columnDefs": [
            {
                "orderable": false,
                "targets": [ 0,2,3,4]
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




    /* <tfoot> search functionality */
    $('.search-input').on( 'keyup change', function () {
        var i =$(this).attr('id');   // getting column index
        var v =$(this).val();  // getting search input value
        user_data.columns(i).search(v).draw();
    });
    /* <tfoot> search functionality */
    $('.search-input-name').on( 'keyup change', function () {
        var i =$(this).attr('id');   // getting column index
        var v =$(this).val();   // getting search input value
        user_data.columns(i).search(v).draw();
    });
    /* Data table functionality end */



});