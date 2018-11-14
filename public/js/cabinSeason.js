$(function(){
    // Get year from dropdown and set in summerseason datepicker
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

    // Get year from dropdown and set in winterseason datepicker
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

    // Date picker for summer season
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

    // Date picker for winter season
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

    // Edit summer season
    $('.edit-season').on("click", function() {
        $('.season-edit-tr').fadeOut();
        var trId     = $(this).parents('tr').attr('id');
        $('#listSeasonDiv').find('tr').removeClass('trActiveclass');
        $('#' + trId).addClass('trActiveclass');
        ovelayLoading('add', trId);
        var seasonId = $(this).parents('tr').data('season');
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

    // Edit winter season
    $('.edit-winter-season').on("click", function() {
        $('.season-edit-tr').fadeOut();
        var trId     = $(this).parents('tr').attr('id');
        $('#listSeasonDiv').find('tr').removeClass('trActiveclass');
        $('#' + trId).addClass('trActiveclass');
        ovelayLoading('add', trId);
        var seasonId = $(this).parents('tr').data('season');
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

    // Create new season
    $('#storeSeason').on('click', function () {
        var $btn  = $(this);
        var divId = 'crtSeasonDiv';
        var  url  = '/admin/cabinlite/seasondetails/store';
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            dataType: 'JSON',
            type: 'POST',
            url: url,
            data: $("#frmseasoncreate").serialize() + '&' + $.param({'storeSeason': $btn.val()}),
            success: function (data) {
                ovelayLoading('remove');//remove loading effect
                $btn.button('reset');

                // Append success message
                if (data.errorMsg != undefined) {
                    var msgClass = 'alert-danger';
                    var msgText  = data.errorMsg;

                }
                else {
                    var msgClass = 'alert-success';
                    var msgText  = data.successMsgSeasonSave;
                }
                var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                $(msg).prependTo('#' + divId).fadeIn(100);

                setTimeout(function () {
                    $('#' + divId + ' #flash').fadeOut()
                }, 3000);

                loadListingSeason();
            },

            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status == 422) {
                    var errData = jqXHR.responseJSON;
                    $.each(errData, function (i, item) {

                        $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("input[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                        $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("select[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                    });

                    $btn.button('reset');
                }
            }
        });
    });
});

/* Helping object for translation */
var translations = {
    confirmDeleteSummerSeason: window.translations.confirmDeleteSummerSeason,
    confirmDeleteWinterSeason: window.translations.confirmDeleteWinterSeason
};

/* Delete summer season */
function deleteSummerSeason(argId,csrf_token) {
    var con = confirm(translations.confirmDeleteSummerSeason);
    var divId = 'listSeasonDiv';
    if (con === true) {
        var trId = 'summer_'+argId;
        ovelayLoading('add',trId);
        var url = '/admin/cabinlite/seasondetails/summer/delete';
        $.ajax({
            dataType: 'JSON',
            type: 'POST',
            url: url,
            data: {'summerId':argId , 'cabin_id':$('input[name="cabin_id"]').val(), "_token": csrf_token},
            success: function (data) {
                ovelayLoading('remove'); // Remove loading effect

                if(data.summerSeasonDeleteStatus === 'success') {
                    $('#' + trId).fadeOut();

                    ovelayLoading('add', 'crtSeasonDiv'); // Add loading effect

                    // append success message
                    var msgClass = 'alert-success';
                    var msgText  = data.delMsg;
                    var msg      = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                    $(msg).prependTo('#' + divId).fadeIn(100);

                    setTimeout(function () {
                        $('#' + divId + ' #flash').fadeOut()
                    }, 2000);

                    loadListingSeason();
                    ovelayLoading('remove');
                }
            }
        });
    }
}

// Delete winter season
function deleteWinterSeason(argId, csrf_token) {
    var con      = confirm(translations.confirmDeleteWinterSeason);
    var divId    = 'listSeasonDiv';
    if (con === true) {
        var trId = 'winter_'+argId;
        ovelayLoading('add',trId);
        var url  = '/admin/cabinlite/seasondetails/winter/delete';
        $.ajax({
            dataType: 'JSON',
            type: 'POST',
            url: url,
            data: {'winterId':argId, 'cabin_id':$('input[name="cabin_id"]').val(), "_token": csrf_token},
            success: function (data) {
                ovelayLoading('remove');// Remove loading effect

                if(data.winterSeasonDeleteStatus == 'success') {
                    $('#' + trId).fadeOut();

                    ovelayLoading('add', divId); // Add loading effect

                    // Append success message
                    var msgClass = 'alert-success';
                    var msgText  = data.delMsg;
                    var msg      = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                    $(msg).prependTo('#' + divId).fadeIn(100);

                    setTimeout(function () {
                        $('#' + divId + ' #flash').fadeOut()
                    }, 2000);

                    loadListingSeason();

                    ovelayLoading('remove');
                }
            }
        });
    }
}

// Loading seasons
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

// Scroll to a particular ID
function scrollToID(argId) {
    $('html, body').animate({
        'scrollTop': $("#" + argId).position().top
    });
}



