@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Contingent')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <style type="text/css">
        .nounderline{
            text-decoration: none;
            cursor: pointer;
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
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">@lang('contingent.formHeading')</h3>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        @if(isset($cabin))
                            <form role="form">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>@lang('contingent.reservationType') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select reservation types"></i></label>
                                        <select class="form-control">
                                            <option>--- @lang('contingent.selectReservationType') ---</option>
                                            <option value="0" @if($cabin->sleeping_place == 0) selected="selected" @endif>@lang('contingent.reservationTypeBeds')</option>
                                            <option value="1" @if($cabin->sleeping_place == 1) selected="selected" @endif>@lang('contingent.reservationTypeSleeps')</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                    </div>
                                    <div class="form-group">
                                        <label>@lang('contingent.rulesLabel') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select rules."></i></label>
                                        <select class="form-control selectRules">
                                            <option value="0">--- @lang('contingent.selectRules') ---</option>
                                            <option value="1" @if($cabin->regular_status == 1) selected="selected" @endif>@lang('contingent.selectRulesRegular')</option>
                                            <option value="2" @if($cabin->regular_status == 2) selected="selected" @endif>@lang('contingent.selectRulesNotRegular')</option>
                                        </select>
                                    </div>

                                    <!-- Div for regular -->
                                    <div class="form-group regular">
                                        <!-- Monday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.monday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Tuesday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.tuesday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Wednesday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.wednesday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Thursday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.thursday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Friday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.friday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Saturday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.saturday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Sunday Div -->
                                        <div class="col-md-3">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.sunday')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- Div for not regular -->
                                    <div class="form-group notRegular">
                                        <div class="col-md-12">
                                            <div class="box box-default collapsed-box box-solid">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">@lang('contingent.notRegularLabel')</h3>

                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label>@lang('contingent.daterangepickerButtonLabel')</label>

                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </div>
                                                            <input type="text" class="form-control pull-right" id="daterange">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.noOfBedsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfBedsPlaceholder')" maxlength="10" value="{{$cabin->beds}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.noOfDormsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.noOfDormsPlaceholder')" maxlength="10" value="{{$cabin->dormitory}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.emergencyRoomsLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.emergencyRoomsPlaceholder')" maxlength="10" value="{{$cabin->makeshift}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="beds">@lang('contingent.inquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.inquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->inquiry_starts}}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dorms">@lang('contingent.mschoolInquiryGuestLabel')</label>
                                                        <input type="text" class="form-control" id="beds" placeholder="@lang('contingent.mschoolInquiryGuestPlaceholder')" maxlength="10" value="{{$cabin->ms_inquiry_starts}}">
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary pull-right">Update</button>
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
                <div class="col-md-3"></div>
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