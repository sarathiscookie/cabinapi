@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Cabin Information update')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Details
                <small>Information</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li><a href="/cabinowner/details"><i class="fa fa fa-table"></i> Details</a></li>
                <li class="fa fa-edit active">Contact</li>
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
                                Contact Information
                            </h4>
                        </div>
                        <!-- /.box-header -->

                        @isset($userDetails)
                            <div class="box-body">
                                <div class="row">
                                    <!-- Div for summer season -->
                                    <div class="col-md-12">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h4>User Details</h4>
                                            </div>

                                            @if (session('failure'))
                                                <div class="alert alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('failure') }}
                                                </div>
                                            @endif

                                            <div class="statusResponse"></div>

                                            <form role="form" method="post" action="">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                                                <label>First Name <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="{{old('firstname', $userDetails->usrFirstname)}}">

                                                                @if ($errors->has('firstname'))
                                                                    <span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                                                <label>Last Name <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="{{old('lastname', $userDetails->usrLastname)}}">

                                                                @if ($errors->has('lastname'))
                                                                    <span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                                <label>E-mail <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email', $userDetails->usrEmail)}}">

                                                                @if ($errors->has('email'))
                                                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                                                                <label>Telephone <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Telephone" value="{{old('telephone', $userDetails->usrTelephone)}}">

                                                                @if ($errors->has('telephone'))
                                                                    <span class="help-block"><strong>{{ $errors->first('telephone') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                                                <label>Zip Code <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="Zip Code" value="{{old('zip', $userDetails->usrZip)}}">

                                                                @if ($errors->has('zip'))
                                                                    <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                                                <label>City <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="{{old('city', $userDetails->usrFirstname)}}">

                                                                @if ($errors->has('city'))
                                                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('addresses') ? ' has-error' : '' }}">
                                                                <label>Addresses <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="addresses" name="addresses" placeholder="Addresses" value="{{old('addresses', $userDetails->usrAddress)}}">

                                                                @if ($errors->has('addresses'))
                                                                    <span class="help-block"><strong>{{ $errors->first('addresses') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                                                                <label>First Name <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="country" name="country" placeholder="Country" value="{{old('country', $userDetails->usrCountry)}}">

                                                                @if ($errors->has('country'))
                                                                    <span class="help-block"><strong>{{ $errors->first('country') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->

                                                <div class="box-footer">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <button type="submit" class="btn btn-primary pull-right" name="updateSummerSeason" value="updateSummerSeason"><i class="fa fa-fw fa-save"></i>Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-footer -->

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        @endisset
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection