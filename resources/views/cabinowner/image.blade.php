@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Images')

@section('css')
    <style type="text/css">
        .nounderline {
            text-decoration: none;
            cursor: pointer;
        }

        .required {
            color: red;
        }

        .bg-primary {
            color: #fff;
            background-color: #337ab7;
            text-align: center;
            width: 202px;
            margin-top: -1%;
            padding: 1% 0;
            display: inline-block;
            margin-bottom: 0px;
        }

        .bg-info {
            text-align: center;
            width: 69%;
            margin-left: 10%;
            margin-top: 3%;
            padding: 5px 0 !important;
            float: left;
            margin-right: 2px;
        }

        .btn.btn-success.set_mainimg, .btn.btn-success.set_profileimg {
            width: 45%;
            margin-top: -1%;
            padding: 1% 0;
            display: inline-block;
        }

        .img_button {
            margin-top: 1%;
            padding: 1% 3%;
            display: inline-block;
        }

        .thumbnail {
            text-align: center;
        }
        #imgDiv{
            padding:5px;
        }
    </style>
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
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('image.dashboard')</a></li>
                <li><i class="fa fa-edit"></i> @lang('image.breadcrumbOne')</li>
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
                            <a href="/cabinowner/image/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i> @lang('image.uploadNewImageButton')</a>
                        </div>
                        <div class="responseMessage">
                            @if (session('imagesSuccessStatus'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h4><i class="icon fa fa-check"></i> {{ session('imagesSuccessStatus') }} </h4>
                                </div>
                            @endif
                        </div>

                        <div id="imgDiv" class="row">
                            @if(empty($images ))
                                <p class="bg-info">@lang('image.noImage')</p>
                            @else
                                @foreach ($images as $image)
                                    <div class="col-md-4" id="{{$image['filename']}}">
                                        <a class="thumbnail">
                                            <img src="{{str_replace('public', '/storage', $image['dirname'].'/'.$image['basename'])}}" alt="Generic placeholder thumbnail">
                                            @if(strpos($image['basename'],'main_') !== false)
                                                <p class="bg-primary">@lang('image.mainImg')</p>
                                            @elseif(strpos($image['basename'],'profile_') !== false)
                                                <p class="bg-primary">@lang('image.profileImg')</p>
                                            @else
                                                <button value="{{$image['basename']}}" type="button" class="btn btn-success set_mainimg">@lang('image.uploadSetmageButton')</button>
                                                <button value="{{$image['basename']}}" type="button" class="btn btn-success set_profileimg">@lang('image.uploadSetProfileButton')</button>
                                            @endif
                                            <button class="img_button" type="submit" value="{{$image['basename']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!-- /.box-header -->
                        <!-- /.box -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <!-- /.content-wrapper -->
    <script src="{{ asset('js/image.js') }}"></script>
    <!-- Helping object for translation -->
    <script>
        window.translations = {
            deleteImage: '{{ trans('image.confirmDeleteImage') }}'
        };
    </script>
@endsection
