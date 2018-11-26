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
$(function(){
    /* Checking for the CSRF token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Create Mountain School Booking
    $('#newBooking').on('click', function() {
        var divId = 'tourbox';
        var url   = '/mountainschool/bookings/store';
        var $btn  = $(this).button('loading');
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize() + '&' + $.param({'formPart': $btn.val()}),
            dataType: 'JSON'
        })
            .done(function (data) {
                $btn.button('reset');
                if (data.response === 'success') {
                    window.location.href = '/mountainschool/bookings';
                }
            })
            .fail(function(data, jqxhr, textStatus, error) {

                if( data.status === 422 ) {
                    var response     = JSON.parse(data.responseText);
                    var bookingOrder = data.responseJSON.bookingOrder;
                    if(response.error) {
                        $( "#errors_"+bookingOrder ).show();

                        errorsHtml = '<div class="alert alert-danger"><ul>';
                        errorsHtml += '<li>' + response.error + '</li>';
                        errorsHtml += '</ul></div>';

                        $( "#errors_"+bookingOrder ).html( errorsHtml );

                        $btn.button('reset');
                    } else {
                        $( "#errors_"+bookingOrder ).hide();
                        var errData = data.responseJSON;

                        $.each(errData, function (i, item) {
                            var spliKey = i.split('.');
                            var fname   = spliKey[0];
                            $('input[name^="' + fname + '" ]').each(function (k, v) {
                                if (spliKey[1] == k) {
                                    $(this).parent('.form-group').children('.help-block').html('<strong>' + item[0] + '</strong>');
                                    $(this).parent('.form-group').addClass('has-error');
                                }
                            });
                            $('select[name^="' + fname + '" ]').each(function (k, v) {
                                if (spliKey[1] == k) {
                                    $(this).parent('.form-group').children('.help-block').html('<strong>' + item[0] + '</strong>');
                                    $(this).parent('.form-group').addClass('has-error');
                                }
                            });
                        });

                        $btn.button('reset');
                    }
                }

                if( data.status === 423) {
                    $(".error").hide();
                    var response     = JSON.parse(data.responseText);
                    var bookingOrder = data.responseJSON.bookingOrder;
                    var tourNumber   = data.responseJSON.tourNumber;

                    $( "#errors_" + bookingOrder + '_' + tourNumber ).show();

                    errorsHtml = '<div class="alert alert-danger"><ul>';
                    errorsHtml += '<li>' + response.error + '</li>';
                    errorsHtml += '</ul></div>';

                    $( "#errors_" + bookingOrder + '_' + tourNumber ).html( errorsHtml );

                    $btn.button('reset');
                }
            });
    });

    var tour_index = 0;

    // Choose tour name
    $('#tourname').change(function () {
        var tourId = $('#tourname').val();

        if(tourId != '') {
            ovelayLoading('add', 'tourbox');
            $.ajax({
                type: "GET",
                url: '/mountainschool/tours/gettour/'+tourId+'/index/'+tour_index,
                success: function (data) {
                    ovelayLoading('remove');
                    $('#cabindtls').append(data);
                    $('#new_tour').html('<i class="fa fa-plus-circle fa-2x icon-primary" id="add_tour" data-tour="' + tourId + '"' + '></i>');
                    $('#newBooking').show();
                }
            });
        }
    });

    // Duplicate selected tour
    $(document).on('click', '#add_tour', function(e) {
        var tourId = $('#add_tour').data('tour');

        getTour(tourId);
        updateTours(tourId);
    });

    // Remove tour from list
    $(document).on('click', '#remove', function(e) {
        var tourId = $('#add_tour').data('tour');
        tour_index--;
        $(this).closest('.col-md-12').remove();
        updateTours(tourId);
    });

    function getTour(tourId) {
        ovelayLoading('add', 'tourbox');
        tour_index++;
        $.ajax({
            type: "GET",
            url: '/mountainschool/tours/gettour/'+tourId+'/index/'+tour_index,
            success: function (data) {
                ovelayLoading('remove');
                $('#cabindtls').append(data);
                $('#newBooking').show();
            }
        });
    }

    function updateTours(tourId) {
        var cabins = $('.tour-box').data('cabins');

        $('.checkInCls').each(function(index) {
            for (var i = cabins.length - 1; i >= 0; i--) {
                $(this).attr('id', 'check_in[' + index+ '][' + cabins[i] + ']');
                $(this).attr('name', 'check_in[' + index + '][' + cabins[i] + ']');
            }
        });
    }

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


});