@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Billing Information Edit')

@section('css')
    <style type="text/css">
        .required{
            color:red;
        }
    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('details.heading')
                <small>@lang('details.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('details.breadcrumbOne')</a></li>
                <li><a href="/cabinowner/details"><i class="fa fa fa-table"></i> @lang('details.breadcrumbTwo')</a></li>
                <li class="fa fa-edit active">Billing</li>
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
                                Information
                            </h4>
                        </div>
                        <!-- /.box-header -->

                        @isset($cabin)
                            <div class="box-body">
                                <div class="row">
                                    <!-- Div for summer season -->
                                    <div class="col-md-12">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h4>Billing</h4>
                                            </div>

                                            @if (session('failure'))
                                                <div class="alert alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('failure') }}
                                                </div>
                                            @endif

                                            <form role="form" method="post" action="{{ route('cabinowner.details.billing.update') }}">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('legal') ? ' has-error' : '' }}">
                                                                <label>Legal <span class="required">*</span></label>

                                                                <select class="form-control" name="legal" id="legal">
                                                                    <option value="0">Select legal forms</option>
                                                                    <option value="gmbh" @if($cabin->legal == 'gmbh' || old('legal') == 'gmbh') selected="selected" @endif>GmbH</option>
                                                                    <option value="gbr" @if($cabin->legal == 'gbr' || old('legal') == 'gbr') selected="selected" @endif>GbR</option>
                                                                    <option value="kg" @if($cabin->legal == 'kg' || old('legal') == 'kg') selected="selected" @endif>KG</option>
                                                                    <option value="ohg" @if($cabin->legal == 'ohg' || old('summerSeason') == 'ohg') selected="selected" @endif>OHGlegal
                                                                    <option value="single" @if($cabin->legal == 'single' || old('legal') == 'single') selected="selected" @endif>One Man Business</option>
                                                                </select>

                                                                @if ($errors->has('legal'))
                                                                    <span class="help-block"><strong>{{ $errors->first('legal') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('tax') ? ' has-error' : '' }}">
                                                                <label>Tax <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="tax" name="tax" placeholder="Tax" value="{{old('tax', $cabin->tax)}}" maxlength="100">

                                                                @if ($errors->has('tax'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tax') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                                                                <label>Phone <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Phone" value="{{old('telephone', $cabin->telephone)}}" maxlength="25">

                                                                @if ($errors->has('telephone'))
                                                                    <span class="help-block"><strong>{{ $errors->first('telephone') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                                                <label>Zip <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip" value="{{old('zip', $cabin->zip)}}" maxlength="25">

                                                                @if ($errors->has('zip'))
                                                                    <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                                                <label>City <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{old('city', $cabin->place)}}" maxlength="255">

                                                                @if ($errors->has('city'))
                                                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                                                <label>Street <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="street" name="street" placeholder="Street" value="{{old('street', $cabin->street)}}" maxlength="255">

                                                                @if ($errors->has('street'))
                                                                    <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->

                                                <div class="box-footer">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary pull-right" name="updateBilling" value="updateBilling"><i class="fa fa-fw fa-save"></i>@lang('details.formUpdateButton')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-footer -->

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        @endisset
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection