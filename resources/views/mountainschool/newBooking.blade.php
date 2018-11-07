@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: New Booking')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('mountainschool.nbHeading')
                <small>@lang('mountainschool.nbSubHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('mountainschool.breadcrumbOne')</a></li>
                <li class="active">@lang('mountainschool.breadcrumbNewBooking')</li>
            </ol>
        </section>

        @if(session()->has('successMsgSave'))
            <div id="flash" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session()->get('successMsgSave') }}
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <form role="form" method="post" id="tourBookingFrm" name="tourBookingFrm">
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('mountainschool.nbBoxHeading')  </h4>
                            </div>

                            <div class="box-body" id="tourbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tourname') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblTourName') <span class="required">*</span></label>
                                            <select class="form-control" id="tourname" name="tourname">
                                                <option value="">@lang('mountainschool.lblTourNamePH')</option>
                                                @if(isset($tourList))
                                                    @foreach($tourList as $key => $type)
                                                        @if($type->tour_name != '')
                                                            <option value="{{ $type->_id  }}">{{ $type->tour_name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block"><strong>  {{ $errors->first('tourname') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="cabindtls">
                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button style="display: none;" type="button" class="btn btn-primary pull-right" name="newBooking" id="newBooking" data-loading-text="loading..." value="newBooking"><i class="fa fa-fw fa-save"></i>@lang('tours.btnSave')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
