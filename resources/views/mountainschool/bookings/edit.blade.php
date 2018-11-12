@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Edit Booking #' . $booking->invoice_number)

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @lang('mountainschool.bookings')
            <small>@lang('mountainschool.controlPanel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('mountainschool.dashboard')</a></li>
            <li class="active">@lang('mountainschool.bookings')</li>
        </ol>
    </section>

    @if (session()->has('successMsgSave'))
        <div id="flash" class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ session()->get('successMsgSave') }}
        </div>
    @endif

    @inject('calendarServices', 'App\Http\Controllers\Mountainschool\CalendarController')

    <div class="content">
        <form action="{{ route('mountainschool.bookings.update', ['id' => $booking->id]) }}" method="POST" id="update_booking_form">
            {{ csrf_field() }}
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">{{ __('mountainschool/bookings.edit.title') }}</h3>
                </div>

                <div class="box-body">

                    {{-- Booking Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('booking_number') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.tour_name') }} <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="booking_number"
                                       name="booking_number"
                                       value="{{ old('booking_number', $booking->invoice_number) }}"
                                       placeholder="test" maxlength="100">
                                <span class="help-block"><strong>  {{ $errors->first('booking_number') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Tour Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('tour_number') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.tour_number') }} <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="tour_number"
                                       name="tour_number"
                                       value="{{ old('tour_number', $booking->ind_tour_no) }}"
                                       placeholder="test" maxlength="100">
                                <span class="help-block"><strong>  {{ $errors->first('tour_number') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Cabin Name --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('cabin_name') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.cabin_name') }} <span class="required">*</span>
                                </label>
                                 <select id="tour_cabins" name="tour_cabins" class="form-control">
                                    <option value="">@lang('tours.lblCabinPH')</option>
                                    <optgroup label="@lang('tours.registeredCabin')">
                                        @foreach($cabins as $Key => $val )
                                            @if($val->other_cabin == '0')
                                                <option value="{{$val->name}}">{{$val->name}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>

                                    <optgroup label="@lang('tours.neighborCabin')">
                                        @foreach($cabins  as $Key => $val )
                                            @if($val->other_cabin == '1')
                                                <option value="{{$val->name}}">{{$val->name}}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>

                                    <optgroup label="@lang('tours.createNewCabin')">
                                        <option value="new_cabin">@lang('tours.CreateNewCabinLabel')</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Reservation Dates --}}

                    @php
                        $calendar = $calendarServices->calendar($cabin->id);
                    @endphp

                    <div class="row">
                        <div class="col-md-6">
                            <div class="calendar" data-id="1" data-cabinid="{{ $cabin['_id'] }}">
                                <div class="holiday1" data-holiday="{{ $calendar[0] }}"></div>
                                <div class="green1" data-green="{{ $calendar[1] }}"></div>
                                <div class="orange1" data-orange="{{ $calendar[2] }}"></div>
                                <div class="red1" data-red="{{ $calendar[3] }}"></div>
                                <div class="notSeasonTime1" data-notseasontime="{{ $calendar[4] }}"></div>


                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('check_in') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblCheckIn')<span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control checkInCls"
                                                   id="check_in1"
                                                   name="check_in1[]"
                                                   placeholder="{{ old('check_in', $booking->check_in->format('d.m.y')) }}"
                                                   readonly
                                                   autocomplete="off"
                                            >

                                            <span class="help-block"><strong>{{ $errors->first('check_in') }}</strong></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('check_out') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblCheckOut')<span class="required">*</span></label>
                                            <input type="text"
                                                   class="form-control checkOutCls"
                                                   id="check_out1"
                                                   name="check_out1[]"
                                                   placeholder="{{ old('check_out', $booking->reserve_to->format('d.m.y')) }}"
                                                   readonly
                                                   autocomplete="off"
                                            >
                                            <span class="help-block"><strong>{{ $errors->first('check_out') }}</strong></span>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>

                    {{-- Beds Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.beds') }} <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="beds"
                                       name="beds"
                                       value="{{ $booking->beds ? old('beds', $booking->beds) : 0 }}"
                                       maxlength="100">
                                <span class="help-block"><strong>  {{ $errors->first('beds') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Dorms Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('dorms') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.dorms') }} <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="dorms"
                                       name="dorms"
                                       value="{{ $booking->dormitory ? old('dorms', $booking->dormitory) : 0 }}"
                                       maxlength="100">
                                <span class="help-block"><strong>  {{ $errors->first('dorms') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Sleeps Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.sleeps') }} <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="sleeps"
                                       name="sleeps"
                                       value="{{ $booking->sleeps ? old('sleeps', $booking->sleeps) : 0 }}"
                                       maxlength="100">
                                <span class="help-block"><strong>  {{ $errors->first('sleeps') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit"
                                    class="btn btn-primary pull-right"
                                    name="editBooking"
                                    id="editBooking"
                                    data-loading-text="Loading..."
                                    value="editBooking"
                            >
                                    <i class="fa fa-fw fa-save"></i>
                                    {{ __('mountainschool/bookings.edit.form.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection