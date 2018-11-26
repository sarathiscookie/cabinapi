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
                updateTours(tourId);
            }
        });
    }

    function updateTours(tourId) {

        $.each( $('.tour-box'), function(i, tour_box) {
           $('.checkInCls', tour_box).each(function(index) {
                $(this).attr('id', 'check_in' + i + (index + 1));
                $(this).attr('name', 'check_in[' + i + '][' + (index + 1) + ']');
           });
        })

        $.each( $('.tour-box'), function(i, tour_box) {
           $('.checkOutCls', tour_box).each(function(index) {
                $(this).attr('id', 'check_out' + i + (index + 1));
                $(this).attr('name', 'check_out[' + i + '][' + (index + 1) + ']');
           });
        })
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