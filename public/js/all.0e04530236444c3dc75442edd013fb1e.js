$(function () {
    /* Checking for the CSRF token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Calendar availability check begin */
    $("body").on("mousedown", ".checkInCls", function() {
        var dataId          = $(this).parent().parent().parent().data("id");
        var $this           = $("#check_in"+dataId);
        var cabinId         = $(this).parent().parent().parent().data("cabinid");
        var returnResult    = [];
        var holidayDates    = $(".holiday"+dataId).data("holiday");
        var greenDates      = $(".green"+dataId).data("green");
        var orangeDates     = $(".orange"+dataId).data("orange");
        var redDates        = $(".red"+dataId).data("red");
        var not_season_time = $(".notSeasonTime"+dataId).data("notseasontime");
        var start_date      = '';

        $this.datepicker({
            showAnim: "drop",
            dateFormat: "dd.mm.y",
            changeMonth: true,
            changeYear: true,
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
            dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
            minDate: '+1d',
            yearRange: "0:+2"
        });

        $this.datepicker("option", "onSelect", function(date) {
            var dt2       = $("#check_out"+dataId);
            var startDate = $this.datepicker('getDate');
            var minDate   = $this.datepicker('getDate');
            dt2.datepicker('setDate', minDate);
            startDate.setDate(startDate.getDate() + 60); //sets dt2 maxDate to the last day of 60 days window
            minDate.setDate(minDate.getDate() + 1); //sets dt2 minDate to the +1 day of from date
            dt2.datepicker('option', 'maxDate', startDate);
            dt2.datepicker('option', 'minDate', minDate);
        });

        $this.datepicker("option", "onChangeMonthYear", function(year,month,inst) {
            if (year != undefined && month != undefined) {
                start_date = year +'-';
                start_date += month +'-';
                start_date += '01';
            }
            $.ajax({
                url: '/mountainschool/calendar/ajax',
                dataType: 'JSON',
                type: 'POST',
                data: { dateFrom: start_date, cabinId: cabinId },
                success: function (response) {
                    for (var i = 0; i < response.holidayDates.length; i++) {
                        holidayDates.push(response.holidayDates[i]);
                    }

                    for (var i = 0; i < response.greenDates.length; i++) {
                        greenDates.push(response.greenDates[i]);
                    }

                    for (var i = 0; i < response.orangeDates.length; i++) {
                        orangeDates.push(response.orangeDates[i]);
                    }

                    for (var i = 0; i < response.redDates.length; i++) {
                        redDates.push(response.redDates[i]);
                    }

                    for (var i = 0; i < response.not_season_time.length; i++) {
                        not_season_time.push(response.not_season_time[i]);
                    }

                    $this.datepicker("refresh");
                }
            });
        });

        $this.datepicker("option", "beforeShowDay", function(date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            if( greenDates.indexOf(string) >=0 ) {
                returnResult = [true, "greenDates", "Verfügbar"];
            }
            if( orangeDates.indexOf(string) >=0 ) {
                returnResult = [true, "orangeDates", "Begrenzt"];
            }
            if( redDates.indexOf(string) >=0 ) {
                returnResult = [true, "redDates", "Ausgebucht"];
            }
            if( not_season_time.indexOf(string) >=0 ) {
                returnResult = [false, "", "Geschlossen"];
            }
            if( holidayDates.indexOf(string) >=0 ) {
                returnResult = [false, "", "Ruhetag"];
            }
            return returnResult;
        });

        $this.datepicker("show");
    });

    $("body").on("mousedown", ".checkOutCls", function() {
        var dataId          = $(this).parent().parent().parent().data("id");
        var $this           = $("#check_out"+dataId);
        var cabinId         = $(this).parent().parent().parent().data("cabinid");
        var returnResults   = [];

        var holidayDates    = $(".holiday"+dataId).data("holiday");
        var greenDates      = $(".green"+dataId).data("green");
        var orangeDates     = $(".orange"+dataId).data("orange");
        var redDates        = $(".red"+dataId).data("red");
        var not_season_time = $(".notSeasonTime"+dataId).data("notseasontime");
        var start_date      = '';

        $this.datepicker({
            showAnim: "drop",
            dateFormat: "dd.mm.y",
            changeMonth: true,
            changeYear: true,
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
            dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
            yearRange: "0:+2"
        });

        $this.datepicker("option", "onChangeMonthYear", function(year,month,inst) {
            if (year != undefined && month != undefined) {
                start_date = year +'-';
                start_date += month +'-';
                start_date += '01';
            }
            $.ajax({
                url: '/mountainschool/calendar/ajax',
                dataType: 'JSON',
                type: 'POST',
                data: { dateFrom: start_date, cabinId: cabinId },
                success: function (response) {
                    for (var i = 0; i < response.holidayDates.length; i++) {
                        holidayDates.push(response.holidayDates[i]);
                    }

                    for (var i = 0; i < response.greenDates.length; i++) {
                        greenDates.push(response.greenDates[i]);
                    }

                    for (var i = 0; i < response.orangeDates.length; i++) {
                        orangeDates.push(response.orangeDates[i]);
                    }

                    for (var i = 0; i < response.redDates.length; i++) {
                        redDates.push(response.redDates[i]);
                    }

                    for (var i = 0; i < response.not_season_time.length; i++) {
                        not_season_time.push(response.not_season_time[i]);
                    }

                    $this.datepicker("refresh");
                }
            });
        });

        $this.datepicker("option", "beforeShowDay", function(date) {
            var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
            if( greenDates.indexOf(string) >=0 ) {
                returnResults = [true, "greenDates", "Verfügbar"];
            }
            if( orangeDates.indexOf(string) >=0 ) {
                returnResults = [true, "orangeDates", "Begrenzt"];
            }
            if( redDates.indexOf(string) >=0 ) {
                returnResults = [true, "redDates", "Ausgebucht"];
            }
            if( holidayDates.indexOf(string) >=0 ) {
                returnResults = [true, "holidayDates", "Ruhetag"];
            }
            if( not_season_time.indexOf(string) >=0 ) {
                returnResults = [false, "", "Geschlossen"];
            }
            return returnResults;
        });

        $this.datepicker("show");

    });
});
/* Checking for the CSRF token */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

//  New booking functionality
$('#newBooking').click(function () {
    //  ovelayLoading('add', divId); //adding loading effect
    if (checkIndivTourNumDup() != false) {
        var divId = 'tourbox';
        var url = '/mountainschool/bookingStore';
        var $btn = $(this);
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize() + '&' + $.param({'formPart': $btn.val()}),
            dataType: 'JSON',
            success: function (data) {
                //    ovelayLoading('remove');//remove loading effect
                $btn.button('reset');
                if ((data.errors)) {
                    $.each(data.errors, function (i, item) {

                        $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                        $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                        $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                        $("input[name='" + i + "']").parent('.form-group').addClass('has-error');

                    });

                } else {
                    //data = JSON.parse(data);
                    //  console.log(data.failureMsg);
                    if (data.successMsg !== undefined) {
                        var msgClass = 'alert-success';
                        var msgText = data.successMsg;
                    }
                    else {
                        var msgClass = 'alert-danger';
                        var msgText = data.failureMsg;
                    }

                    $(window).scrollTop(10);
                    var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';
                    $("#tour_name").trigger("change");

                    $(msg).prependTo('.tourBookingFrm').fadeIn(100);
                    setTimeout(function () {
                        $('.tourBookingFrm #flash').fadeOut()
                    }, 2000);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                // ovelayLoading('remove');//remove loading effect
                if (jqXHR.status == 422) {
                    var errData = jqXHR.responseJSON;
                    $.each(errData, function (i, item) {
                        var spliKey = i.split('.');
                        var fname = spliKey[0];
                        $('input[name^="' + fname + '" ]').each(function (k, v) {
                            if (spliKey[1] == k) {
                                $(this).parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $(this).parent('.form-group').addClass('has-error');
                            }
                        });
                        $('select[name^="' + fname + '" ]').each(function (k, v) {
                            if (spliKey[1] == k) {
                                $(this).parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $(this).parent('.form-group').addClass('has-error');
                            }
                        });
                    });
                    $btn.button('reset');
                }
            }
        });
    }
});

// Select tour name
$('#tour_name').change(function () {
    ovelayLoading('add', 'tourbox');
    var tourId = $('#tour_name').val();
    $.ajax({
        type: "GET",
        url: '/mountainschool/tours/gettour/'+tourId,
        success: function (data) {
            ovelayLoading('remove');
            $('#cabindtls').html(data);
        }
    });
});

// Overlay after submit
function ovelayLoading(arg, appendDiv) {
    if (arg == 'add') {
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo('#' + appendDiv);
    }
    if (arg == 'remove') {
        $("#overlay").remove();
    }
}