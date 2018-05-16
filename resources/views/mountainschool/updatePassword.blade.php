@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Update Password')


@section('css')
    <style type="text/css">    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('tours.pwdheading')
                <small>@lang('tours.pwdsmallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a></li>             
                <li class="fa fa-edit active">@lang('tours.breadcrumbpwd')</li>
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
                                @lang('tours.pwdBoxHeading')
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


                            <form role="form" method="post" action="{{ route('mountainschool.mydata.updatepassword') }}"  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('current_pwd') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblCurrentPwd') <span class="required">*</span></label>

                                                <input type="password" class="form-control" id="current_pwd" name="current_pwd" placeholder="@lang('tours.lblCurrentPwdPH')" value="{{old('current_pwd' )}}" maxlength="100">

                                                @if ($errors->has('current_pwd'))
                                                    <span class="help-block"><strong>{{ $errors->first('current_pwd') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('new_pwd') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblNewPwd')<span class="required">*</span></label>

                                                <input type="password" class="form-control" id="new_pwd" name="new_pwd" placeholder="@lang('tours.lblNewPwdPH')" value="{{old('new_pwd' )}}" maxlength="25">

                                                @if ($errors->has('new_pwd'))
                                                    <span class="help-block"><strong>{{ $errors->first('new_pwd') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('con_pwd') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblPwdConfirm')<span class="required">*</span></label>

                                                <input type="password" class="form-control" id="con_pwd" name="con_pwd" placeholder="@lang('tours.lblPwdConfirmPH')" value="{{old('con_pwd' )}}" maxlength="25">

                                                @if ($errors->has('con_pwd'))
                                                    <span class="help-block"><strong>{{ $errors->first('con_pwd') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>


                                    </div>









                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary  " name="updatePwd" value="updatePwd"><i class="fa fa-fw fa-save"></i>@lang('tours.frmUpdateBtn')</button>
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