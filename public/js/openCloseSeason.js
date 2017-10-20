$(function(){

    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('[data-toggle="tooltip"]').tooltip();

    /* Helping object for translation */
    var translations = {
        confirmDeleteSummerSeason: window.translations.confirmDeleteSummerSeason,
        confirmDeleteWinterSeason: window.translations.confirmDeleteWinterSeason,
        deleteSummerSeasonSuccess: window.translations.deleteSummerSeasonSuccess,
        deleteWinterSeasonSuccess: window.translations.deleteWinterSeasonSuccess
    };

    /* Delete summer season */
    $('.jquery-postback-summer').on('click', function(e){
        e.preventDefault();
        var $btn      = $(this).button('loading');
        var summerId  = $(this).val();
        var r = confirm(translations.confirmDeleteSummerSeason);
        if (r == true) {
            $.ajax({
                url: '/cabinowner/season/summer/delete',
                data: { summerId: summerId },
                dataType: 'JSON',
                type: 'POST'
            })
                .done(function( response ) {
                    if(response.summerSeasonDeleteStatus == 'success') {
                        $('.statusResponse').html('<div class="alert alert-success alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+translations.deleteSummerSeasonSuccess+'</div>');
                        $btn.button('reset');
                        var delay = 1000;
                        setTimeout(function(){ window.location = '/cabinowner/season'; }, delay);
                    }
                })
                .fail(function() {
                    $('.statusResponse').html('<div class="alert alert-danger alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS! </strong>Hat leider nicht geklappt. Bitte versuchen Sie es erneut</div>');
                    $btn.button('reset');
                });
        }
        else {
            $btn.button('reset');
        }
    });

    /* Delete winter season */
    $('.jquery-postback-winter').on('click', function(e){
        e.preventDefault();
        var $btn      = $(this).button('loading');
        var winterId  = $(this).val();
        var r = confirm(translations.confirmDeleteWinterSeason);
        if (r == true) {
            $.ajax({
                url: '/cabinowner/season/winter/delete',
                data: { winterId: winterId },
                dataType: 'JSON',
                type: 'POST'
            })
                .done(function( response ) {
                    if(response.winterSeasonDeleteStatus == 'success') {
                        $('.statusResponse').html('<div class="alert alert-success alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+translations.deleteWinterSeasonSuccess+'</div>');
                        $btn.button('reset');
                        var delay = 1000;
                        setTimeout(function(){ window.location = '/cabinowner/season'; }, delay);
                    }
                })
                .fail(function() {
                    $('.statusResponse').html('<div class="alert alert-danger alert-dismissible response" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS! </strong>Hat leider nicht geklappt. Bitte versuchen Sie es erneut</div>');
                    $btn.button('reset');
                });
        }
        else {
            $btn.button('reset');
        }
    });

    /* Getting year from dropdown and setting in datepicker */
    $('#summerSeasonYear').on('change', function(){
        var summerSeasonYear = $("#summerSeasonYear").val();
        $( '#summerSeasonYear' ).attr( "data-summerYear", summerSeasonYear );
        var year = $( '#summerSeasonYear' ).val();
        var start = new Date("January 01, "+year+" 00:00:00");
        //var end = new Date(new Date().setYear(start.getFullYear()+1));
        $('#earliest_summer_open').datepicker('setDates', start);
        $('#earliest_summer_close').datepicker('setDates', start);
        $('#latest_summer_open').datepicker('setDates', start);
        $('#latest_summer_close').datepicker('setDates', start);
        $('#summer_next_season').datepicker('setDates', start);
    });

    $('#winterSeasonYear').on('change', function(){
        var winterSeasonYear = $("#winterSeasonYear").val();
        $( '#winterSeasonYear' ).attr( "data-winterYear", winterSeasonYear );
        var year = $( '#winterSeasonYear' ).val();
        var start = new Date("January 01, "+year+" 00:00:00");
        //var end = new Date(new Date().setYear(start.getFullYear()+1));
        $('#earliest_winter_open').datepicker('setDates', start);
        $('#earliest_winter_close').datepicker('setDates', start);
        $('#latest_winter_open').datepicker('setDates', start);
        $('#latest_winter_close').datepicker('setDates', start);
        $('#winter_next_season').datepicker('setDates', start);
    });

    /* Date picker for summer season */
    $('#earliest_summer_open').datepicker({
        autoclose: true
    });
    $('#earliest_summer_close').datepicker({
        autoclose: true
    });
    $('#latest_summer_open').datepicker({
        autoclose: true
    });
    $('#latest_summer_close').datepicker({
        autoclose: true
    });
    $('#summer_next_season').datepicker({
        autoclose: true
    });


    /* Date picker for winter season */
    $('#earliest_winter_open').datepicker({
        autoclose: true
    });
    $('#earliest_winter_close').datepicker({
        autoclose: true
    });
    $('#latest_winter_open').datepicker({
        autoclose: true
    });
    $('#latest_winter_close').datepicker({
        autoclose: true
    });
    $('#winter_next_season').datepicker({
        autoclose: true
    });

});