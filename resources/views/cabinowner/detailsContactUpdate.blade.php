@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Contact Information Edit')

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
                <li class="fa fa-edit active">@lang('details.breadcrumbThree')</li>
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
                                @lang('details.boxHeading')
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
                        <!-- /.box-header -->

                        @isset($userDetails)
                            <form role="form" method="post" action="{{ route('cabinowner.details.contact.update') }}">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelFirstName') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="@lang('details.contactLabelFirstNamePH')" value="{{old('firstname', $userDetails->usrFirstname)}}" maxlength="100">

                                                @if ($errors->has('firstname'))
                                                    <span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelLastName') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="@lang('details.contactLabelLastNamePH')" value="{{old('lastname', $userDetails->usrLastname)}}" maxlength="100">

                                                @if ($errors->has('lastname'))
                                                    <span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelMobile')</label>

                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="@lang('details.contactLabelMobilePH')" value="{{old('mobile', $userDetails->usrMobile)}}" maxlength="25">

                                                @if ($errors->has('mobile'))
                                                    <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelPhone') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="@lang('details.contactLabelPhonePH')" value="{{old('telephone', $userDetails->usrTelephone)}}" maxlength="25">

                                                @if ($errors->has('telephone'))
                                                    <span class="help-block"><strong>{{ $errors->first('telephone') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelZip') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="@lang('details.contactLabelZipPH')" value="{{old('zip', $userDetails->usrZip)}}" maxlength="25">

                                                @if ($errors->has('zip'))
                                                    <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelCity') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="city" name="city" placeholder="@lang('details.contactLabelCityPH')" value="{{old('city', $userDetails->usrCity)}}" maxlength="255">

                                                @if ($errors->has('city'))
                                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelStreet') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="street" name="street" placeholder="@lang('details.contactLabelStreetPH')" value="{{old('street', $userDetails->usrAddress)}}" maxlength="255">

                                                @if ($errors->has('street'))
                                                    <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                                                <label>@lang('details.contactLabelCountry') <span class="required">*</span></label>

                                                <select class="form-control select2" id="country" name="country">
                                                    <option value=""> @lang('details.contactLabelCountryPH') </option>
                                                    @if(isset($country))
                                                        @foreach($country as $land)
                                                            <option value="{{$land->name}}" @if(old('country') === $land->name || $userDetails->usrCountry === $land->name) selected="selected" @endif> {{$land->name}} </option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                                @if ($errors->has('country'))
                                                    <span class="help-block"><strong>{{ $errors->first('country') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary pull-right" name="updateContact" value="updateContact"><i class="fa fa-fw fa-save"></i>@lang('details.formUpdateButton')</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        @endisset
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection