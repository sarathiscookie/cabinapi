@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Cabin Information Edit')

@section('css')
    <style type="text/css">
        .list-group-item{
            cursor: default;
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
                @lang('details.heading')
                <small>@lang('details.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('details.breadcrumbOne')</a></li>
                <li><a href="/cabinowner/details"><i class="fa fa fa-table"></i> @lang('details.breadcrumbTwo')</a></li>
                <li class="fa fa-edit active">Cabin</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Cabin Information</h4>
                        </div>

                        @isset($cabin)
                            <div class="box-body">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#cabin" data-toggle="tab">Cabin</a></li>
                                        <li><a href="#facility" data-toggle="tab">Facility</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="cabin">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('cabinname') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelName') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="cabinname" name="cabinname" placeholder="@lang('details.cabinBoxLabelNamePH')" value="{{old('cabinname', $cabin->name)}}" maxlength="200">

                                                        @if ($errors->has('cabinname'))
                                                            <span class="help-block"><strong>{{ $errors->first('cabinname') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('height') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelHeight') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="height" name="height" placeholder="@lang('details.cabinBoxLabelHeightPH')" value="{{old('height', $cabin->height)}}" maxlength="10">

                                                        @if ($errors->has('height'))
                                                            <span class="help-block"><strong>{{ $errors->first('height') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('club') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelClub') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="club" name="club" placeholder="@lang('details.cabinBoxLabelClubPH')" value="{{old('club', $cabin->club)}}" maxlength="200">

                                                        @if ($errors->has('club'))
                                                            <span class="help-block"><strong>{{ $errors->first('club') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('cancel') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelCancel') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="cancel" name="cancel" placeholder="@lang('details.cabinBoxLabelCancelPH')" value="{{old('cancel', $cabin->reservation_cancel)}}" maxlength="100">

                                                        @if ($errors->has('cancel'))
                                                            <span class="help-block"><strong>{{ $errors->first('cancel') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('availability') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelAvailability') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="availability" name="availability" placeholder="@lang('details.cabinBoxLabelCancelPH')" value="{{old('availability', $cabin->reachable)}}" maxlength="255">

                                                        @if ($errors->has('availability'))
                                                            <span class="help-block"><strong>{{ $errors->first('availability') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('tours') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelTour') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="tours" name="tours" placeholder="@lang('details.cabinBoxLabelTourPH')" value="{{old('tours', $cabin->tours)}}" maxlength="255">

                                                        @if ($errors->has('tours'))
                                                            <span class="help-block"><strong>{{ $errors->first('tours') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('checkin') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelCheckIn') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="checkin" name="checkin" placeholder="@lang('details.cabinBoxLabelCheckInPH')" value="{{old('checkin', $cabin->checkin_from)}}" maxlength="10">

                                                        @if ($errors->has('checkin'))
                                                            <span class="help-block"><strong>{{ $errors->first('checkin') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('checkout') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelCheckOut') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="checkout" name="checkout" placeholder="@lang('details.cabinBoxLabelCheckOutPH')" value="{{old('checkout', $cabin->reservation_to)}}" maxlength="10">

                                                        @if ($errors->has('checkout'))
                                                            <span class="help-block"><strong>{{ $errors->first('checkout') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{--<div class="form-group {{ $errors->has('facility') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelFacility') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="facility" name="facility" placeholder="@lang('details.cabinBoxLabelFacilityPH')" value="{{old('facility', $cabin->interior)}}" maxlength="100">

                                                        @if ($errors->has('facility'))
                                                            <span class="help-block"><strong>{{ $errors->first('facility') }}</strong></span>
                                                        @endif
                                                    </div>--}}


                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Facility</strong> <br>
                                                            @foreach($cabin->interior as $interior)
                                                                <span class="label label-default">{{ $interior }}</span>
                                                            @endforeach
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelPrice') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="price" name="price" placeholder="@lang('details.cabinBoxLabelPricePH')" value="{{old('price', $cabin->halfboard_price)}}" maxlength="15">

                                                        @if ($errors->has('price'))
                                                            <span class="help-block"><strong>{{ $errors->first('price') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    {{--<div class="form-group {{ $errors->has('payment') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelPayType') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="payment" name="payment" placeholder="@lang('details.cabinBoxLabelPayTypePH')" value="{{old('payment', $cabin->payment_type)}}" maxlength="25">

                                                        @if ($errors->has('payment'))
                                                            <span class="help-block"><strong>{{ $errors->first('payment') }}</strong></span>
                                                        @endif
                                                    </div>--}}

                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Pay Type</strong> <br>
                                                            @foreach($cabin->payment_type as $payment)
                                                                <span class="label label-default">{{ $payment }}</span>
                                                            @endforeach
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    {{--<div class="form-group {{ $errors->has('neighbour') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelNeighbour') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="neighbour" name="neighbour" placeholder="@lang('details.cabinBoxLabelNeighbourPH')" value="{{old('neighbour', $cabin->payment_type)}}" maxlength="200">

                                                        @if ($errors->has('neighbour'))
                                                            <span class="help-block"><strong>{{ $errors->first('neighbour') }}</strong></span>
                                                        @endif
                                                    </div>--}}

                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Neighbours Cabin</strong> <br>
                                                            @foreach($cabin->neighbour_cabin as $neighbour_cabin)
                                                                <span class="label label-default">{{ $neighbour_cabin }}</span>
                                                            @endforeach
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('deposit') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelDeposit') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="deposit" name="deposit" placeholder="@lang('details.cabinBoxLabelNeighbourPH')" value="{{old('deposit', $cabin->prepayment_amount)}}" maxlength="15">

                                                        @if ($errors->has('deposit'))
                                                            <span class="help-block"><strong>{{ $errors->first('deposit') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('website') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelWebsite') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="website" name="website" placeholder="@lang('details.cabinBoxLabelWebsitePH')" value="{{old('website', $cabin->website)}}" maxlength="200">

                                                        @if ($errors->has('website'))
                                                            <span class="help-block"><strong>{{ $errors->first('website') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('details') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelMoreDetails') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="details" name="details" placeholder="@lang('details.cabinBoxLabelMoreDetailsPH')" value="{{old('details', $cabin->other_details)}}">

                                                        @if ($errors->has('details'))
                                                            <span class="help-block"><strong>{{ $errors->first('details') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('region') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelRegion') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="region" name="region" placeholder="@lang('details.cabinBoxLabelRegionPH')" value="{{old('region', $cabin->region)}}" maxlength="200">

                                                        @if ($errors->has('region'))
                                                            <span class="help-block"><strong>{{ $errors->first('region') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('latitude') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelLatitude') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="@lang('details.cabinBoxLabelLatitudePH')" value="{{old('latitude', $cabin->latitude)}}" maxlength="100">

                                                        @if ($errors->has('latitude'))
                                                            <span class="help-block"><strong>{{ $errors->first('latitude') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group {{ $errors->has('longitude') ? ' has-error' : '' }}">
                                                        <label>@lang('details.cabinBoxLabelLongitude') <span class="required">*</span></label>

                                                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="@lang('details.cabinBoxLabelLongitudePH')" value="{{old('longitude', $cabin->longitude)}}" maxlength="100">

                                                        @if ($errors->has('longitude'))
                                                            <span class="help-block"><strong>{{ $errors->first('longitude') }}</strong></span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tab-pane" id="facility">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Wlan</strong><span class="pull-right"><input type="checkbox" id="wlan" name="wlan" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Shower</strong><span class="pull-right"><input type="checkbox" id="shower" name="shower" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Meal via Card</strong><span class="pull-right"><input type="checkbox" id="mealviacard" name="mealviacard" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Breakfeast</strong><span class="pull-right"><input type="checkbox" id="breakfast" name="breakfast" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Washing Machine</strong><span class="pull-right"><input type="checkbox" id="washingmachine" name="washingmachine" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Tv</strong><span class="pull-right"><input type="checkbox" id="tv" name="tv" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Drying Room</strong><span class="pull-right"><input type="checkbox" id="dryingroom" name="dryingroom" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Luggage Transport</strong><span class="pull-right"><input type="checkbox" id="luggagetransport" name="luggagetransport" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Reachable by car</strong><span class="pull-right"><input type="checkbox" id="bycar" name="bycar" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Dog allowed</strong><span class="pull-right"><input type="checkbox" id="luggage" name="luggage" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Wheelchair accessible</strong><span class="pull-right"><input type="checkbox" id="wheelchair" name="wheelchair" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Public phone</strong><span class="pull-right"><input type="checkbox" id="publicPhone" name="publicPhone" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Power supply for own guests</strong><span class="pull-right"><input type="checkbox" id="powerSupply" name="powerSupply" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Dustbins</strong><span class="pull-right"><input type="checkbox" id="dustbins" name="dustbins" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Cabin shop</strong><span class="pull-right"><input type="checkbox" id="cabinShop" name="cabinShop" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Ascent possibility incl</strong><span class="pull-right"><input type="checkbox" id="ascentPossibility" name="ascentPossibility" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Accessible by telephone</strong><span class="pull-right"><input type="checkbox" id="telephoneAccess" name="telephoneAccess" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Smoking allowed</strong><span class="pull-right"><input type="checkbox" id="smokingAllowed" name="smokingAllowed" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Smoke Detector</strong><span class="pull-right"><input type="checkbox" id="smokeDetector" name="smokeDetector" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Carbon monoxide detector</strong><span class="pull-right"><input type="checkbox" id="carbMonDetector" name="carbMonDetector" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="list-group">
                                                        <a href="#" class="list-group-item">
                                                            <strong>Helicopter land available</strong><span class="pull-right"><input type="checkbox" id="helicopterLand" name="helicopterLand" value="1"></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- /.tab-content -->
                                </div>
                                <!-- /.nav-tabs-custom -->
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="btn btn-primary btn-block"><i class="fa fa-fw fa-edit"></i>@lang('details.contactLabelEditButton')</a>
                                    </div>
                                </div>
                            </div>
                        @endisset

                        @empty($cabin)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item">
                                            <p class="list-group-item-text">@lang('details.noResult')</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endempty

                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection


