@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Create new booking')

@section('css')
    <!-- Hotel Datepicker CSS -->
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('plugins/hoteldatepicker/hotel-datepicker.css') }}" />--}}
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.css" />

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
                Booking
                <small>New</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('details.breadcrumbOne')</a></li>
                <li class="fa fa-edit active">New Booking</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Check Availability</h4>
                        </div>

                        <div id="errors"></div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Booking Date</label>

                                        {{--<input type="text" class="form-control" id="daterange" name="daterange" placeholder="" value="{{old('daterange')}}" readonly>--}}
                                        <div class="input-group input-daterange">
                                            <input type="text" class="form-control" id="dateFrom" name="dateFrom" {{--value="{{old('dateFrom')}}"--}} readonly>
                                            <div class="input-group-addon">to</div>
                                            <input type="text" class="form-control" id="dateTo" name="dateTo" {{--value="{{old('dateTo')}}"--}} readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(session()->has('sleeping_place'))
                                @if(session('sleeping_place') != 1)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No of beds</label>

                                                <select class="form-control" id="beds" name="beds">
                                                    <option value=""> Select no of beds </option>
                                                    @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                        <option value="{{$bedsDormsSleepKey}}" {{--@if(old('beds') == $bedsDormsSleepKey) selected="selected" @endif--}}> {{$noBedsDormsSleep}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>No of dorms</label>

                                                <select class="form-control" id="dorms" name="dorms">
                                                    <option value=""> Select no of dorms </option>
                                                    @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                        <option value="{{$bedsDormsSleepKey}}" {{--@if(old('dorms') == $bedsDormsSleepKey) selected="selected" @endif--}}> {{$noBedsDormsSleep}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>No of sleeps</label>

                                                <select class="form-control" id="sleeps" name="sleeps">
                                                    <option value=""> Select no of sleeps </option>
                                                    @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                        <option value="{{$bedsDormsSleepKey}}" {{--@if(old('sleeps') == $bedsDormsSleepKey) selected="selected" @endif--}}> {{$noBedsDormsSleep}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary pull-right" id="searchAvailability" name="searchAvailability" value="searchAvailability" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-fw fa-search"></i>Check Availability</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.col -->

                <form role="form" method="post" action="{{ route('cabinowner.store.booking') }}">
                    {{ csrf_field() }}
                    <div class="col-md-9">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h4 class="box-title">Create Booking</h4>
                            </div>

                            <div class="box-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                            <label>First Name <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" maxlength="100" value="{{old('firstname')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required>

                                            @if ($errors->has('firstname'))
                                                <span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                            <label>Last Name <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter last name" maxlength="100" value="{{old('lastname')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required>

                                            @if ($errors->has('lastname'))
                                                <span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                            <label>Street</label>

                                            <input type="text" class="form-control" id="street" name="street" placeholder="Enter street" maxlength="255" value="{{old('street')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('street'))
                                                <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label>City</label>

                                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter city" maxlength="255" value="{{old('city')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('city'))
                                                <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                                            <label>Country <span class="required">*</span></label>

                                            <select class="form-control select2" id="country" name="country" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required style="width: 100%;">
                                                <option value=""> Choose country </option>
                                                @if(isset($country))
                                                    @foreach($country as $land)
                                                        <option value="{{$land->name}}" @if(old('country') == $land->name) selected="selected" @endif> {{$land->name}} </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @if ($errors->has('country'))
                                                <span class="help-block"><strong>{{ $errors->first('country') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                            <label>Zip Code</label>

                                            <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter zip code" maxlength="25" value="{{old('zip')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('zip'))
                                                <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Halfboard</label>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="halfboard" name="halfboard" value="1" @if(old('halfboard') == '1') checked @endif @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>
                                                    Half board available
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                            <label>Email <span class="required">*</span></label>

                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" maxlength="255" value="{{old('email')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required>

                                            @if ($errors->has('zip'))
                                                <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                            <label>Mobile</label>

                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" maxlength="20" value="{{old('mobile')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('mobile'))
                                                <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                            <label>Phone</label>

                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" maxlength="20" value="{{old('phone')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('phone'))
                                                <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group {{ $errors->has('comments') ? ' has-error' : '' }}">
                                            <label>Comments </label>

                                            <textarea class="form-control" rows="3" id="comments" name="comments" placeholder="Comments ..." maxlength="300" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif> {{old('comments')}} </textarea>
                                            <div id="textarea_feedback"></div>

                                            @if ($errors->has('comments'))
                                                <span class="help-block"><strong>{{ $errors->first('comments') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" name="createBooking" value="createBooking" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled="disabled" @endif><i class="fa fa-fw fa-save"></i>Create Bookings</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.col -->

                </form>
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- Hotel Datepicker JS -->
    {{--<script type="text/javascript" src="{{ asset('plugins/hoteldatepicker/moment.js') }}"></script>--}}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.js"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    {{--<script type="text/javascript" src="{{ asset('plugins/hoteldatepicker/fecha.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/hoteldatepicker/hotel-datepicker.min.js') }}"></script>--}}

    <script>
        $(function(){

            /* Checking for the CSRF token */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            /* Initialize Select2 Elements */
            $(".select2").select2();

            /* Calendar booking availability begin */
            $("#dateFrom").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd.mm.yy',
                startDate: new Date()
            });

            /*$("#dateFrom").datepicker().on('show', function(e) {
                $.ajax({
                    url: '/cabinowner/check/availability/calendar',
                    dataType: 'JSON',
                    type: 'POST'
                })
                    .done(function( response ) {
                        $("#dateFrom").datepicker('setDatesDisabled', response.disableDates);
                    })
                    .fail(function(response, jqxhr, textStatus, error) {
                    });
            });

            $("#dateFrom").datepicker().on('changeMonth', function(e) {
                $.ajax({
                    url: '/cabinowner/check/availability/calendar',
                    dataType: 'JSON',
                    type: 'POST',
                    data: { date : moment(e.date).format('YY-MM-DD') }
                })
                    .done(function( response ) {
                        $("#dateFrom").datepicker('setDatesDisabled', response.disableDates);
                    })
                    .fail(function(response, jqxhr, textStatus, error) {
                    });
            });*/

            $("#dateFrom").datepicker().on('changeDate', function(e) {
                var temp   = $(this).datepicker('getDate');
                var start  = new Date(temp);
                start.setDate(start.getDate() + 1); // Here date is setting greater than start date

                var end    = new Date(start);
                end.setDate(end.getDate() + 60);

                $("#dateTo").datepicker({
                    autoclose: true,
                    format: 'dd.mm.yy',
                    startDate: start,
                    endDate: end
                });
            });
            /* Calendar booking availability end */

            /* Character limit for comments begin */
            var text_max = 300;
            $('#textarea_feedback').css('color', 'red');
            $('#textarea_feedback').html(text_max + ' characters remaining');

            $('#comments').keyup(function() {
                var text_length = $('#comments').val().length;
                var text_remaining = text_max - text_length;

                $('#textarea_feedback').html(text_remaining + ' characters remaining');
            });
            /* Character limit for comments end */

            /* Booking availability begins */
            $("#searchAvailability").on('click', function(e){
                e.preventDefault();
                var dateFrom  = $('#dateFrom').val();
                var dateTo    = $('#dateTo').val();
                //var dateFrom  = '03.09.17'; //sat
                //var dateTo    = '05.09.17'; //tue
                var beds      = $('#beds').val();
                var dorms     = $('#dorms').val();
                var sleeps    = $('#sleeps').val();
                var search    = $(this).val();
                var $btn      = $(this).button('loading');
                $.ajax({
                    url: '/cabinowner/check/availability',
                    dataType: 'JSON',
                    type: 'POST',
                    data: { dateFrom: dateFrom, dateTo: dateTo, beds: beds, dorms: dorms, sleeps: sleeps, search: search }
                })
                    .done(function( response ) {
                        //console.log(response.disableDates);
                        if(response.available === 'success') {
                            $( '#errors' ).hide();
                            $btn.button('reset');
                            $(":input:not([name=dateFrom], [name=dateTo], [name=beds], [name=dorms], [name=sleeps])").prop('disabled', false);
                        }

                    })
                    .fail(function(response, jqxhr, textStatus, error) {
                        $btn.button('reset');
                        if( response.status === 422 ) {
                            $( '#errors' ).show();
                            var errors = response.responseJSON;
                            errorsHtml = '<div class="alert alert-danger"><ul>';
                            $.each( errors , function( key, value ) {
                                errorsHtml += '<li>' + value + '</li>';
                            });
                            errorsHtml += '</ul></div>';
                            $( '#errors' ).html( errorsHtml );
                        }
                        if(!(response.available === 'success')) {
                            $(":input:not([name=dateFrom], [name=dateTo], [name=beds], [name=dorms], [name=sleeps])").prop('disabled', true);
                        }
                    });
            });
            /* Search availability ends */
        });
    </script>
@endsection
