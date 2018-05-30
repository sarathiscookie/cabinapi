@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Create new booking')

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
            <h1> @lang('cabinownerBooking.heading')

                <small> @lang('cabinownerBooking.headingsm')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('cabinownerBooking.breadcrumbOne')</a></li>
                <li class="fa fa-edit active"> @lang('cabinownerBooking.breadcrumbNewBooking')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title"> @lang('cabinownerBooking.checkAvailability')</h4>
                        </div>

                        <div id="errors"></div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> @lang('cabinownerBooking.bookingDate')</label>

                                        <div class="input-group input-daterange">
                                            <input type="text" class="form-control" id="dateFrom" name="dateFrom" readonly>
                                            <div class="input-group-addon">   @lang('cabinownerBooking.to')</div>
                                            <input type="text" class="form-control" id="dateTo" name="dateTo" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(session()->has('sleeping_place'))
                                @if(session('sleeping_place') != 1)
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>   @lang('cabinownerBooking.lblnoOfBeds')</label>

                                                <select class="form-control" id="beds" name="beds">
                                                    <option value="">   @lang('cabinownerBooking.lblnoOfBedsPH')</option>
                                                    @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                        <option value="{{$bedsDormsSleepKey}}" {{--@if(old('beds') == $bedsDormsSleepKey) selected="selected" @endif--}}> {{$noBedsDormsSleep}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>  @lang('cabinownerBooking.lblnoOfDorms')</label>

                                                <select class="form-control" id="dorms" name="dorms">
                                                    <option value="">   @lang('cabinownerBooking.lblnoOfDormsPH')</option>
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
                                                <label>   @lang('cabinownerBooking.lblnoOfSleeps')</label>

                                                <select class="form-control" id="sleeps" name="sleeps">
                                                    <option value="">   @lang('cabinownerBooking.lblnoOfSleepsPH') </option>
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
                                    <button class="btn btn-primary pull-right" id="searchAvailability" name="searchAvailability" value="searchAvailability" data-loading-text="Loading..." autocomplete="off"><i class="fa fa-fw fa-search"></i> @lang('cabinownerBooking.checkAvailability')</button>
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
                                <h4 class="box-title"> @lang('cabinownerBooking.lblcreateBooking')</h4>
                            </div>

                            <div class="box-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                            <label>  @lang('cabinownerBooking.firstName') <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" maxlength="100" value="{{old('firstname')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required>

                                            @if ($errors->has('firstname'))
                                                <span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                            <label>   @lang('cabinownerBooking.lastName')<span class="required">*</span></label>

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
                                            <label>   @lang('cabinownerBooking.street')</label>

                                            <input type="text" class="form-control" id="street" name="street" placeholder="Enter street" maxlength="255" value="{{old('street')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('street'))
                                                <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                            <label>   @lang('cabinownerBooking.city')</label>

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
                                            <label>   @lang('cabinownerBooking.country')<span class="required">*</span></label>

                                            <select class="form-control select2" id="country" name="country" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required style="width: 100%;">
                                                <option value="">   @lang('cabinownerBooking.countryPH') </option>
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
                                            <label>   @lang('cabinownerBooking.Zip')</label>

                                            <input type="text" class="form-control" id="zip" name="zip" placeholder="Enter zip code" maxlength="25" value="{{old('zip')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>

                                            @if ($errors->has('zip'))
                                                <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (session()->has('halfboard') && session()->has('halfboard_price'))
                                        @if(session('halfboard') == '1')
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>  @lang('cabinownerBooking.halfBoard')</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="halfboard" name="halfboard" value="1" @if(old('halfboard') == '1') checked @endif @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>
                                                           @lang('cabinownerBooking.halfBoardAvail')
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                            <label>   @lang('cabinownerBooking.email') <span class="required">*</span></label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" maxlength="255" value="{{old('email')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif required>
                                             @if ($errors->has('zip'))
                                                <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                            <label>   @lang('cabinownerBooking.mobile')</label>
                                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Enter mobile" maxlength="20" value="{{old('mobile')}}" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled @endif>
                                            @if ($errors->has('mobile'))
                                                <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                            <label>   @lang('cabinownerBooking.telephone')</label>

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
                                            <label>   @lang('cabinownerBooking.comments')  </label>

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
                                        <button type="submit" class="btn btn-primary pull-right" name="createBooking" value="createBooking" @if( (session()->has('availableSuccess')) && (session('availableSuccess') === 'success') ) disabled="" @else disabled="disabled" @endif><i class="fa fa-fw fa-save"></i>  @lang('cabinownerBooking.btnCreateBooking')</button>
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

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

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
            var array = <?php echo json_encode($disableDates);?>;
            var start_date = '';

            $("#dateFrom").datepicker({
                dateFormat: "dd.mm.y",
                monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
                dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
                minDate: 0,
                onSelect: function (date) {
                    var dt2       = $('#dateTo');
                    var startDate = $(this).datepicker('getDate');
                    var minDate   = $(this).datepicker('getDate');
                    $(this).datepicker('option', 'minDate', minDate);
                    dt2.datepicker('setDate', minDate);
                    startDate.setDate(startDate.getDate() + 60); //sets dt2 maxDate to the last day of 60 days window
                    minDate.setDate(minDate.getDate() + 1); //sets dt2 minDate to the +1 day of from date
                    dt2.datepicker('option', 'maxDate', startDate);
                    dt2.datepicker('option', 'minDate', minDate);
                },
                onChangeMonthYear: function(year,month,inst) {
                    if (year != undefined && month != undefined) {
                        start_date = year +'-';
                        start_date += month +'-';
                        start_date += '01';
                    }

                    $.ajax({
                        url: '/cabinowner/check/availability/calendar',
                        dataType: 'JSON',
                        type: 'POST',
                        async: false,
                        data: { dateFrom: start_date },
                        success: function (response) {
                            array = response.disableDates;
                        },
                        error: function (err) {
                            alert(JSON.stringify(err));
                        }
                    });
                },
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    return [array.indexOf(string) == -1]
                }
            });

            $('#dateTo').datepicker({
                dateFormat: "dd.mm.y",
                monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
                dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
                onChangeMonthYear: function(year,month,inst) {
                    if (year != undefined && month != undefined) {
                        start_date = year +'-';
                        start_date += month +'-';
                        start_date += '01';
                    }

                    $.ajax({
                        url: '/cabinowner/check/availability/calendar',
                        dataType: 'JSON',
                        type: 'POST',
                        async: false,
                        data: { dateFrom: start_date },
                        success: function (response) {
                            array = response.disableDates;
                        },
                        error: function (err) {
                            alert(JSON.stringify(err));
                        }
                    });
                },
                beforeShowDay: function (date) {
                    var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                    return [array.indexOf(string) == -1]
                }
            });
            /* Calendar booking availability end */

            /* Character limit for comments begin */
            var text_max = 300;
            $('#textarea_feedback').css('color', 'red');
            $('#textarea_feedback').html(text_max + "  @lang('cabinownerBooking.charactersRemaining')   ");

            $('#comments').keyup(function() {
                var text_length = $('#comments').val().length;
                var text_remaining = text_max - text_length;

                $('#textarea_feedback').html(text_remaining + "  @lang('cabinownerBooking.charactersRemaining')   ");
            });
            /* Character limit for comments end */

            /* Booking availability begins */
            $("#searchAvailability").on('click', function(e){
                e.preventDefault();
                var dateFrom  = $('#dateFrom').val();
                var dateTo    = $('#dateTo').val();
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
