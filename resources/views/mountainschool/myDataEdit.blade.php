@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Edit My Data')


@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <style type="text/css">    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('tours.mdheading')
                <small>@lang('tours.mdsmallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a></li>             
                <li class="fa fa-edit active">@lang('tours.breadcrumbMD')</li>
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
                                @lang('tours.mdBoxHeading')
                            </h4>
                        </div>


                        @if (session('failure'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('failure') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- /.box-header -->

                        @isset($userDetails)
                            <form role="form" method="post" action="{{ route('mountainschool.mydata.update') }}"  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="box-body">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('usrMschoolName') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelMSName')</label>

                                                <input type="text" class="form-control" id="usrMschoolName" name="usrMschoolName" placeholder="@lang('tours.contactLabelMSNamePH')" value="{{old('usrMschoolName', $userDetails->usrMschoolName)}}" maxlength="255">

                                                @if ($errors->has('usrMschoolName'))
                                                    <span class="help-block"><strong>{{ $errors->first('usrMschoolName') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('firstname') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelFirstName') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="@lang('tours.contactLabelFirstNamePH')" value="{{old('firstname', $userDetails->usrFirstname)}}" maxlength="100">

                                                @if ($errors->has('firstname'))
                                                    <span class="help-block"><strong>{{ $errors->first('firstname') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelLastName') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="@lang('tours.contactLabelLastNamePH')" value="{{old('lastname', $userDetails->usrLastname)}}" maxlength="100">

                                                @if ($errors->has('lastname'))
                                                    <span class="help-block"><strong>{{ $errors->first('lastname') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelMobile')</label>

                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="@lang('tours.contactLabelMobilePH')" value="{{old('mobile', $userDetails->usrMobile)}}" maxlength="25">

                                                @if ($errors->has('mobile'))
                                                    <span class="help-block"><strong>{{ $errors->first('mobile') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('telephone') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelPhone') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="telephone" name="telephone" placeholder="@lang('tours.contactLabelPhonePH')" value="{{old('telephone', $userDetails->usrTelephone)}}" maxlength="25">

                                                @if ($errors->has('telephone'))
                                                    <span class="help-block"><strong>{{ $errors->first('telephone') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('zip') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelZip') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="zip" name="zip" placeholder="@lang('tours.contactLabelZipPH')" value="{{old('zip', $userDetails->usrZip)}}" maxlength="25">

                                                @if ($errors->has('zip'))
                                                    <span class="help-block"><strong>{{ $errors->first('zip') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelCity') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="city" name="city" placeholder="@lang('tours.contactLabelCityPH')" value="{{old('city', $userDetails->usrCity)}}" maxlength="255">

                                                @if ($errors->has('city'))
                                                    <span class="help-block"><strong>{{ $errors->first('city') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('street') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelStreet') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="street" name="street" placeholder="@lang('tours.contactLabelStreetPH')" value="{{old('street', $userDetails->usrAddress)}}" maxlength="255">

                                                @if ($errors->has('street'))
                                                    <span class="help-block"><strong>{{ $errors->first('street') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelCountry') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="country" name="country" placeholder="@lang('tours.contactLabelCountryPH')" value="{{old('country', $userDetails->usrCountry)}}" maxlength="255">

                                                @if ($errors->has('country'))
                                                    <span class="help-block"><strong>{{ $errors->first('country') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelEmail') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="email" name="email" placeholder="@lang('tours.contactLabelEmailPH')" value="{{old('email', $userDetails->usrEmail)}}" maxlength="255">

                                                @if ($errors->has('email'))
                                                    <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('birthDay') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelBirthDay') <span class="required">*</span></label>

                                                <input type="text" class="form-control daterange" id="birthDay" name="birthDay" placeholder="@lang('tours.contactLabelBirthDayPH')" value="{{old('birthDay', $userDetails->usrBirthday )}}" maxlength="255">

                                                @if ($errors->has('birthDay'))
                                                    <span class="help-block"><strong>{{ $errors->first('birthDay') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('usrLogo') ? ' has-error' : '' }}">
                                                <label>@lang('tours.contactLabelMSLogo')  </label>

                                                <input data-preview="#preview" name="usrLogo" type="file" id="usrLogo">

                                                <img src="{{ asset('images/mschool/'.$userDetails->usrLogo) }}" id="preview" width="100" height="100"  style="margin:-60px 165px 10px !important" >

                                                @if ($errors->has('usrLogo'))
                                                    <span class="help-block"><strong>{{ $errors->first('usrLogo') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <input type="hidden" name="udtId" value="{{$userDetails->_id }}"
                                       id="udtId">
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary pull-right" name="updateContact" value="updateContact"><i class="fa fa-fw fa-save"></i>@lang('tours.frmUpdateBtn')</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </form>
                        @endisset
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>

        /* Date range functionality begin */

            $(function() {
    $('input[name="birthDay"]').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
            autoUpdateInput: false,
            locale: {
                format: 'DD.MM.YYYY',
                cancelLabel: 'Löschen',
                applyLabel: 'Bestätigen'
            }
    }
     );

                $('input[name="birthDay"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD.MM.YYYY')  );
                });


    });
    </script>
@endsection