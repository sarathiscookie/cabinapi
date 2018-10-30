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