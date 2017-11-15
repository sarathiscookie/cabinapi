@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Create new booking')

@section('css')
    <!-- Hotel Datepicker CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/hoteldatepicker/hotel-datepicker.css') }}" />

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

                        <form role="form" method="post" action="{{ route('cabinowner.bookings.availability') }}">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group {{ $errors->has('bookingDate') ? ' has-error' : '' }}">
                                            <label>Booking Date</label>

                                            <input type="text" class="form-control" id="bookingDate" name="bookingDate" placeholder="" value="{{old('bookingDate')}}" maxlength="30" readonly>

                                            @if ($errors->has('bookingDate'))
                                                <span class="help-block"><strong>{{ $errors->first('bookingDate') }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(session()->has('sleeping_place'))
                                    @if(session('sleeping_place') != 1)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                                    <label>No of beds</label>

                                                    <select class="form-control" id="beds" name="beds">
                                                        <option value=""> Select no of beds </option>
                                                        @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                            <option value="{{$bedsDormsSleepKey}}" @if(old('beds') == $bedsDormsSleepKey) selected="selected" @endif> {{$noBedsDormsSleep}} </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('beds'))
                                                        <span class="help-block"><strong>{{ $errors->first('beds') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group {{ $errors->has('dorms') ? ' has-error' : '' }}">
                                                    <label>No of dorms</label>

                                                    <select class="form-control" id="dorms" name="dorms">
                                                        <option value=""> Select no of dorms </option>
                                                        @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                            <option value="{{$bedsDormsSleepKey}}" @if(old('dorms') == $bedsDormsSleepKey) selected="selected" @endif> {{$noBedsDormsSleep}} </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('dorms'))
                                                        <span class="help-block"><strong>{{ $errors->first('dorms') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                                    <label>No of sleeps</label>

                                                    <select class="form-control" id="sleeps" name="sleeps">
                                                        <option value=""> Select no of sleeps </option>
                                                        @foreach($noBedsDormsSleeps as $bedsDormsSleepKey => $noBedsDormsSleep)
                                                            <option value="{{$bedsDormsSleepKey}}" @if(old('sleeps') == $bedsDormsSleepKey) selected="selected" @endif> {{$noBedsDormsSleep}} </option>
                                                        @endforeach
                                                    </select>

                                                    @if ($errors->has('sleeps'))
                                                        <span class="help-block"><strong>{{ $errors->first('sleeps') }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" name="searchAvailability" value="searchAvailability"><i class="fa fa-fw fa-search"></i>Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Create Booking</h4>
                        </div>

                        <form role="form" method="post" action="">
                            {{ csrf_field() }}
                            <div class="box-body">


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Street <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Zip Code <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Country <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Mobile <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Phone <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" name="updateCabin" value="updateCabin"><i class="fa fa-fw fa-save"></i>Create Bookings</button>
                                    </div>
                                </div>
                            </div>
                        </form>

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
    <!-- Hotel Datepicker JS -->
    <script type="text/javascript" src="{{ asset('plugins/hoteldatepicker/hotel-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/hoteldatepicker/fecha.min.js') }}"></script>

    <script>
        var input = document.getElementById('bookingDate');
        var datepicker = new HotelDatepicker(input, {
            maxNights: 60,
            format: 'DD.MM.YY'
        });

        $('#close-bookingDate').on('click', function(e){
            e.preventDefault();
        });
    </script>
@endsection
