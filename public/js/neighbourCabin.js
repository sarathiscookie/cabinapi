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
    var normalCabin = $('#normalCabin').DataTable({
        "bSort": false,
        "lengthMenu": [10, 50, 100, 250, 500],
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": '/admin/neighbour/cabins/datatables',
            "dataType": "json",
            "type": "POST"
        },
        "columns": [
            { "data": "cabinName" },
            { "data": "switchToNormalCabin" }
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
        normalCabin.columns(i).search(v).draw();
    });

    $('.search-input-cabin').on( 'keyup change', function () {
        var i =$(this).attr('id');   // getting column index
        var v =$(this).val();   // getting search input value
        normalCabin.columns(i).search(v).draw();
    });
    /* Data table functionality end */


    // Normal cabin switch to neighbour cabin
    $('#normalCabin tbody').on( 'click', 'button.switchToNormalCabin', function(e){
        e.preventDefault();

        var cabinId = $(this).val();
        var $btn    = $(this).button('loading');

        $.ajax({
            url: '/admin/neighbour/cabins/switch/to/normal',
            data: { "cabinId": cabinId },
            dataType: 'JSON',
            type: 'PUT'
        })
            .done(function( result ) {
                $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+result.successMsg+'</div>');
                $btn.button('reset');
                normalCabin.ajax.reload(null, false);
            })
            .fail(function() {
                $('.responseMessage').html('<div class="alert alert-warning alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS! </strong>Hat leider nicht geklappt. Bitte versuchen Sie es erneut</div>');
                normalCabin.ajax.reload(null, false);
            });
    });

});

