/**
 * Created by Divya on 07-11-2017.
 */
$(function(){
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
/* Delete Image  */
$('.img_button').on('click', function(e){
    e.preventDefault();
    var imgname  = $(this).val();
    var r = confirm('Delete?');
    if (r == true) {
        $.ajax({
            url: '/cabinowner/image/delete',
            data: { imagename: imgname },
            dataType: 'JSON',
            type: 'POST'
        })
            .done(function( response ) {
                if(response.imgDeleteStatus == 'success') {


                    $('#imgDiv').html(response.images);
                    $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+response.message+' </div>');
                    $('.responseMessage').show().delay(5000).fadeOut();
                }
            })
            .fail(function() {

            });
    }

});
    /* Setting main image */
    $('body').on('click','.set_mainimg',function(e){
        e.preventDefault();
        var imgname  = $(this).val();

            $.ajax({
                url: '/cabinowner/image/setMainImg',
                data: { imagename: imgname },
                dataType: 'JSON',
                type: 'POST'
            })
                .done(function( response ) {
                    if(response.imgsetMainStatus == 'success') {

                        $('#imgDiv').html(response.images);
                        $('.responseMessage').html('<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> '+response.message+' </div>');
                        $('.responseMessage').show().delay(5000).fadeOut();
                    }
                })
                .fail(function() {

                });


    });

        // create image functioans
        $(".export").prop('disabled', true);

        function neueBildBearbeitung() {

            var dkrm = new Darkroom('#target', {
                // Size options
                minWidth: 100,
                minHeight: 100,
                maxWidth: 1980,
                maxHeight: 720,
                ratio: 1980 / 720,
                backgroundColor: '#fff',
                // Plugins options
                plugins: {
                    //save: false,
                    crop: {
                        quickCropKey: 67, //key "c"
                        //minHeight: 50,
                        //minWidth: 50,
                        ratio: 1980 / 720
                    },
                    //Was nach dem Speichern passieren soll.
                    save: {
                        callback: function () {
                            if (1 != 1) {

                            } else {
                                this.darkroom.selfDestroy(); // Turn off the bar and cleanup
                                var imageData = dkrm.canvas.toDataURL({
                                    format: 'jpeg',
                                    quality: 0.8
                                });
                                //Timeout um dem Dateileser Zeit zu geben
                                window.setTimeout(function () {
                                    $("#hidden_base64Logo").val(imageData);
                                    $(".export").prop('disabled', false);

                                }, 1000);

                            }
                        }
                    }
                },

                // Post initialize script
                initialize: function () {
                    var cropPlugin = this.plugins['crop'];
                    cropPlugin.requireFocus();
                }
            });
        }

        document.getElementById('logoUpload').onchange = function (evt) {

            if (typeof dkrm === 'undefined' || variable === null) {
            } else {
                dkrm.selfDestroy();
            }

            var tgt = evt.target || window.event.srcElement,
                files = tgt.files;
            // FileReader support
            if (FileReader && files && files.length) {
                var fr = new FileReader();
                fr.onload = function () {
                    document.getElementById('target').src = fr.result;
                }
                fr.readAsDataURL(files[0]);
                //Timeout um dem Dateileser Zeit zu geben.
                window.setTimeout(function () {
                    neueBildBearbeitung();
                }, 1000);

            }

        }
        $(".export").click(function (e) {
            document.imageupload.submit();
        })

    });
