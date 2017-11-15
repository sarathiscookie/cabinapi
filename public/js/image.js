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
    /* Helping object for translation */
    var translations = {
        deleteImage: window.translations.deleteImage,

    };
    /* Delete Image  */
    $('body').on('click', '.img_button', function (e) {
        e.preventDefault();
        var imgname = $(this).val();
        var r = confirm(translations.deleteImage);
        if (r == true) {
            $.ajax({
                url: '/cabinowner/image/delete',
                data: {imagename: imgname},
                dataType: 'JSON',
                type: 'POST'
            })
                .done(function (response) {
                    if (response.imgDeleteStatus == 'success') {


                        $('#imgDiv').html(response.images);
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> ' + response.message + ' </div>');
                        $('.responseMessage').show().delay(5000).fadeOut();
                    }
                })
                .fail(function () {

                });
        }

    });
    /* Setting main image */
    $('body').on('click', '.set_mainimg', function (e) {
        e.preventDefault();
        var imgname = $(this).val();

        $.ajax({
            url: '/cabinowner/image/setMainImg',
            data: {imagename: imgname},
            dataType: 'JSON',
            type: 'POST'
        })
            .done(function (response) {
                if (response.imgsetMainStatus == 'success') {

                    $('#imgDiv').html(response.images);
                    $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> ' + response.message + ' </div>');
                    $('.responseMessage').show().delay(5000).fadeOut();
                }
            })
            .fail(function () {

            });


    });
    /****set profile Image ******/
    $('body').on('click', '.set_profileimg', function (e) {
        e.preventDefault();
        var imgname = $(this).val();

        $.ajax({
            url: '/cabinowner/image/setProfileImg',
            data: {imagename: imgname},
            dataType: 'JSON',
            type: 'POST'
        })
            .done(function (response) {
                if (response.imgsetMainStatus == 'success') {

                    $('#imgDiv').html(response.images);
                    $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> ' + response.message + ' </div>');
                    $('.responseMessage').show().delay(5000).fadeOut();
                }
            })
            .fail(function () {

            });


    });

});
