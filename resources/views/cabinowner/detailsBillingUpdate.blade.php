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
                <li class="fa fa-edit active">@lang('details.breadcrumbBilling')</li>
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
                                @lang('details.billingHeading')
                            </h4>
                        </div>
                        <!-- /.box-header -->

                        @if (session('failure'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('failure') }}
                            </div>
                        @endif

                        @isset($cabin)
                            <form role="form" method="post" action="{{ route('cabinowner.details.billing.update') }}">
                                {{ csrf_field() }}

                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('company') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelCompanyName') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="company" name="company" placeholder="@lang('details.billingLabelCompanyNamePH')" @if(isset($userCompany)) value="{{old('company', $userCompany->company)}}" @else value="{{old('company')}}" @endif maxlength="255">

                                                @if ($errors->has('company'))
                                                    <span class="help-block"><strong>{{ $errors->first('company') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelZip') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="@lang('details.billingLabelZipPH')" value="{{old('zip', $cabin->zip)}}" maxlength="25">

                                                @if ($errors->has('zip'))
                                                    <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelPlace') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="city" name="city" placeholder="@lang('details.billingLabelPlacePH')" value="{{old('city', $cabin->place)}}" maxlength="255">

                                                @if ($errors->has('city'))
                                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelStreet') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="street" name="street" placeholder="@lang('details.billingLabelStreetPH')" value="{{old('street', $cabin->street)}}" maxlength="255">

                                                @if ($errors->has('street'))
                                                    <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('legal') ? ' has-error' : '' }}">
                                                <label>Legal <span class="required">*</span></label>

                                                <select class="form-control" name="legal" id="legal">
                                                    <option value="0">@lang('details.legalSelectLabel')</option>
                                                    <option value="gmbh" @if($cabin->legal == 'gmbh' || old('legal') == 'gmbh') selected="selected" @endif>@lang('details.legalgmbh')</option>
                                                    <option value="gbr" @if($cabin->legal == 'gbr' || old('legal') == 'gbr') selected="selected" @endif>@lang('details.legalgbr')</option>
                                                    <option value="kg" @if($cabin->legal == 'kg' || old('legal') == 'kg') selected="selected" @endif>@lang('details.legalkg')</option>
                                                    <option value="ohg" @if($cabin->legal == 'ohg' || old('summerSeason') == 'ohg') selected="selected" @endif>@lang('details.legalohg')</option>
                                                    <option value="single" @if($cabin->legal == 'single' || old('legal') == 'single') selected="selected" @endif>@lang('details.legalsingle')</option>
                                                </select>

                                                @if ($errors->has('legal'))
                                                    <span class="help-block"><strong>{{ $errors->first('legal') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group {{ $errors->has('vat') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelVat') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="vat" name="vat" placeholder="@lang('details.billingLabelVatPH')" value="{{old('vat', $cabin->vat)}}" maxlength="100">

                                                @if ($errors->has('vat'))
                                                    <span class="help-block"><strong>{{ $errors->first('vat') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group {{ $errors->has('fax') ? ' has-error' : '' }}">
                                                <label>@lang('details.billingLabelFax') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="fax" name="fax" placeholder="@lang('details.billingLabelFaxPH')" value="{{old('fax', $cabin->fax)}}" maxlength="50">

                                                @if ($errors->has('fax'))
                                                    <span class="help-block"><strong>{{ $errors->first('fax') }}</strong></span>
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
                        @endisset
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection