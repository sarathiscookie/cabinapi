@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Upload Image')

@section('css')
    <style type="text/css">
        .nounderline {
            text-decoration: none;
            cursor: pointer;
        }

        .required {
            color: red;
        }
    </style>
    <link rel="stylesheet" href="/css/darkroom.css">
    <link rel="stylesheet" href="/css/cropper.css">
@endsection


@section('content')
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

    <script type='text/javascript' src="{{ URL::asset('/js/fabric.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('/js/darkroom.js') }}"></script>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('image.imagePageHeading')
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li><i class="fa fa-edit"></i> @lang('openingClosingSeason.breadcrumbOne')</li>
                <li class="active">@lang('image.imagePageHeading')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('image.images')
                            </h3>

                        </div>

                        <!-- image upload part -->

                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="panel panel-default">
                                    <div class="panel-heading"> @lang('image.uploadNewImageButton')</div>
                                    <div class="panel-body">
                                        <form class="form-horizontal" role="form" name="imageupload" method="POST"
                                              action="{!! url('/cabinowner/image/store') !!}" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('sign') ? ' has-error' : '' }}">
                                                <label for="signUpload" class="col-md-4 control-label upl">@lang('image.imageUploadPreview')</label>
                                                <div class="col-md-6" id="image-cropper">

                                                    <img src="" id="target">
                                                    <!-- The actual file input will be hidden -->
                                                    <div class="col-md-6">
                                                        <input type="file" name="logoUpload" id="logoUpload" accept="image/png|image/jpeg"
                                                               class="cropit-image-input" multiple="true"/>

                                                    </div>
                                                </div>
                                                <label class="data-url"></label>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6  col-md-offset-4">
                                                    <input type="hidden" id="hidden_base64Logo" name="hidden_base64Logo"/>
                                                    <button class="btn btn-primary export">Speichern</button>
                                                    <button class="btn btn-primary abbrechen" onclick="location.href='/account';">
                                                        Abbrechen
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
<!--image upload end -->
                </div>

                </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <script>

        $(function () {

            $(".export").prop('disabled', true);

            function neueBildBearbeitung() {

                dkrm = new Darkroom('#target', {
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


    </script>

@endsection