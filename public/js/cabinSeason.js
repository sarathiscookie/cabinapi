$(document).ready(function () {

    /* Get year from dropdown and set in datepicker */
    $('#summerSeasonYear').on('change', function () {
        var summerSeasonYear = $("#summerSeasonYear").val();
        $('#summerSeasonYear').attr("data-summeryear", summerSeasonYear);
        var year = $('#summerSeasonYear').val();
        var start = new Date("January 01, " + year + " 00:00:00");
        //setDates: to set date

        $('#earliest_summer_open').datepicker('setDates', start);
        $('#earliest_summer_close').datepicker('setDates', start);
        $('#latest_summer_open').datepicker('setDates', start);
        $('#latest_summer_close').datepicker('setDates', start);
        $('#summer_next_season').datepicker('setDates', start);
    });

    $('#winterSeasonYear').on('change', function () {
        var winterSeasonYear = $("#winterSeasonYear").val();
        $('#winterSeasonYear').attr("data-winteryear", winterSeasonYear);
        var year = $('#winterSeasonYear').val();
        var start = new Date("January 01, " + year + " 00:00:00");

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

    <!---------------------------->


    $('#storeSeason').click(function () {

        var $btn = $(this);
        var divId = 'crtSeasonDiv';
        var  url = '/admin/cabinlite/seasondetails/store';
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            type: "POST",
            url: url,
            data: $("#frmseasoncreate").serialize() + '&' + $.param({'storeSeason': $btn.val()}),
            success: function (data) {

                ovelayLoading('remove');//remove loading effect
                $btn.button('reset');
                if ((data.errors)) {

//to do
                } else {
                    ovelayLoading('add', 'crtSeasonDiv'); //adding loading effect
                    data = JSON.parse(data);
                    //  append success message
                    if (data.errorMsg != undefined) {
                        var msgClass = 'alert-danger';
                        var msgText = data.errorMsg;

                    }
                    else {
                        var msgClass = 'alert-success';
                        var msgText = data.successMsgSeasonSave;
                    }
                    var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                    $(msg).prependTo('#' + divId).fadeIn(100);
                    setTimeout(function () {
                        $('#' + divId + ' #flash').fadeOut()
                    }, 3000);

                    ovelayLoading('add', 'listSeasonDiv');
                    loadListingSeason();
                    ovelayLoading('remove');

                }
            },

            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                if (jqXHR.status == 422) {
                    var errData = jqXHR.responseJSON;

                    $.each(errData, function (i, item) {

                        $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("input[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                        $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("select[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                      /*  $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                        $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                        $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                        */
                    });

                    $btn.button('reset');
                }

                console.log(" error: " + textStatus + ' : ' + errorThrown);
            }
        }).done(function (data) {

        });
    });

    /* Helping object for translation */

});//close doc ready

/*
loadListingSeason for load Listing
 */
function loadListingSeason() {

    $("#crtSeasonDiv").fadeOut();
    $("#listSeasonDiv").fadeIn();
    $.ajax({
        type: "POST",
        url: '/admin/cabinlite/seasondetails/lists',
        data: $("#frmseasoncreate").serialize(),
        success: function (data) {

            $("#crtSeasonDiv").fadeOut();
            $("#listSeasonDiv").html(data);
            $("#listSeasonDiv").fadeIn();
        }
    });
}

$('#crtNewSeason').click(function () {
    $('#frmseasoncreate').get(0).reset();
    $('.form-group').removeClass('has-error');
    $('#storeSeason').button('reset');
    $("#crtSeasonDiv").fadeIn();
    $("#listSeasonDiv").fadeOut();
    $('.season-edit-tr').remove();

});
$('#listSeason').click(function () {
    $("#crtSeasonDiv").fadeOut();
    $("#listSeasonDiv").fadeIn();
});

/* Scroll to a particulare ID*/
function scrollToID(argId) {
    $('html, body').animate({
        'scrollTop': $("#" + argId).position().top
    });
}
/* Helping object for translation */
var translations = {
    confirmDeleteSummerSeason: window.translations.confirmDeleteSummerSeason,
    confirmDeleteWinterSeason: window.translations.confirmDeleteWinterSeason,

};
/* Delete Season */
function deleteSesonSummer(argId,csrf_token) {

    var con = confirm(translations.confirmDeleteSummerSeason);
    var divId = 'listSeasonDiv';
    if (con == true) {

        var trId =  'summer_'+argId;
        console.log(trId);
        ovelayLoading('add',trId);
        var url = '/admin/cabinlite/seasondetails/summer/delete';
        $.ajax({
            type: "POST",
            url: url,
            data: {'summerId':argId , 'cabin_id':$('input[name="cabin_id"]').val(), "_token": csrf_token  },
            success: function (data) {

                ovelayLoading('remove');//remove loading effect
                if(data.summerSeasonDeleteStatus == 'success') {
                    $('#' + trId).fadeOut();

//to d
                    ovelayLoading('add', 'crtSeasonDiv'); //adding loading effect

                    //  append success message

                    var msgClass = 'alert-success';
                    var msgText = data.delMsg;

                    var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                    $(msg).prependTo('#' + divId).fadeIn(100);
                    setTimeout(function () {
                        $('#' + divId + ' #flash').fadeOut()
                    }, 2000);


                    loadListingSeason();
                    ovelayLoading('remove');

                }
            },

            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail


                console.log(" error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }
    else{

    }
}

function deleteSesonWinter(argId,csrf_token) {

    var con = confirm(translations.confirmDeleteWinterSeason);
    var divId = 'listSeasonDiv';
    if (con == true) {
        var trId =  'winter_'+argId;
        ovelayLoading('add',trId);
        var url = '/admin/cabinlite/seasondetails/winter/delete';
        $.ajax({
            type: "POST",
            url: url,
            data: {'winterId':argId , 'cabin_id':$('input[name="cabin_id"]').val(), "_token": csrf_token  },
            success: function (data) {
                ovelayLoading('remove');//remove loading effect
                if(data.winterSeasonDeleteStatus == 'success') {
                    $('#' + trId).fadeOut();
                    ovelayLoading('add', divId); //adding loading effect
                    //  append success message
                    var msgClass = 'alert-success';
                    var msgText = data.delMsg;
                    var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                    $(msg).prependTo('#' + divId).fadeIn(100);
                    setTimeout(function () {
                        $('#' + divId + ' #flash').fadeOut()
                    }, 2000);
                    loadListingSeason();
                    ovelayLoading('remove');
                }
            },

            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                console.log(" error: " + textStatus + ' : ' + errorThrown);
            }
        });
    }
    else{

    }
}
/* Clear form  */
function clear_form_elements(ele) {

    $(ele).find(':input').each(function () {
        switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
        }
    });

}

<!---------------------------->


$(document).ready(function () {

    $(document).on("click", '.edit-season', function(event) {
        $('.season-edit-tr').fadeOut();
        var trId = $(this).parents('tr').attr('id');
        $('#listSeasonDiv').find('tr').removeClass('trActiveclass');
        $('#' + trId).addClass('trActiveclass');
        ovelayLoading('add', trId);
        var seasonId = $(this).parents('tr').data('season');
      //  console.log(seasonId);
        $.ajax({
            type: "GET",
            url: '/admin/cabinlite/seasondetails/summer/edit',
            data: {'season_id': seasonId},
            success: function (data) {
                ovelayLoading('remove');
                $('.season-edit-tr').remove();
                var html = data;
                jQuery("#" + trId).after(html);
            }
        });
    });
    $(document).on("click", '.edit-winter-season', function(event) {
        $('.season-edit-tr').fadeOut();
        var trId = $(this).parents('tr').attr('id');
        $('#listSeasonDiv').find('tr').removeClass('trActiveclass');
        $('#' + trId).addClass('trActiveclass');
        ovelayLoading('add', trId);
        var seasonId = $(this).parents('tr').data('season');
        console.log(seasonId);
        $.ajax({
            type: "GET",
            url: '/admin/cabinlite/seasondetails/winter/edit',
            data: {'season_id': seasonId},
            success: function (data) {
                ovelayLoading('remove');
                $('.season-edit-tr').remove();
                var html = data;
                jQuery("#" + trId).after(html);
            }
        });
    });

});// doc ready close
//--------------------------------//
