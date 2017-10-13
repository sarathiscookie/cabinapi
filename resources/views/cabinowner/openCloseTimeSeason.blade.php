@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Opening & Closing Time')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datepicker/datepicker3.css') }}" />
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
                @lang('openingClosingSeason.pageHeading')
                <small>@lang('openingClosingSeason.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li><i class="fa fa-edit"></i> @lang('openingClosingSeason.breadcrumbOne')</li>
                <li class="active">@lang('openingClosingSeason.breadcrumbTwo')</li>
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
                            <h3 class="box-title">
                                @lang('openingClosingSeason.formHeading')
                            </h3>
                        </div>
                        <!-- /.box-header -->

                        @isset($cabin)
                            <div class="box-body">
                                <div class="row">
                                    <!-- Div for summer season -->
                                    <div class="col-md-6">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    @lang('openingClosingSeason.summerSeasonHeading')
                                                    <small>(01.05.{{ date("y")}} - 31.10.{{ date("y")}})</small>
                                                </h3>
                                            </div>

                                            @if (session('successSummer'))
                                                <div class="alert alert-success">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('successSummer') }}
                                                </div>
                                            @endif

                                            @if (session('failureSummer'))
                                                <div class="alert alert-warning">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('failureSummer') }}
                                                </div>
                                            @endif

                                            <form role="form" method="post" action="{{ route('cabinowner.summer.season.update') }}">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('summerSeason') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.summerSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="summerSeason">
                                                                    <option value="0">@lang('openingClosingSeason.summerSeasonChooseSeasonSelect')</option>
                                                                    <option value="2017" @if($cabin->summerSeason == '2017' || old('summerSeason') == '2017') selected="selected" @endif>2017</option>
                                                                    <option value="2018" @if($cabin->summerSeason == '2018' || old('summerSeason') == '2018') selected="selected" @endif>2018</option>
                                                                    <option value="2019" @if($cabin->summerSeason == '2019' || old('summerSeason') == '2019') selected="selected" @endif>2019</option>
                                                                    <option value="2020" @if($cabin->summerSeason == '2020' || old('summerSeason') == '2020') selected="selected" @endif>2020</option>
                                                                    <option value="2021" @if($cabin->summerSeason == '2021' || old('summerSeason') == '2021') selected="selected" @endif>2021</option>
                                                                </select>

                                                                @if ($errors->has('summerSeason'))
                                                                    <span class="help-block"><strong>{{ $errors->first('summerSeason') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('summerSeasonStatus') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.summerSeasonChooseStatusLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="summerSeasonStatus">
                                                                    <option value="0">@lang('openingClosingSeason.summerSeasonChooseStatusSelect')</option>
                                                                    <option value="open" @if($cabin->summerSeasonStatus == 'open' || old('summerSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelOpen')</option>
                                                                    <option value="close" @if($cabin->summerSeasonStatus == 'close' || old('summerSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelClose')</option>
                                                                </select>

                                                                @if ($errors->has('summerSeasonStatus'))
                                                                    <span class="help-block"><strong>{{ $errors->first('summerSeasonStatus') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>@lang('openingClosingSeason.summerSeasonSmallHeadingEditSeason') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Edit Season"></i></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_summer_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_open">@lang('openingClosingSeason.summerSeasonEarliestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_summer_open" name="earliest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_open', $cabin->earliest_summer_open)}}">

                                                                @if ($errors->has('earliest_summer_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_open') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('earliest_summer_close') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_close">@lang('openingClosingSeason.summerSeasonEarliestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_summer_close" name="earliest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_close', $cabin->earliest_summer_close)}}">

                                                                @if ($errors->has('earliest_summer_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_summer_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_open">@lang('openingClosingSeason.summerSeasonLatestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_summer_open" name="latest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_open', $cabin->latest_summer_open)}}">

                                                                @if ($errors->has('latest_summer_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_open') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('latest_summer_close') ? ' has-error' : '' }}">
                                                                <label for="latest_summer_close">@lang('openingClosingSeason.summerSeasonLatestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_summer_close" name="latest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_close', $cabin->latest_summer_close)}}">

                                                                @if ($errors->has('latest_summer_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="rule">@lang('openingClosingSeason.summerSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select holiday"></i></label>
                                                                <div>
                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_mon" name="summer_mon" value="1" @if($cabin->summer_mon == 1 || old('summer_mon') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.monday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_tue" name="summer_tue" value="1" @if($cabin->summer_tue == 1 || old('summer_tue') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.tuesday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_wed" name="summer_wed" value="1" @if($cabin->summer_wed == 1 || old('summer_wed') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.wednesday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_thu" name="summer_thu" value="1" @if($cabin->summer_thu == 1 || old('summer_thu') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.thursday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_fri" name="summer_fri" value="1" @if($cabin->summer_fri == 1 || old('summer_fri') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.friday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_sat" name="summer_sat" value="1" @if($cabin->summer_sat == 1 || old('summer_sat') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.saturday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_sun" name="summer_sun" value="1" @if($cabin->summer_sun == 1 || old('summer_sun') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.sunday')
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <input type="hidden" name="season" value="summer">
                                                    <button type="submit" class="btn btn-primary pull-right">@lang('openingClosingSeason.updateSummerButton')</button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                    <!-- Div for winter season -->
                                    <div class="col-md-6">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">
                                                    @lang('openingClosingSeason.winterSeasonHeading')
                                                    <small>(01.11.{{ date("y")}} - 31.04.{{date("y")}})</small>
                                                </h3>
                                            </div>

                                            @if (session('successWinter'))
                                                <div class="alert alert-success">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('successWinter') }}
                                                </div>
                                            @endif

                                            @if (session('failureWinter'))
                                                <div class="alert alert-warning">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('failureWinter') }}
                                                </div>
                                            @endif

                                            <form role="form" method="post" action="{{ route('cabinowner.winter.season.update') }}">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('winterSeason') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.winterSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="winterSeason">
                                                                    <option value="0">@lang('openingClosingSeason.winterSeasonChooseSeasonSelect')</option>
                                                                    <option value="2017" @if($cabin->winterSeason == '2017' || old('winterSeason') == '2017') selected="selected" @endif>2017</option>
                                                                    <option value="2018" @if($cabin->winterSeason == '2018' || old('winterSeason') == '2018') selected="selected" @endif>2018</option>
                                                                    <option value="2019" @if($cabin->winterSeason == '2019' || old('winterSeason') == '2019') selected="selected" @endif>2019</option>
                                                                    <option value="2020" @if($cabin->winterSeason == '2020' || old('winterSeason') == '2020') selected="selected" @endif>2020</option>
                                                                    <option value="2021" @if($cabin->winterSeason == '2021' || old('winterSeason') == '2021') selected="selected" @endif>2021</option>
                                                                </select>

                                                                @if ($errors->has('winterSeason'))
                                                                    <span class="help-block"><strong>{{ $errors->first('winterSeason') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('winterSeasonStatus') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.winterSeasonChooseStatusLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="winterSeasonStatus">
                                                                    <option value="0">@lang('openingClosingSeason.winterSeasonChooseStatusSelect')</option>
                                                                    <option value="open" @if($cabin->winterSeasonStatus == 'open' || old('winterSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelOpen')</option>
                                                                    <option value="close" @if($cabin->winterSeasonStatus == 'close' || old('winterSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelClose')</option>
                                                                </select>

                                                                @if ($errors->has('winterSeasonStatus'))
                                                                    <span class="help-block"><strong>{{ $errors->first('winterSeasonStatus') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>@lang('openingClosingSeason.winterSeasonSmallHeadingEditSeason') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Edit Season"></i></label>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_winter_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_open">@lang('openingClosingSeason.winterSeasonEarliestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_winter_open" name="earliest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_open', $cabin->earliest_winter_open)}}">

                                                                @if ($errors->has('earliest_winter_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_open') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('earliest_winter_close') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_close">@lang('openingClosingSeason.winterSeasonEarliestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_winter_close" name="earliest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_close', $cabin->earliest_winter_close)}}">

                                                                @if ($errors->has('earliest_winter_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_winter_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_open">@lang('openingClosingSeason.winterSeasonLatestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_winter_open" name="latest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_open', $cabin->latest_winter_open)}}">

                                                                @if ($errors->has('latest_winter_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_open') }}</strong></span>
                                                                @endif
                                                            </div>

                                                            <div class="form-group {{ $errors->has('latest_winter_close') ? ' has-error' : '' }}">
                                                                <label for="latest_winter_close">@lang('openingClosingSeason.winterSeasonLatestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_winter_close" name="latest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_close', $cabin->latest_winter_close)}}">

                                                                @if ($errors->has('latest_winter_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="rule">@lang('openingClosingSeason.winterSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Select holiday"></i></label>
                                                                <div>
                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_mon" name="winter_mon" value="1" @if($cabin->winter_mon == 1 || old('winter_mon') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.mondayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_tue" name="winter_tue" value="1" @if($cabin->winter_tue == 1 || old('winter_tue') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.tuesdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_wed" name="winter_wed" value="1" @if($cabin->winter_wed == 1 || old('winter_wed') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.wednesdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_thu" name="winter_thu" value="1" @if($cabin->winter_thu == 1 || old('winter_thu') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.thursdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_fri" name="winter_fri" value="1" @if($cabin->winter_fri == 1 || old('winter_fri') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.fridayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_sat" name="winter_sat" value="1" @if($cabin->winter_sat == 1 || old('winter_sat') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.saturdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_sun" name="winter_sun" value="1" @if($cabin->winter_sun == 1 || old('winter_sun') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.sundayWinter')
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="box-footer">
                                                    <input type="hidden" name="season" value="winter">
                                                    <button type="submit" class="btn btn-primary pull-right">@lang('openingClosingSeason.updateWinterButton')</button>
                                                </div>

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

@section('scripts')

    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Contingent Js -->
    <script src="{{ asset('js/openCloseSeason.js') }}"></script>
@endsection