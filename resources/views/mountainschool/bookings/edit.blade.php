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

                @if (Session::has('error'))
                    <div id="flash" class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ Session::get('error') }}
                    </div>
                @endif

                <div class="box-body">

                    {{-- Booking Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('booking_number') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.booking_number') }} <span class="required">*</span>
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="booking_number"
                                       name="booking_number"
                                       value="{{ old('booking_number', $booking->invoice_number) }}"
                                       readonly
                                >
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
                                <input type="text"
                                       class="form-control"
                                       id="tour_number"
                                       name="tour_number"
                                       value="{{ old('tour_number', $booking->ind_tour_no) }}"
                                       readonly
                                >
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
                                <input type="text"
                                       name="cabin_name"
                                       class="form-control"
                                       value="{{ old('cabin_name', $booking->cabin_name) }}"
                                       readonly
                                >
                            </div>
                        </div>
                    </div>

                    {{-- Reservation Dates --}}

                    @php
                        $calendar = $calendarServices->calendar($cabin->id);
                    @endphp

                    <div class="row">
                        <div class="col-md-6">
                            <div class="calendar row" data-id="1" data-cabinid="{{ $cabin['_id'] }}">
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
                                                   value="{{ old('check_in', $booking->check_in->format('d.m.y')) }}"
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
                                                   value="{{ old('check_out', $booking->reserve_to->format('d.m.y')) }}"
                                                   readonly
                                                   autocomplete="off"
                                            >
                                            <span class="help-block"><strong>{{ $errors->first('check_out') }}</strong></span>
                                        </div>
                                    </div>

                            </div>
                        </div>
                    </div>

                    {{-- Guides Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('guides') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.guides') }} <span class="required">*</span>
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="guides"
                                       name="guides"
                                       value="{{ $booking->no_guides ? old('guides', $booking->no_guides) : 0 }}"
                                >
                                <span class="help-block"><strong>  {{ $errors->first('guides') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    {{-- Guests Number --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('guests') ? ' has-error' : '' }}">
                                <label>
                                    {{ __('mountainschool/bookings.edit.form.guests') }} <span class="required">*</span>
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="guests"
                                       name="guests"
                                       value="{{ $booking->guests ? old('guests', $booking->guests) : 0 }}"
                                >
                                <span class="help-block"><strong>  {{ $errors->first('guests') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    @if ($cabin->sleeping_place)
                        {{-- Sleeps Number --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                    <label>
                                        {{ __('mountainschool/bookings.edit.form.sleeps') }} <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="sleeps"
                                           name="sleeps"
                                           value="{{ $booking->sleeps ? old('sleeps', $booking->sleeps) : 0 }}"
                                    >
                                    <span class="help-block"><strong>  {{ $errors->first('sleeps') }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Beds Number --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                    <label>
                                        {{ __('mountainschool/bookings.edit.form.beds') }} <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           class="form-control"
                                           id="beds"
                                           name="beds"
                                           value="{{ $booking->beds ? old('beds', $booking->beds) : 0 }}"
                                    >
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
                                    <input type="number"
                                           class="form-control"
                                           id="dorms"
                                           name="dorms"
                                           value="{{ $booking->dormitory ? old('dorms', $booking->dormitory) : 0 }}"
                                    >
                                    <span class="help-block"><strong>  {{ $errors->first('dorms') }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($cabin->halfboard)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{ __('mountainschool/bookings.edit.form.halfboard') }}</label>
                                    <div class="checkbox {{ $errors->has('halfboard') ? 'has-error' : '' }}">
                                        <label>
                                            <input type="checkbox" name="halfboard" @if ($booking->halfboard) checked @endif> {{ __('mountainschool/bookings.edit.form.halfboard') }}
                                        </label>
                                        <span class="help-block"><strong>  {{ $errors->first('halfboard') }}</strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit"
                                    class="btn btn-primary pull-right"
                                    name="edit"
                                    id="edit"
                                    data-loading-text="Loading..."
                                    value="edit"
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