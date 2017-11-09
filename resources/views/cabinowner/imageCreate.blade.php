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

@section('scripts')
    <script src="{{ asset('js/fabric.js') }}"></script>
    <script src="{{ asset('js/darkroom.js') }}"></script>
    <script src="{{ asset('js/image.js') }}"></script>
@endsection

@section('content')
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


@endsection