@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Contingent')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <style type="text/css">
        .nounderline {
            text-decoration: none;
            cursor: pointer;
        }
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
                @lang('contingent.pageHeading')
                <small>@lang('contingent.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('contingent.dashboard')</a></li>
                <li><i class="fa fa-edit"></i> @lang('contingent.breadcrumbOne')</li>
                <li class="active">@lang('contingent.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                {{--<div class="col-md-3"></div>--}}
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('contingent.formHeading')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        @if(isset($cabin))
                            <form role="form" method="post" action="{{ route('cabinowner.contingent.update') }}">
                                {{ csrf_field() }}
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('reservation_type') ? ' has-error' : '' }}">
                                                <label for="reservation_type">@lang('contingent.reservationType') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select reservation types"></i></label>

                                                <select class="form-control" name="reservation_type" id="reservation_type">
                                                    <option value="2">--- @lang('contingent.selectReservationType') ---</option>
                                                    <option value="0" @if($cabin->sleeping_place == 0 || old('reservation_type') == 0) selected="selected" @endif>@lang('contingent.reservationTypeBeds')</option>
                                                    <option value="1" @if($cabin->sleeping_place == 1 || old('reservation_type') == 1) selected="selected" @endif>@lang('contingent.reservationTypeSleeps')</option>
                                                </select>

                                                @if ($errors->has('reservation_type'))
                                                    <span class="help-block"><strong>{{ $errors->first('reservation_type') }}</strong></span>
                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('normal_beds') ? ' has-error' : '' }}">
                                                <label for="normal_beds">@lang('contingent.noOfBedsLabel') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="normal_beds" name="normal_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('normal_beds', $cabin->beds)}}">

                                                @if ($errors->has('normal_beds'))
                                                    <span class="help-block"><strong>{{ $errors->first('normal_beds') }}</strong></span>
                                                @endif
                                            </div>
                                            <div class="form-group {{ $errors->has('normal_dorms') ? ' has-error' : '' }}">
                                                <label for="normal_dorms">@lang('contingent.noOfDormsLabel') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="normal_dorms" name="normal_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('normal_dorms', $cabin->dormitory)}}">

                                                @if ($errors->has('normal_dorms'))
                                                    <span class="help-block"><strong>{{ $errors->first('normal_dorms') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('normal_emergency_rooms') ? ' has-error' : '' }}">
                                                <label for="normal_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                <input type="text" class="form-control" id="normal_emergency_rooms" name="normal_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('normal_emergency_rooms', $cabin->makeshift)}}">

                                                @if ($errors->has('normal_emergency_rooms'))
                                                    <span class="help-block"><strong>{{ $errors->first('normal_emergency_rooms') }}</strong></span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has('normal_inquiry_guest') ? ' has-error' : '' }}">
                                                <label for="normal_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                <input type="text" class="form-control" id="normal_inquiry_guest" name="normal_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('normal_inquiry_guest', $cabin->inquiry_starts)}}">

                                                @if ($errors->has('normal_inquiry_guest'))
                                                    <span class="help-block"><strong>{{ $errors->first('normal_inquiry_guest') }}</strong></span>
                                                @endif
                                            </div>

                                            <div class="form-group {{ $errors->has('normal_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                <label for="normal_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                <input type="text" class="form-control" id="normal_ms_inquiry_guest" name="normal_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('normal_ms_inquiry_guest', $cabin->ms_inquiry_starts)}}">

                                                @if ($errors->has('normal_ms_inquiry_guest'))
                                                    <span class="help-block"><strong>{{ $errors->first('normal_ms_inquiry_guest') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="rule">@lang('contingent.rulesLabel') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select rules."></i></label>
                                        <div>
                                            <label for="regularCheckbox">
                                                <input type="checkbox" id="regularCheckbox" name="regularCheckbox" value="1" @if($cabin->regular == 1 || old('regularCheckbox') == 1) checked @endif>
                                                Regular
                                            </label>
                                            <label for="notRegularCheckbox">
                                                <input type="checkbox" id="notRegularCheckbox" name="notRegularCheckbox" value="1" @if($cabin->not_regular == 1 || old('notRegularCheckbox') == 1) checked @endif>
                                                Not Regular
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Div for regular -->
                                    <div class="form-group" id="regular">

                                        <div class="box box-default box-solid">

                                            <div class="box-header with-border">
                                                <h3 class="box-title"> @lang('contingent.selectRulesRegular')</h3>
                                            </div>

                                            <div class="box-body">
                                                <!-- Monday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="monday" name="monday" value="1"> @lang('contingent.monday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('mon_beds') ? ' has-error' : '' }}">
                                                                <label for="mon_beds"> @lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="mon_beds" name="mon_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('mon_beds', $cabin->mon_beds)}}">

                                                                @if ($errors->has('mon_beds'))
                                                                    <span class="help-block"><strong>{{ $errors->first('mon_beds') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('mon_dorms') ? ' has-error' : '' }}">
                                                                <label for="mon_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="mon_dorms" name="mon_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('mon_dorms', $cabin->mon_dorms)}}">

                                                                @if ($errors->has('mon_dorms'))
                                                                    <span class="help-block"><strong>{{ $errors->first('mon_dorms') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('mon_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="mon_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="mon_emergency_rooms" name="mon_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('mon_emergency_rooms', $cabin->mon_emergency_rooms)}}">

                                                                @if ($errors->has('mon_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{ $errors->first('mon_emergency_rooms') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('mon_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="mon_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="mon_inquiry_guest" name="mon_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('mon_inquiry_guest', $cabin->mon_inquiry_guest)}}">

                                                                @if ($errors->has('mon_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{ $errors->first('mon_inquiry_guest') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('mon_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="mon_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="mon_ms_inquiry_guest" name="mon_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('mon_ms_inquiry_guest', $cabin->mon_ms_inquiry_guest)}}">

                                                                @if ($errors->has('mon_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{ $errors->first('mon_ms_inquiry_guest') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Tuesday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="tuesday" name="tuesday" value="1"> @lang('contingent.tuesday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('tue_beds') ? ' has-error' : '' }}">
                                                                <label for="tue_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="tue_beds" name="tue_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('tue_beds', $cabin->tue_beds)}}">

                                                                @if ($errors->has('tue_beds'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tue_beds') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('tue_dorms') ? ' has-error' : '' }}">
                                                                <label for="tue_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="tue_dorms" name="tue_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('tue_dorms', $cabin->tue_dorms)}}">

                                                                @if ($errors->has('tue_dorms'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tue_dorms') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('tue_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="tue_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="tue_emergency_rooms" name="tue_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('tue_emergency_rooms', $cabin->tue_emergency_rooms)}}">

                                                                @if ($errors->has('tue_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tue_emergency_rooms') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('tue_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="tue_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="tue_inquiry_guest" name="tue_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('tue_inquiry_guest', $cabin->tue_inquiry_guest)}}">

                                                                @if ($errors->has('tue_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tue_inquiry_guest') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('tue_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="tue_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="tue_ms_inquiry_guest" name="tue_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('tue_ms_inquiry_guest', $cabin->tue_ms_inquiry_guest)}}">

                                                                @if ($errors->has('tue_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{ $errors->first('tue_ms_inquiry_guest') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Wednesday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="wednesday" name="wednesday" value="1"> @lang('contingent.wednesday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('wed_beds') ? ' has-error' : '' }}">
                                                                <label for="wed_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="wed_beds" name="wed_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('wed_beds', $cabin->wed_beds)}}">

                                                                @if ($errors->has('wed_beds'))
                                                                    <span class="help-block"><strong>{{ $errors->first('wed_beds') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('wed_dorms') ? ' has-error' : '' }}">
                                                                <label for="wed_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="wed_dorms" name="wed_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('wed_dorms', $cabin->wed_beds)}}">

                                                                @if ($errors->has('wed_dorms'))
                                                                    <span class="help-block"><strong>{{ $errors->first('wed_dorms') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('wed_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="wed_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="wed_emergency_rooms" name="wed_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('wed_emergency_rooms', $cabin->wed_emergency_rooms)}}">

                                                                @if ($errors->has('wed_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{$errors->first('wed_emergency_rooms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('wed_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="wed_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="wed_inquiry_guest" name="wed_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('wed_inquiry_guest', $cabin->wed_inquiry_guest)}}">

                                                                @if ($errors->has('wed_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('wed_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('wed_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="wed_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="wed_ms_inquiry_guest" name="wed_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('wed_ms_inquiry_guest', $cabin->wed_ms_inquiry_guest)}}">

                                                                @if ($errors->has('wed_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('wed_ms_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Thursday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="thursday" name="thursday" value="1"> @lang('contingent.thursday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('thu_beds') ? ' has-error' : '' }}">
                                                                <label for="thu_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="thu_beds" name="thu_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('thu_beds', $cabin->thu_beds)}}">

                                                                @if ($errors->has('thu_beds'))
                                                                    <span class="help-block"><strong>{{$errors->first('thu_beds')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('thu_dorms') ? ' has-error' : '' }}">
                                                                <label for="thu_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="thu_dorms" name="thu_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('thu_dorms', $cabin->thu_dorms)}}">

                                                                @if ($errors->has('thu_dorms'))
                                                                    <span class="help-block"><strong>{{$errors->first('thu_dorms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('thu_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="thu_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="thu_emergency_rooms" name="thu_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('thu_emergency_rooms', $cabin->thu_emergency_rooms)}}">

                                                                @if ($errors->has('thu_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{$errors->first('thu_emergency_rooms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('thu_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="thu_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="thu_inquiry_guest" name="thu_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('thu_inquiry_guest', $cabin->thu_inquiry_guest)}}">

                                                                @if ($errors->has('thu_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('thu_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('thu_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="thu_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="thu_ms_inquiry_guest" name="thu_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('thu_ms_inquiry_guest', $cabin->thu_inquiry_guest)}}">

                                                                @if ($errors->has('thu_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('thu_ms_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Friday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="friday" name="friday" value="1"> @lang('contingent.friday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('fri_beds') ? ' has-error' : '' }}">
                                                                <label for="fri_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="fri_beds" name="fri_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('fri_beds', $cabin->fri_beds)}}">

                                                                @if ($errors->has('fri_beds'))
                                                                    <span class="help-block"><strong>{{$errors->first('fri_beds')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('fri_dorms') ? ' has-error' : '' }}">
                                                                <label for="fri_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="fri_dorms" name="fri_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('fri_dorms', $cabin->fri_dorms)}}">

                                                                @if ($errors->has('fri_dorms'))
                                                                    <span class="help-block"><strong>{{$errors->first('fri_dorms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('fri_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="fri_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="fri_emergency_rooms" name="fri_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('fri_emergency_rooms', $cabin->fri_emergency_rooms)}}">

                                                                @if ($errors->has('fri_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{$errors->first('fri_emergency_rooms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('fri_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="fri_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="fri_inquiry_guest" name="fri_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('fri_inquiry_guest', $cabin->fri_inquiry_guest)}}">

                                                                @if ($errors->has('fri_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('fri_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('fri_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="fri_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="fri_ms_inquiry_guest" name="fri_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('fri_ms_inquiry_guest', $cabin->fri_ms_inquiry_guest)}}">

                                                                @if ($errors->has('fri_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('fri_ms_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Saturday Div -->
                                                <div class="col-md-4">
                                                    <div class="box box-default collapsed-box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="saturday" name="saturday" value="1"> @lang('contingent.saturday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('sat_beds') ? ' has-error' : '' }}">
                                                                <label for="sat_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="sat_beds" name="sat_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('sat_beds', $cabin->sat_beds)}}">

                                                                @if ($errors->has('sat_beds'))
                                                                    <span class="help-block"><strong>{{$errors->first('sat_beds')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sat_dorms') ? ' has-error' : '' }}">
                                                                <label for="sat_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="sat_dorms" name="sat_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('sat_dorms', $cabin->sat_dorms)}}">

                                                                @if ($errors->has('sat_dorms'))
                                                                    <span class="help-block"><strong>{{$errors->first('sat_dorms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sat_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="sat_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="sat_emergency_rooms" name="sat_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('sat_emergency_rooms', $cabin->sat_emergency_rooms)}}">

                                                                @if ($errors->has('sat_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{$errors->first('sat_emergency_rooms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sat_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="sat_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="sat_inquiry_guest" name="sat_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('sat_inquiry_guest', $cabin->sat_inquiry_guest)}}">

                                                                @if ($errors->has('sat_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('sat_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sat_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="sat_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="sat_ms_inquiry_guest" name="sat_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('sat_ms_inquiry_guest', $cabin->sat_ms_inquiry_guest)}}">

                                                                @if ($errors->has('sat_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('sat_ms_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Sunday Div -->
                                                <div class="col-md-4">
                                                    <div @if($errors->has('sun_beds') || $errors->has('sun_dorms')) class="box box-danger box-solid" @else class="box box-default collapsed-box box-solid" @endif>
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title"><input type="checkbox" id="sunday" name="sunday" value="1"> @lang('contingent.sunday')</h3>

                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="box-body">

                                                            <div class="form-group {{ $errors->has('sun_beds') ? ' has-error' : '' }}">
                                                                <label for="sun_beds">@lang('contingent.noOfBedsLabel')</label>

                                                                <input type="text" class="form-control" id="sun_beds" name="sun_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('sun_beds', $cabin->sun_beds)}}">

                                                                @if ($errors->has('sun_beds'))
                                                                    <span class="help-block"><strong>{{$errors->first('sun_beds')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sun_dorms') ? ' has-error' : '' }}">
                                                                <label for="sun_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                                <input type="text" class="form-control" id="sun_dorms" name="sun_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('sun_dorms', $cabin->sun_dorms)}}">

                                                                @if ($errors->has('sun_dorms'))
                                                                    <span class="help-block"><strong>{{$errors->first('sun_dorms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sun_emergency_rooms') ? ' has-error' : '' }}">
                                                                <label for="sun_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                                <input type="text" class="form-control" id="sun_emergency_rooms" name="sun_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('sun_emergency_rooms', $cabin->sun_emergency_rooms)}}">

                                                                @if ($errors->has('sun_emergency_rooms'))
                                                                    <span class="help-block"><strong>{{$errors->first('sun_emergency_rooms')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sun_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="sun_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="sun_inquiry_guest" name="sun_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('sun_inquiry_guest', $cabin->sun_inquiry_guest)}}">

                                                                @if ($errors->has('sun_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('sun_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('sun_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                                <label for="sun_ms_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                                <input type="text" class="form-control" id="sun_ms_inquiry_guest" name="sun_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('sun_ms_inquiry_guest', $cabin->sun_ms_inquiry_guest)}}">

                                                                @if ($errors->has('sun_ms_inquiry_guest'))
                                                                    <span class="help-block"><strong>{{$errors->first('sun_ms_inquiry_guest')}}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Div for not regular -->
                                    <div class="form-group" id="notRegular">

                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">@lang('contingent.selectRulesNotRegular')</h3>

                                                <div class="box-tools pull-right">
                                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="box-body">
                                                <div class="col-md-6">

                                                    <div class="form-group {{ $errors->has('not_regular_date') ? ' has-error' : '' }}">
                                                        <label>@lang('contingent.daterangepickerButtonLabel')</label>

                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>

                                                            <input type="text" class="form-control pull-right" id="daterange" name="not_regular_date" value="{{old('not_regular_date', $cabin->not_regular_date)}}">

                                                            @if ($errors->has('not_regular_date'))
                                                                <span class="help-block"><strong>{{ $errors->first('not_regular_date') }}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group {{ $errors->has('not_regular_beds') ? ' has-error' : '' }}">
                                                        <label for="not_regular_beds">@lang('contingent.noOfBedsLabel')</label>

                                                        <input type="text" class="form-control" id="not_regular_beds" name="not_regular_beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{old('not_regular_beds', $cabin->not_regular_beds)}}">

                                                        @if ($errors->has('not_regular_beds'))
                                                            <span class="help-block"><strong>{{ $errors->first('not_regular_beds') }}</strong></span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group {{ $errors->has('not_regular_dorms') ? ' has-error' : '' }}">
                                                        <label for="not_regular_dorms">@lang('contingent.noOfDormsLabel')</label>

                                                        <input type="text" class="form-control" id="not_regular_dorms" name="not_regular_dorms" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{old('not_regular_dorms', $cabin->not_regular_dorms)}}">

                                                        @if ($errors->has('not_regular_dorms'))
                                                            <span class="help-block"><strong>{{ $errors->first('not_regular_dorms') }}</strong></span>
                                                        @endif
                                                    </div>

                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group {{ $errors->has('not_regular_emergency_rooms') ? ' has-error' : '' }}">
                                                        <label for="not_regular_emergency_rooms">@lang('contingent.emergencyRoomsLabel')</label>

                                                        <input type="text" class="form-control" id="not_regular_emergency_rooms" name="not_regular_emergency_rooms" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{old('not_regular_emergency_rooms', $cabin->not_regular_emergency_rooms)}}">

                                                        @if ($errors->has('not_regular_emergency_rooms'))
                                                            <span class="help-block"><strong>{{ $errors->first('not_regular_emergency_rooms') }}</strong></span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group {{ $errors->has('not_regular_inquiry_guest') ? ' has-error' : '' }}">
                                                        <label for="not_regular_inquiry_guest">@lang('contingent.inquiryGuestLabel')</label>

                                                        <input type="text" class="form-control" id="not_regular_inquiry_guest" name="not_regular_inquiry_guest" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{old('not_regular_inquiry_guest', $cabin->not_regular_inquiry_guest)}}">

                                                        @if ($errors->has('not_regular_inquiry_guest'))
                                                            <span class="help-block"><strong>{{ $errors->first('not_regular_inquiry_guest') }}</strong></span>
                                                        @endif
                                                    </div>

                                                    <div class="form-group {{ $errors->has('not_regular_ms_inquiry_guest') ? ' has-error' : '' }}">
                                                        <label for="not_ms_regular_inquiry_guest">@lang('contingent.mschoolInquiryGuestLabel')</label>

                                                        <input type="text" class="form-control" id="not_regular_ms_inquiry_guest" name="not_regular_ms_inquiry_guest" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{old('not_regular_ms_inquiry_guest', $cabin->not_regular_ms_inquiry_guest)}}">

                                                        @if ($errors->has('not_regular_ms_inquiry_guest'))
                                                            <span class="help-block"><strong>{{ $errors->first('not_regular_ms_inquiry_guest') }}</strong></span>
                                                        @endif
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <!-- /.box -->
                </div>
                {{--<div class="col-md-3"></div>--}}
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')

    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Contingent Js -->
    <script src="{{ asset('js/contingent.js') }}"></script>
@endsection