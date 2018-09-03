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

    if ($("#tour_data").length) {
        var tour_data = $('#tour_data').DataTable({
            "lengthMenu": [10, 50, 100, 250, 500],
            "order": [[1, "asc"]],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '/mountainschool/tours/datatables',
                "dataType": "json",
                "type": "POST"
            },
            "columns": [
                {"data": "tour_no"},
                {"data": "tour_name"},
                {"data": "no_cabins"},
                {"data": "cabins"},
                {"data": "Edit"}
            ],
            "columnDefs": [
                {
                    "orderable": false,
                    "targets": [2, 3, 4 ]
                }
            ],
            "language": {
                "sEmptyTable": "Keine Daten in der Tabelle vorhanden",
                "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
                "sInfoEmpty": "0 bis 0 von 0 Einträgen",
                "sInfoFiltered": "(gefiltert von _MAX_ Einträgen)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ Einträge anzeigen",
                "sLoadingRecords": "Wird geladen...",
                "sProcessing": "Bitte warten...",
                "sSearch": "Suchen",
                "sZeroRecords": "Keine Einträge vorhanden.",
                "oPaginate": {
                    "sFirst": "Erste",
                    "sPrevious": "Zurück",
                    "sNext": "Nächste",
                    "sLast": "Letzte"
                },
                "oAria": {
                    "sSortAscending": ": aktivieren, um Spalte aufsteigend zu sortieren",
                    "sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
                }
            }
        });


        /* <tfoot> search functionality */
        $('.search-input').on('keyup change', function () {
            var i = $(this).attr('id');   // getting column index
            var v = $(this).val();  // getting search input value
            tour_data.columns(i).search(v).draw();
        });
        /* <tfoot> search functionality */
        $('.search-input-tourname').on('keyup change', function () {
            var i = $(this).attr('id');   // getting column index
            var v = $(this).val();   // getting search input value
            tour_data.columns(i).search(v).draw();
        });
    }
    /* Data table functionality end */
});

/* create new cabin  */
$('#createCabin').click(function (e) {
    var divId     = 'new_cabin';
    ovelayLoading('add', divId); //adding loading effect
    var $btn      = $(this);

    $('#' + divId).find('.has-error').removeClass('has-error');
    $('#' + divId).find('.help-block').html('<strong></strong>');

    $btn.button('loading');
    $.ajax({
        type: "POST",
        url: '/mountainschool/tours/createtour/createnewcabin',
        data: $("#addcabinFrm").serialize() + '&' + $.param({'formPart': $btn.val()}),
        success: function (data) {
            ovelayLoading('remove');//remove loading effect
            $btn.button('reset');
            if ((data.errors)) {
                $.each(data.errors, function (i, item) {
                    $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                    $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                });
            }
            else {
                data = JSON.parse(data);

                if (data.errorMsg != undefined) {
                    var msgClass = 'alert-danger';
                    var msgText = data.errorMsg;
                }
                else {
                    var msgClass = 'alert-success';
                    var msgText = data.successMsg;
                }

                var rowId = $("#createCabin").parents('.box-solid').closest('.row').attr('rid');
                var html = ' <div class="row" id="crowid' + rowId + '" rid="' + rowId + '"><div class="col-md-6"  >    <div class="form-group"  >      <label> ' + lblCabin + rowId + lblReqSpanCabin + '</label><input type="text" name="cabins[]" class="form-control" value="' + $('#cabin_name').val() + '" readonly="readonly">  </div>   </div><div class="col-md-1"  ><a href="javascript:void(0)"  class="delRow"> <img src="/img/delete.png" alt="Remove" width="25" hight="25" style=" position: relative; bottom: -30px;"></a>  </div> </div>';
                // cabinCountFilter(rowId);
                $("#newcabinRowID").closest('div[class^="row"]').remove();
                $(html).insertBefore("#tour_cabins_row");

                var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                $(msg).prependTo('#' + divId).fadeIn(100);
                setTimeout(function () {
                    $('#' + divId + ' #flash').fadeOut()
                }, 2000);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            ovelayLoading('remove');

            if (jqXHR.status == 422) {
                var errData = jqXHR.responseJSON;
                $.each(errData, function (i, item) {
                    $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                    $("input[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                });

                $btn.button('reset');
            }
        }
    });
});

/* check new cabin add section exits */
function checkNewCabinSection()
{
    if ($('#newcabinRowID').length)
    {
        alert("Please add new cabin first.");
        $('#newcabinRowID').addClass(' box-danger');
        $( "#createCabin" ).trigger( "click" );
        $('#cabin_name').focus();
        return false;
    }
}


/* Add new cabin UI */
function addNewCabin(i)
{
    $.ajax({
        type: "GET",
        url: '/mountainschool/tours/addnewcabin',
        data: {},
        success: function (data) {
            var html = '<div class="row" id="crowid' + i + '" rid="' + i + '"><div class="col-md-6"   ><div class="form-group"  >      <label> ' + lblCabin + i + '</label> ' + data + ' </div> </div> <div class="col-md-1"   > <a href="javascript:void(0)"  class="delRow"> <img src="/img/delete.png" alt="Remove" width="25" hight="25" style=" position: relative;   bottom:-30px;"></a> </div>   </div> ';
            $(html).insertBefore("#tour_cabins_row");
        }
    });
}

/* Overlay after submit */
function ovelayLoading(arg, appendDiv)
{
    if (arg == 'add') {
        var overlay = jQuery('<div id="overlay">  </div>');
        overlay.appendTo('#' + appendDiv);
    }

    if (arg == 'remove') {
        $("#overlay").remove();
    }
}