$(function(){
    /* Checking for the CSRF token */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Create Mountain School Booking
    $('#newBooking').on('click', function() {
        var divId    = 'tourbox';
        var url      = '/mountainschool/bookingStore';
        var $btn     = $(this).button('loading');
        $('#' + divId).find('.has-error').removeClass('has-error');
        $('#' + divId).find('.help-block').html('<strong></strong>');
        $btn.button('loading');
        $.ajax({
            type: "POST",
            url: url,
            data: $("form").serialize() + '&' + $.param({'formPart': $btn.val()}),
            dataType: 'JSON',
            success: function (data) {
                $btn.button('reset');
                if (data.successMsg !== undefined) {
                    var msgClass = 'alert-success';
                    var msgText  = data.successMsg;
                    window.location.href = '/mountainschool/bookings';
                }
                else {
                    var msgClass = 'alert-danger';
                    var msgText = data.failureMsg;
                }

                $(window).scrollTop(10);

                var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                $("#tour_name").trigger("change");

                $(msg).prependTo('#tourBookingFrm').fadeIn(100);

                setTimeout(function () {
                    $('#tourBookingFrm #flash').fadeOut()
                }, 2000);
            },
            error: function (data, textStatus, errorThrown) { // What to do if we fail
                if (data.status == 422) {
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
        });
    });

    // Choose tour name
    $('#tour_name').change(function () {
        ovelayLoading('add', 'tourbox');
        var tourId = $('#tour_name').val();
        $.ajax({
            type: "GET",
            url: '/mountainschool/tours/gettour/'+tourId,
            success: function (data) {
                ovelayLoading('remove');
                $('#cabindtls').html(data);
                $('#newBooking').show();
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
});