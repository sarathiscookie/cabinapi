/**
 * Created by Divya on 07-11-2017.
 */
$(function() {
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    if($('.responseMessage').html()!='')
        $('.responseMessage').show().delay(5000).fadeOut();
    /* Helping object for translation */
    var translations = {
        deleteCol: window.translations.deleteCol,
        deleteRow: window.translations.deleteRow,
    };


    /* function to delete a row */
    $("body").on("click", ".rdel",function(){
        var r = confirm(translations.deleteRow);
        if (r == true) {
            $(this).parent().parent().remove();
        }
        else
            return false;
    });

    /*function to delete a column*/
    $("body").on("click", ".cdel", function ( event ) {
        var r = confirm(translations.deleteCol);
        if (r == true) {
            var ndx = $(this).parent().index() + 1;
            $("td", event.delegateTarget).remove(":nth-child(" + ndx + ")");
        }
        else
            return false;

    });

    /*function to add a row*/
    $('body').on('click', '.irow', function(){
        $(this).closest( "tr" ).after($("#mtable tbody tr:last").clone());
        return false;
    });

    $('body').on('click', '.icol', function(){

        $('#mtable tr ').append($("<td>"));
        var i=0;
        $('#mtable tbody tr').each(function() {
            if (i == 0)
                $(this).children('td:last').append($('<button class="icol btn-primary">+</button><input class="cdel btn-primary" value="-" type="button">'))
            else if
            (i == 1) $(this).children('td:last').append($('<input class="form-control" type="text" name="price_type[]">'))
            else
            {$(this).children('td:last').append($('<input class="form-control" type="text" name="price[]">'))}
            i++;
        });
        return false;
    });


});
