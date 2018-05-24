@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Basic Settings')


@section('css')
    <style type="text/css">    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('tours.basicSetHeading')
                <small>@lang('tours.basicSetsmallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a></li>             
                <li class="fa fa-edit active">@lang('tours.breadcrumbbasicSet')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                @lang('tours.basicSetBoxHeading')
                            </h4>
                        </div>

                        @if (session('failure'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('failure') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- /.box-header -->


                            <form role="form" method="post" action="{{ route('mountainschool.updatebasicsettings') }}"  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('no_guides') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblNoGuides') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="no_guides" name="no_guides" placeholder="@lang('tours.lblNoGuidesPH')" value="{{old('no_guides',$basicsettings->no_guides )}}" maxlength="100">

                                                @if ($errors->has('no_guides'))
                                                    <span class="help-block"><strong>{{ $errors->first('no_guides') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('contact_person') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblContactPerson')<span class="required">*</span></label>

                                                <input type="text" class="form-control" id="contact_person" name="contact_person" placeholder="@lang('tours.lblContactPersonPH')" value="{{old('contact_person' ,$basicsettings->contact_person)}}" maxlength="25">

                                                @if ($errors->has('contact_person'))
                                                    <span class="help-block"><strong>{{ $errors->first('contact_person') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('notice') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblnotice')<span class="required">*</span></label>

                                                <input type="text" class="form-control" id="notice" name="notice" placeholder="@lang('tours.lblnoticePH')" value="{{old('notice' , $basicsettings->notice  )}}" maxlength="25">

                                                @if ($errors->has('notice'))
                                                    <span class="help-block"><strong>{{ $errors->first('notice') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                         <div class="form-group">
                                            <label>@lang('cabins.lblHalfboard')   </label>
                                            <div class=" checkbox">
                                                <label>
                                                    <input type="checkbox" id="half_board" class="halfboardCls"
                                                           name="half_board"
                                                           {{( old('half_board' , $basicsettings->half_board ) == '1'  )? 'checked="checked"' :'' }} value="1">
                                                    @lang('cabins.half_board_available')</label></div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary  " name="updateBasicSettings" value="updateBasicSettings"><i class="fa fa-fw fa-save"></i>@lang('tours.frmUpdateBtn')</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>

                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection