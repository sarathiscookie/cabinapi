@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Edit Cabin')

@section('css')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}"/>

    <style type="text/css">
        .required {
            color: red;
        }

        select option:disabled {
            color: #222D32;
            background-color: #ECF0F5;
            font-style: oblique !important;
        }

        #field {
            margin-bottom: 20px;
        }

        .addNeighbourlbl {
            position: relative;
            bottom: -10px;
            color: #9FC436;
        }

        .remove-me {
            margin-top: 29px !important;
            margin-left: -20px !important;
        }

        @media only screen and (max-width: 1000px) {
            .remove-me {
                margin-top: 2px !important;
                margin-left: -2px !important;
            }
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            filter: alpha(opacity=70);
            -moz-opacity: 0.5;
            -khtml-opacity: 0.5;
            opacity: 0.5;
            z-index: 10000;
            background-color: #ECF0F5;
/*
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background: url('/img/loading.gif') center center no-repeat;
            */

        }

        .add-more-dynamic,.addNeighbourlbl{
            cursor: pointer;
        }

    </style>
@endsection
@inject('cabinInfo', 'App\Http\Controllers\Cabinowner\DetailsController')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('cabins.crtHeading')
                <small>@lang('cabins.uptHeadingSub')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('cabins.breadcrumbOne')</a></li>
                <li><a href="/admin/cabinlite"><i class="fa fa fa-table"></i> @lang('cabins.breadcrumbTwo')</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="false">
                        <i class="fa fa-home fa-fw" aria-hidden="true"></i>{{$cabin->name}} <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a tabindex="-1" href="#">{{$cabin->name}} </a></li>
                        <li class="divider"></li>
                        <li><li class="fa fa-edit" style="margin: 8px "></li><a tabindex="-1" href="/admin/cabinlite/contingent/{{$cabin->_id}}">@lang('cabins.menuContigent')</a></li>
                        <li><li class="fa fa-edit" style="margin: 8px "></li><a tabindex="-1" href="/admin/cabinlite/seasondetails/{{$cabin->_id}}">@lang('cabins.menuSeason')</a></li>
                        <li><li class="fa fa-edit" style="margin: 8px "></li><a tabindex="-1" href="/admin/cabinlite/image/{{$cabin->_id}}">@lang('cabins.menuImages')</a></li>
                    </ul>
                </li>
                <li class="fa fa-edit active">@lang('cabins.breadcrumbEdit')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- *************************************** -->
            <form role="form" method="post" action="{{ route('admin.cabinlite.updatecontingent')  }}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-4">
                        <div class="box  box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('cabins.contactInfo')</h4>
                            </div>
                            <div class="box-body" id="contactInfoBox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblFirstName') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="firstname" name="firstname"
                                                   placeholder="@lang('cabins.lblFirstNamePH')"
                                                   value="{{old('firstname', $userDetails->usrFirstname)}}"
                                                   maxlength="100">
                                                <span class="help-block"><strong></strong></span>

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblLastName') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="lastname" name="lastname"
                                                   placeholder="@lang('cabins.lblLastNamePH')"
                                                   value="{{old('lastname', $userDetails->usrLastname)}}"
                                                   maxlength="100">
                                                <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblMobile')</label>

                                            <input type="text" class="form-control" id="mobile" name="mobile"
                                                   placeholder="@lang('cabins.lblMobilePH')"
                                                   value="{{old('mobile', $userDetails->usrMobile)}}" maxlength="25">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblPhone') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="telephone" name="telephone"
                                                   placeholder="@lang('cabins.lblPhonePH')"
                                                   value="{{old('telephone', $userDetails->usrTelephone)}}"
                                                   maxlength="25">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('usrZip') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblZip') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="usrZip" name="usrZip"
                                                   placeholder="@lang('cabins.lblZipPH')"
                                                   value="{{old('zip', $userDetails->usrZip)}}" maxlength="25">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('usrCity') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCity') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="usrCity" name="usrCity"
                                                   placeholder="@lang('cabins.lblCityPH')"
                                                   value="{{old('usrCity', $userDetails->usrCity)}}" maxlength="255">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('usrAddress') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblStreet') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="usrAddress" name="usrAddress"
                                                   placeholder="@lang('cabins.lblStreetPH')"
                                                   value="{{old('usrAddress', $userDetails->usrAddress)}}" maxlength="255">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('usrCountry') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCountry') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="usrCountry" name="usrCountry"
                                                   placeholder="@lang('cabins.lblCountryPH')"
                                                   value="{{old('usrCountry', $userDetails->usrCountry)}}" maxlength="255">
                                            <span class="help-block"><strong></strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary pull-right" name="updateContactInfo" id="updateContactInfo" data-loading-text="Updating..."
                                                    value="updateContactInfo"><i
                                                        class="fa fa-fw fa-save"></i>@lang('cabins.btnUpdate')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <div class="box  box-primary">
                            <div class="  box-header with-border">
                                <h4 class="box-title"> @lang('cabins.billingInfo')</h4>
                            </div>

                            <!-- box body -->
                            <div class="box-body" id="billingInfoBox">
                                <!---- ---->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group   {{ $errors->has('company') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCompany') <span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" id="company"
                                                   name="company"
                                                   placeholder="@lang('cabins.companyPH')"
                                                   value="{{ old('company' , $userDetails->company) }}"
                                                   maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('company') }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group   {{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingCity')  </label>
                                            <input type="text" class="form-control" id="city"
                                                   name="city"
                                                   placeholder="@lang('cabins.billingcityPH')"
                                                   value="{{ old('city' , $cabin->place) }}"
                                                   maxlength="100">
                                            <span class="help-block"><strong>   {{ $errors->first('city') }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group   {{ $errors->has('street') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingStreet')  </label>
                                            <input type="text" class="form-control" id="street"
                                                   name="street"
                                                   placeholder="@lang('cabins.billingstreetPH')"
                                                   value="{{ old('street' , $cabin->street) }}"
                                                   maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('street') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('zip') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingZip')  </label>
                                            <input type="text" class="form-control" id="zip"
                                                   name="zip"
                                                   placeholder="@lang('cabins.billingZipPH')"
                                                   value="{{ old('zip' , $cabin->zip) }}"
                                                   maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('zip') }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group {{ $errors->has('fax') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingFax')  </label>

                                            <input type="text" class="form-control" id="fax" name="fax"
                                                   placeholder="@lang('cabins.billingFaxPH')"
                                                   value="{{old('fax'  , $cabin->fax )}}" maxlength="50">

                                            @if ($errors->has('fax'))
                                                <span class="help-block"><strong>{{ $errors->first('fax') }}</strong></span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('legal') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingLegal')  </label>

                                            <select class="form-control" name="legal" id="legal">
                                                <option value="0">@lang('cabins.legalSelectLabel')</option>
                                                <option value="gmbh"
                                                        @if( old('legal') == 'gmbh' ||   $cabin->legal == 'gmbh') selected="selected" @endif>@lang('cabins.legalgmbh')</option>
                                                <option value="gbr"
                                                        @if( old('legal') == 'gbr' ||   $cabin->legal == 'gbr') selected="selected" @endif>@lang('cabins.legalgbr')</option>
                                                <option value="kg"
                                                        @if( old('legal') == 'kg' ||   $cabin->legal == 'kg') selected="selected" @endif>@lang('cabins.legalkg')</option>
                                                <option value="ohg"
                                                        @if( old('legal') == 'ohg' ||   $cabin->legal == 'ohg') selected="selected" @endif>@lang('cabins.legalohg')</option>
                                                <option value="single"
                                                        @if( old('legal') == 'single' ||  $cabin->legal == 'single') selected="selected" @endif>@lang('cabins.legalsingle')</option>
                                            </select>

                                            @if ($errors->has('legal'))
                                                <span class="help-block"><strong>{{ $errors->first('legal') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('vat') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBillingVat') </label>

                                            <input type="text" class="form-control" id="vat" name="vat"
                                                   placeholder="@lang('cabins.billingVatPH')"
                                                   value="{{old('vat' , $cabin->vat )}}" maxlength="100">

                                            @if ($errors->has('vat'))
                                                <span class="help-block"><strong>{{ $errors->first('vat') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" id="updateBillingInfo" data-loading-text="Updating..."
                                                    class="btn btn-primary pull-right" name="updateBillingInfo"
                                                    value="updateBillingInfo"><i
                                                        class="fa fa-fw fa-save"></i>@lang('cabins.btnUpdate')</button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- box body -->
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="box  box-primary">
                            <div class="  box-header with-border">
                                <h4 class="box-title"> @lang('cabins.boxHeading')</h4>
                            </div>

                            <!-- box body -->
                            <div class="box-body" id="CabinInfoBox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('cabin_name') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCabinName') <span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" id="cabin_name" readonly="readonly"
                                                   name="cabin_name" value="{{old('cabin_name', $cabin->name)}}"

                                                   placeholder="@lang('cabins.cabinNamePH')" maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('cabin_name') }}</strong></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('cabin_code') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCabinCode') <span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" id="cabin_code"
                                                   name="cabin_code" readonly="readonly"
                                                   value="{{ old('cabin_code', $cabin->invoice_code) }}"
                                                   placeholder="@lang('cabins.cabinCodePH')" maxlength="100">
                                            <span class="help-block"><strong>   {{ $errors->first('cabin_code') }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('club') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblClub')  </label>
                                            <input type="text" class="form-control" id="club" name="club"
                                                   value="{{ old('club',  $cabin->club) }}"
                                                   placeholder="@lang('cabins.clubPH')"
                                                   maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('club') }}</strong></span>
                                        </div>


                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('availability') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblAvailability')  </label>
                                            <input type="text" class="form-control" id="availability"
                                                   name="availability" placeholder="@lang('cabins.availabilityPH')"
                                                   value="{{ old('availability',  $cabin->reachable) }}"
                                                   maxlength="100">
                                            <span class="help-block"><strong>   {{ $errors->first('availability') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('height') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblCabinHeight') <span
                                                                class="required">*</span></label>
                                                    <input type="text" class="form-control" id="height"
                                                           name="height"
                                                           value="{{ old('height', $cabin->height) }}"
                                                           placeholder="@lang('cabins.cabinHeightPH')"
                                                           maxlength="200">
                                                    <span class="help-block"><strong>  {{ $errors->first('height') }}</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('deposit') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblDeposit') <span
                                                                class="required">*</span></label>
                                                    <input type="text" class="form-control" id="deposit"
                                                           name="deposit"
                                                           placeholder="@lang('cabins.DepositPH')"
                                                           value="{{ old('deposit' , $cabin->prepayment_amount) }}"
                                                           maxlength="100">
                                                    <span class="help-block"><strong>  {{ $errors->first('deposit') }}</strong></span>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('check_in') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblCheckIn') <span
                                                                class="required">*</span></label>
                                                    <input type="text" class="form-control" id="check_in"
                                                           name="check_in"
                                                           placeholder="@lang('cabins.CheckInPH')"
                                                           value="{{ old('check_in', $cabin->checkin_from) }}"
                                                           maxlength="100">
                                                    <span class="help-block"><strong>  {{ $errors->first('check_in') }}</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group  {{ $errors->has('check_out') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblCheckOut') <span
                                                                class="required">*</span></label>
                                                    <input type="text" class="form-control" id="check_out"
                                                           name="check_out"
                                                           placeholder="@lang('cabins.CheckOutPH')"
                                                           value="{{ old('check_out', $cabin->reservation_to) }}"
                                                           maxlength="100">
                                                    <span class="help-block"><strong>   {{ $errors->first('check_out') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!------>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('cabin_owner') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblCabinOwner') <span
                                                                class="required">*</span></label>
                                                    <select class="form-control" id="cabin_owner" name="cabin_owner">
                                                        <option value="">-- @lang('cabins.cabinOwnerPH') --</option>
                                                        @foreach($cabinOwnerList as $key =>$owner)

                                                            <option for="{{$owner['company']}}"
                                                                    @if( $cabin->cabin_owner == $key  || old('cabin_owner') == $cabin->cabin_owner ) selected="selected"
                                                                    @endif
                                                                    @if((!empty($owner['isCabinExists']) &&  $cabin->cabin_owner != $key )) disabled="disabled"
                                                                    @endif
                                                                    value="{{$key}}">{{$owner['fname'] . ' ' . $owner['lname'] . ' ('. $owner['usrName'] .')  '  }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"><strong>  {{ $errors->first('cabin_owner') }}</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('website') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblWebsite')  </label>
                                                    <input type="text" class="form-control" id="website" name="website"
                                                           placeholder="@lang('cabins.websitePH')"
                                                           value="{{ old('website', $cabin->website) }}"
                                                           maxlength="100">
                                                    <span class="help-block"><strong>   {{ $errors->first('website') }}</strong></span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('region') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblRegion')  </label>


                                                    <select id="region" name="region" class="form-control region"
                                                            style="width: 100%;">
                                                        <option value="0">@lang('cabins.regionPH')</option>
                                                        @foreach($cabinInfo->regions() as $region)
                                                            <option value="{{ $region->name }}"
                                                                    @if($region->name == $cabin->region || old('region') == $cabin->region) selected="selected" @endif>{{ $region->name }}</option>
                                                        @endforeach
                                                    </select>

                                                    <span class="help-block"><strong>  {{ $errors->first('region') }}</strong></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group   {{ $errors->has('country') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblCountry') <span
                                                                class="required">*</span></label>
                                                    <select class="form-control" id="country" name="country">
                                                        <option value="">-- @lang('cabins.countryPH') --</option>
                                                        @foreach($countrylist as $country)
                                                            <option value="{{ $country->name }}"
                                                                    @if($country->name == old('country') ||  $cabin->country == $country->name) selected="selected" @endif>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block"><strong>   {{ $errors->first('country') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-------->


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('cabins.lbltour')</label>
                                            <input type="text" class="form-control" id="tours" name="tours"
                                                   placeholder="@lang('cabins.tourPH')"
                                                   value="{{old('tours', $cabin->tours )}}" maxlength="200">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>@lang('cabins.lblHalfboard')   </label>
                                                    <div class=" checkbox">
                                                        <label>
                                                            <input type="checkbox" id="halfboard"
                                                                   name="halfboard" {{( old('halfboard') == '1' || $cabin->halfboard =="1" )? 'checked="checked"' :'' }} >
                                                            @lang('cabins.half_board_available')</label></div>
                                                </div>
                                            </div>
                                            <div class="col-md-9" style="display: none" id="divHafPrice">
                                                <div class="form-group   {{ $errors->has('halfboard_price') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblHalfBoardPrice') <span
                                                                class="required">*</span></label>
                                                    <input type="text" class="form-control" id="halfboard_price"
                                                           name="halfboard_price"
                                                           placeholder="@lang('cabins.halfBoardPricePH')"
                                                           value="{{ old('halfboard_price', $cabin->halfboard_price) }}"
                                                           maxlength="100">
                                                    <span class="help-block"><strong>   {{ $errors->first('halfboard_price') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!---- ---->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group   {{ $errors->has('booking_type') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblBookingType') <span
                                                        class="required">*</span></label>
                                            <select class="form-control" id="booking_type" name="booking_type">
                                                <option value="">-- @lang('cabins.bookingTypePH') --</option>
                                                @foreach($cabinType as $key =>$ctype)
                                                    <option
                                                            @if(  old('booking_type') == $key  ||  $cabin->booking_type == $key ) selected="selected"
                                                            @endif
                                                            value="{{$key}}">{{ $ctype  }}
                                                    </option>
                                                @endforeach
                                            </select>


                                            <span class="help-block"><strong>  {{ $errors->first('booking_type') }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('cancel') ? ' has-error' : '' }}">
                                            <label>@lang('cabins.lblCancel')  </label>

                                            <select class="form-control" id="cancel" name="cancel">
                                                @foreach($cabinInfo->reservationCancel() as $key => $type)
                                                    <option value="{{ $key }}"
                                                            @if(  old('cancel') == $key || $cabin->reservation_cancel == $key ) selected="selected" @endif>{{ $type }}</option>
                                                @endforeach
                                            </select>

                                            @if ($errors->has('cancel'))
                                                <span class="help-block"><strong>{{ $errors->first('cancel') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('cabins.lblFacility')</label>
                                            <select id="facility" name="facility[]" class="form-control interior"
                                                    multiple="multiple"
                                                    data-placeholder="@lang('cabins.lblFacilityPH')"
                                                    style="width: 100%;">
                                                @foreach($cabinInfo->interiorLabel() as $interiorLabelKey => $interiorLabel)
                                                    @if(!empty($cabin->interior))
                                                        <option value="{{ $interiorLabelKey }}"
                                                                @if(in_array($interiorLabelKey, $cabin->interior)) selected="selected" @endif>{{ $interiorLabel }}</option>
                                                    @else
                                                        <option value="{{ $interiorLabelKey }}"
                                                                @if( old('facility') != "" && in_array($interiorLabelKey , old('facility'))) selected="selected" @endif>{{ $interiorLabel }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('payment') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblPayType') </label>
                                                    <select id="payment" name="payment[]"
                                                            class="form-control payment"
                                                            multiple="multiple"
                                                            data-placeholder="@lang('cabins.lblPayTypePH')"
                                                            style="width: 100%;">
                                                        @foreach($cabinInfo->paymentType() as $paymentTypeKey => $paymentType)
                                                            @if(!empty($cabin->payment_type))
                                                                <option value="{{ $paymentTypeKey }}"
                                                                        @if(in_array($paymentTypeKey, $cabin->payment_type )) selected="selected" @endif>{{ $paymentType }}</option>
                                                            @else
                                                                <option value="{{ $paymentTypeKey }}">{{ $paymentType }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('payment'))
                                                        <span class="help-block"><strong>{{ $errors->first('payment') }}</strong></span>
                                                    @endif
                                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('latitude') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblLatitude')  </label>
                                                    <input type="text" class="form-control" id="latitude"
                                                           name="latitude"
                                                           placeholder="@lang('cabins.lblLatitudePH')"
                                                           value="{{old('latitude' , $cabin->latitude)}}"
                                                           maxlength="100">

                                                    @if ($errors->has('latitude'))
                                                        <span class="help-block"><strong>{{ $errors->first('latitude') }}</strong></span>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('longitude') ? ' has-error' : '' }}">
                                                    <label>@lang('cabins.lblLongitude')  </label>
                                                    <input type="text" class="form-control" id="longitude"
                                                           name="longitude"
                                                           placeholder="@lang('cabins.lblLongitudePH')"
                                                           value="{{old('longitude', $cabin->longitude )}}"
                                                           maxlength="100">

                                                    @if ($errors->has('longitude'))
                                                        <span class="help-block"><strong>{{ $errors->first('longitude') }}</strong></span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>@lang('cabins.lblNeighbour')</label>
                                            <select id="neighbour" name="neighbour[]"
                                                    class="form-control neighbour"
                                                    multiple="multiple"
                                                    data-placeholder="@lang('cabins.lblNeighbourPH')"
                                                    style="width: 100%;">
                                                @foreach($cabinInfo->cabins() as $neighbour)
                                                    @if(!empty($cabin->neighbour_cabin))
                                                        <option value="{{ $neighbour->_id }}"
                                                                @if(in_array($neighbour->_id, $cabin->neighbour_cabin )) selected="selected" @endif>{{ $neighbour->name }}</option>
                                                    @else
                                                        <option value="{{ $neighbour->_id }}"
                                                                @if( old('neighbour') != "" &&  in_array($neighbour->_id, old('neighbour'))) selected="selected" @endif>{{ $neighbour->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="addtoDiv">
                                                    <input type="hidden" name="count" value="1"/>
                                                    <img src="/img/add1.png" alt="Add More" class="add-more-dynamic"
                                                         title="Add More" width="25px" height="25px"
                                                         style=" margin-top: 15px; "/>
                                                    <label class="addNeighbourlbl">@lang('cabins.lblNewNeighbourCabin')</label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <!--
                                    <div class="col-md-2  align-text-bottom">
                                        <div class="form-group" style="  margin-top: 35px;">
                                            <!--<img src="/img/add1.png" alt="Add More" id="add_more"   title ="Add More" width="25px" height="25px">
                                        </div>
                                    </div> -->
                                </div>
                               <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <label>@lang('cabins.lblMoreDetails')</label>
                                        <textarea id="details" name="details" class="otherDetails"
                                                  placeholder="@lang('cabins.moreDetailsPH')"
                                                  style="width: 100%; height: 150px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{old('details' ,  $cabin->other_details)}}</textarea>
                                    </div>
                                    </div>
                                </div>
                                <!----------------------------->
                                <input type="hidden" name="cabin_id" value="{{$cabin->_id}}">
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-primary pull-right" name="updateCabin"   id="updateCabin" data-loading-text="Updating..."
                                                    value="updateCabin"><i
                                                        class="fa fa-fw fa-save"></i>@lang('cabins.btnUpdate')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- boxbody ends -->
                        </div>

                    </div>
                </div>
            </form>
            <!-- *************************************** -->

        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script type="text/javascript"
            src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.de-DE.js') }}"></script>
    <!-- cabin Edit  Js -->
    <script src="{{ asset('js/cabinCrud.js') }}"></script>
    <script>
    </script>
@endsection