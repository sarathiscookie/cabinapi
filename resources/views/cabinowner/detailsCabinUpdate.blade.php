@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Cabin Information Edit')

@section('css')
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" />

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
                            <form>
                                <div class="box-body">
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

                                                <select class="form-control" id="cancel" name="cancel">
                                                    @foreach($cabinInfo->reservationCancel() as $key => $type)
                                                        <option value="{{ $key }}" @if($key == $cabin->reservation_cancel || old('legal') == $cabin->reservation_cancel) selected="selected" @endif>{{ $type }}</option>
                                                    @endforeach
                                                </select>

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
                                            <div class="form-group {{ $errors->has('facility') ? ' has-error' : '' }}">
                                                <label>@lang('details.cabinBoxLabelFacility') <span class="required">*</span></label>
                                                <select id="facility" name="facility" class="form-control interior" multiple="multiple" data-placeholder="Choose facility cabin" style="width: 100%;">
                                                    @foreach($cabin->interior as $interior)
                                                        @foreach($cabinInfo->interiorLabel() as $key => $interiorLabel)
                                                            <option value="{{ $key }}" @if($key == $interior || old('facility') == $interior) selected="selected" @endif>{{ $interiorLabel }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('facility'))
                                                    <span class="help-block"><strong>{{ $errors->first('facility') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('halfboard') ? ' has-error' : '' }}">
                                                <label>Halfboard</label>

                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" id="halfboard" name="halfboard" value="1" @if($cabin->halfboard == '1' || old('halfboard') == '1') checked @endif>
                                                    </label>
                                                </div>

                                                @if ($errors->has('halfboard'))
                                                    <span class="help-block"><strong>{{ $errors->first('halfboard') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4 priceBox">
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
                                            <div class="form-group {{ $errors->has('payment') ? ' has-error' : '' }}">
                                                <label>@lang('details.cabinBoxLabelPayType') <span class="required">*</span></label>
                                                <select id="payment" name="payment" class="form-control payment" multiple="multiple" data-placeholder="Choose payment type" style="width: 100%;">
                                                    @foreach($cabin->payment_type as $payment)
                                                        @foreach($cabinInfo->paymentType() as $paymentTypeKey => $paymentType)
                                                            <option value="{{ $paymentTypeKey }}" @if($paymentTypeKey == $payment || old('payment') == $payment) selected="selected" @endif>{{ $paymentType }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('payment'))
                                                    <span class="help-block"><strong>{{ $errors->first('payment') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('neighbour') ? ' has-error' : '' }}">
                                                <label>@lang('details.cabinBoxLabelNeighbour') <span class="required">*</span></label>

                                                <select id="neighbour" name="neighbour" class="form-control neighbour" multiple="multiple" data-placeholder="Choose neighbour cabin" style="width: 100%;">
                                                    @foreach($cabin->neighbour_cabin as $neighbour_cabin)
                                                        @foreach($cabinInfo->cabins() as $neighbour)
                                                            <option value="{{ $neighbour->_id }}" @if($neighbour->_id == $neighbour_cabin || old('neighbour') == $neighbour_cabin) selected="selected" @endif>{{ $neighbour->name }}</option>
                                                        @endforeach
                                                    @endforeach
                                                </select>

                                                @if ($errors->has('neighbour'))
                                                    <span class="help-block"><strong>{{ $errors->first('neighbour') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('deposit') ? ' has-error' : '' }}">
                                                <label>@lang('details.cabinBoxLabelDeposit') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="deposit" name="deposit" placeholder="@lang('details.cabinBoxLabelDepositPH')" value="{{old('deposit', $cabin->prepayment_amount)}}" maxlength="15">

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

                                                {{--<input type="text" class="form-control" id="details" name="details" placeholder="@lang('details.cabinBoxLabelMoreDetailsPH')" value="{{old('details', $cabin->other_details)}}">--}}
                                                <textarea class="otherDetails" placeholder="@lang('details.cabinBoxLabelMoreDetailsPH')" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ $cabin->other_details }}</textarea>

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

                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="" class="btn btn-primary btn-block"><i class="fa fa-fw fa-edit"></i>@lang('details.contactLabelEditButton')</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

@section('scripts')
    <!-- Select2 -->
    <script type="text/javascript" src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <!-- Bootstrap WYSIHTML5 -->
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>

    <script>
        $(function () {
            /* Multiple select for neighbour cabins */
            $(".neighbour").select2();

            /* Multiple select for interior cabins */
            $(".interior").select2();

            /* Multiple select for payment */
            $(".payment").select2();

            /* Editor for listing cabin more details*/
            $(".otherDetails").wysihtml5();

            /* Hide show function for halfboard price */
            if($('#halfboard').is(":checked")) {
                $('.priceBox').show();
            }
            else {
                $('.priceBox').hide();
            }

            $('#halfboard').on('change', function() {
                $('.priceBox').toggle();
            });

        });
    </script>
@endsection
