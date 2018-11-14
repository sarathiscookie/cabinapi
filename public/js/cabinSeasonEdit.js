$(function() {
    // Update summer season
    $('#updateSummerSeason').on('click', function () {
        var $btn     = $(this);
        divId        = 'listSeasonDiv';
        url          = '/admin/cabinlite/seasondetails/summer/update';
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            dataType: 'JSON',
            type: "POST",
            url: url,
            data: $("#frmseasonedit").serialize() + '&' + $.param({
                'updateSummerSeason': $btn.val(),
                'cabin_id': $('input[name="cabin_id"]').val()
            }),
            success: function (data) {
                $btn.button('reset');
                if (data.errorMsg != undefined) {
                    var msgClass = 'alert-danger';
                    var msgText = data.errorMsg;
                }
                else {
                    var msgClass = 'alert-success';
                    var msgText = data.successMsgSeasonSave;
                }
                var msg = '<div id="flash" style="width: 90%; margin-left:20px;  " class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';
                scrollToID(divId);
                $(msg).prependTo('#' + divId).fadeIn(100);
                setTimeout(function () {
                    $('#' + divId + ' #flash').fadeOut()
                }, 2000);
                loadListingSeason();
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
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

    $('.listSeason').click(function () {
        $(".season-edit-tr").fadeOut();
        $("#listSeasonDiv").fadeIn();
        $('#listSeasonDiv').find('tr').removeClass('trActiveclass');
    });

    /* Date picker for summer season */
    $('#summer_next_season_upt').datepicker({
        autoclose: true
    });

    $('#earliest_summer_open_upt').datepicker({
        autoclose: true
    });

    $('#earliest_summer_close_upt').datepicker({
        autoclose: true
    });

    $('#summer_next_season_upt').datepicker({
        autoclose: true
    });

    $('#latest_summer_open_upt').datepicker({
        autoclose: true
    });

    $('#latest_summer_close_upt').datepicker({
        autoclose: true
    });

    // Get year from dropdown and set in summner datepicker
    $('#summerSeasonYearUpt').on('change', function () {
        var summerSeasonYear = $("#summerSeasonYearUpt").val();
        $('#summerSeasonYearUpt').attr("data-summeryear", summerSeasonYear);
        var year  = $('#summerSeasonYearUpt').val();
        var start = new Date("January 01, " + year + " 00:00:00");
        //setDates: to set date

        $('#earliest_summer_open_upt').datepicker('setDates', start);
        $('#earliest_summer_close_upt').datepicker('setDates', start);
        $('#latest_summer_open_upt').datepicker('setDates', start);
        $('#latest_summer_close_upt').datepicker('setDates', start);
        $('#summer_next_season_upt').datepicker('setDates', start);
    });

    // Update winter season
    $('#updateWinterSeason').on('click', function() {

        var $btn = $(this);
        divId    = 'listSeasonDiv';
        url      = '/admin/cabinlite/seasondetails/winter/update';
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            dataType: 'JSON',
            type: "POST",
            url: url,
            data: $("#frmwinterseasonedit").serialize() + '&' + $.param({
                'updateWinterSeason': $btn.val(),
                'cabin_id': $('input[name="cabin_id"]').val()
            }),
            success: function (data) {
                $btn.button('reset');
                if (data.errorMsg != undefined) {
                    var msgClass = 'alert-danger';
                    var msgText = data.errorMsg;

                }
                else {
                    var msgClass = 'alert-success';
                    var msgText = data.successMsgSeasonSave;
                }
                var msg = '<div id="flash"  style="width: 90%; margin-left:20px;  " class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                scrollToID(divId);

                $(msg).prependTo('#' + divId).fadeIn(100);

                setTimeout(function () {
                    $('#' + divId + ' #flash').fadeOut()
                }, 2000);

                loadListingSeason();
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
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

    /* Date picker for winter season */
    $('#winter_next_season_upt').datepicker({
        autoclose: true
    });

    $('#earliest_winter_open_upt').datepicker({
        autoclose: true
    });

    $('#earliest_winter_close_upt').datepicker({
        autoclose: true
    });

    $('#winter_next_season_upt').datepicker({
        autoclose: true
    });

    $('#latest_winter_open_upt').datepicker({
        autoclose: true
    });

    $('#latest_winter_close_upt').datepicker({
        autoclose: true
    });

    // Get year from dropdown and set in winter datepicker
    $('#winterSeasonYearUpt').on('change', function () {
        var winterSeasonYear = $("#winterSeasonYearUpt").val();
        $('#winterSeasonYearUpt').attr("data-winteryear", winterSeasonYear);
        var year  = $('#winterSeasonYearUpt').val();
        var start = new Date("January 01, " + year + " 00:00:00");

        $('#earliest_winter_open_upt').datepicker('setDates', start);
        $('#earliest_winter_close_upt').datepicker('setDates', start);
        $('#latest_winter_open_upt').datepicker('setDates', start);
        $('#latest_winter_close_upt').datepicker('setDates', start);
        $('#winter_next_season_upt').datepicker('setDates', start);
    });
});









