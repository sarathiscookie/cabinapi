@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Summer Opening & Closing Time Edit')

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
                <li><a href="/cabinowner/season"><i class="fa fa-edit"></i> @lang('openingClosingSeason.breadcrumbOne')</a></li>
                <li class="active">@lang('openingClosingSeason.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('openingClosingSeason.formHeading')
                            </h3>
                        </div>
                        <!-- /.box-header -->

                        @isset($summerSeason)
                            <div class="box-body">
                                <div class="row">
                                    <!-- Div for summer season -->
                                    <div class="col-md-12">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h4>@lang('openingClosingSeason.summerSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Sommersaison ist ca. im Zeitraum vom 01.05 – 31.10 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i>
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

                                            <div class="statusResponse"></div>

                                            <form role="form" method="post" action="{{ route('cabinowner.summer.season.update') }}">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('summerSeasonYear') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.summerSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="summerSeasonYear" id="summerSeasonYear">
                                                                    <option value="0">@lang('openingClosingSeason.summerSeasonChooseSeasonSelect')</option>
                                                                    <option value="2017" @if($summerSeason->summerSeasonYear == 2017 || old('summerSeason') == '2017') selected="selected" @endif>2017</option>
                                                                    <option value="2018" @if($summerSeason->summerSeasonYear == 2018 || old('summerSeason') == '2018') selected="selected" @endif>2018</option>
                                                                    <option value="2019" @if($summerSeason->summerSeasonYear == 2019 || old('summerSeason') == '2019') selected="selected" @endif>2019</option>
                                                                    <option value="2020" @if($summerSeason->summerSeasonYear == 2020 || old('summerSeason') == '2020') selected="selected" @endif>2020</option>
                                                                    <option value="2021" @if($summerSeason->summerSeasonYear == 2021 || old('summerSeason') == '2021') selected="selected" @endif>2021</option>
                                                                </select>

                                                                @if ($errors->has('summerSeasonYear'))
                                                                    <span class="help-block"><strong>{{ $errors->first('summerSeasonYear') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('summerSeasonStatus') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.summerSeasonChooseStatusLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="summerSeasonStatus">
                                                                    <option value="0">@lang('openingClosingSeason.summerSeasonChooseStatusSelect')</option>
                                                                    <option value="open" @if($summerSeason->summerSeasonStatus == 'open' || old('summerSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelOpen')</option>
                                                                    <option value="close" @if($summerSeason->summerSeasonStatus == 'close' || old('summerSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelClose')</option>
                                                                </select>

                                                                @if ($errors->has('summerSeasonStatus'))
                                                                    <span class="help-block"><strong>{{ $errors->first('summerSeasonStatus') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_summer_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_open">@lang('openingClosingSeason.summerSeasonEarliestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_summer_open" name="earliest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_open', ($summerSeason->earliest_summer_open)->format('d.m.y'))}}">

                                                                @if ($errors->has('earliest_summer_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_open') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_summer_close') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_close">@lang('openingClosingSeason.summerSeasonEarliestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_summer_close" name="earliest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_close', ($summerSeason->earliest_summer_close)->format('d.m.y'))}}">

                                                                @if ($errors->has('earliest_summer_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_summer_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_open">@lang('openingClosingSeason.summerSeasonLatestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_summer_open" name="latest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_open', ($summerSeason->latest_summer_open)->format('d.m.y'))}}">

                                                                @if ($errors->has('latest_summer_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_open') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_summer_close') ? ' has-error' : '' }}">
                                                                <label for="latest_summer_close">@lang('openingClosingSeason.summerSeasonLatestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_summer_close" name="latest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_close', ($summerSeason->latest_summer_close)->format('d.m.y'))}}">

                                                                @if ($errors->has('latest_summer_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('summer_next_season') ? ' has-error' : '' }}">
                                                                <label for="earliest_summer_open">@lang('openingClosingSeason.summerNextSeason') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="summer_next_season" name="summer_next_season" placeholder="@lang('openingClosingSeason.summerNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('summer_next_season', $summerSeason->summer_next_season->format('d.m.y'))}}">

                                                                @if ($errors->has('summer_next_season'))
                                                                    <span class="help-block"><strong>{{ $errors->first('summer_next_season') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rule">@lang('openingClosingSeason.summerSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                                                                <div>
                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_mon" name="summer_mon" value="1" @if($summerSeason->summer_mon == 1 || old('summer_mon') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.monday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_tue" name="summer_tue" value="1" @if($summerSeason->summer_tue == 1 || old('summer_tue') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.tuesday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_wed" name="summer_wed" value="1" @if($summerSeason->summer_wed == 1 || old('summer_wed') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.wednesday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_thu" name="summer_thu" value="1" @if($summerSeason->summer_thu == 1 || old('summer_thu') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.thursday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_fri" name="summer_fri" value="1" @if($summerSeason->summer_fri == 1 || old('summer_fri') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.friday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_sat" name="summer_sat" value="1" @if($summerSeason->summer_sat == 1 || old('summer_sat') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.saturday')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="summer_sun" name="summer_sun" value="1" @if($summerSeason->summer_sun == 1 || old('summer_sun') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.sunday')
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->

                                                <div class="box-footer">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="hidden" name="summerSeasonId" value="{{$summerSeason->_id}}">
                                                            <button type="button" class="btn btn-danger pull-left jquery-postback-summer" data-loading-text="Loading..." autocomplete="off" value="{{$summerSeason->_id}}"><i class="fa fa-fw fa-trash"></i>@lang('openingClosingSeason.deleteSummerButton')</button>
                                                            <button type="submit" class="btn btn-primary pull-right" name="updateSummerSeason" value="updateSummerSeason"><i class="fa fa-fw fa-save"></i>@lang('openingClosingSeason.updateSummerButton')</button>
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

@section('scripts')

    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            confirmDeleteSummerSeason: '{{ trans('openingClosingSeason.confirmDeleteSummerSeason') }}',
            deleteSummerSeasonSuccess: '{{ trans('openingClosingSeason.deleteSummerSeasonSuccess') }}'
        };
    </script>

    <!-- openCloseSeason Js -->
    <script src="{{ asset('js/openCloseSeason.js') }}"></script>
@endsection