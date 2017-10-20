@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Winter Opening & Closing Time Edit')

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

                        @isset($winterSeason)
                            <div class="box-body">
                                <div class="row">
                                    <!-- Div for summer season -->
                                    <div class="col-md-12">
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h4>@lang('openingClosingSeason.winterSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 31.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></i>
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

                                            <form role="form" method="post" action="{{ route('cabinowner.winter.season.update') }}">
                                                {{ csrf_field() }}
                                                <div class="box-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('winterSeasonYear') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.winterSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="winterSeasonYear" id="winterSeasonYear">
                                                                    <option value="0">@lang('openingClosingSeason.winterSeasonChooseSeasonSelect')</option>
                                                                    <option value="2017" @if($winterSeason->winterSeasonYear == 2017 || old('winterSeason') == '2017') selected="selected" @endif>2017</option>
                                                                    <option value="2018" @if($winterSeason->winterSeasonYear == 2018 || old('winterSeason') == '2018') selected="selected" @endif>2018</option>
                                                                    <option value="2019" @if($winterSeason->winterSeasonYear == 2019 || old('winterSeason') == '2019') selected="selected" @endif>2019</option>
                                                                    <option value="2020" @if($winterSeason->winterSeasonYear == 2020 || old('winterSeason') == '2020') selected="selected" @endif>2020</option>
                                                                    <option value="2021" @if($winterSeason->winterSeasonYear == 2021 || old('winterSeason') == '2021') selected="selected" @endif>2021</option>
                                                                </select>

                                                                @if ($errors->has('winterSeasonYear'))
                                                                    <span class="help-block"><strong>{{ $errors->first('winterSeasonYear') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('winterSeasonStatus') ? ' has-error' : '' }}">
                                                                <label>@lang('openingClosingSeason.winterSeasonChooseStatusLabel') <span class="required">*</span></label>

                                                                <select class="form-control" name="winterSeasonStatus">
                                                                    <option value="0">@lang('openingClosingSeason.winterSeasonChooseStatusSelect')</option>
                                                                    <option value="open" @if($winterSeason->winterSeasonStatus == 'open' || old('winterSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelOpen')</option>
                                                                    <option value="close" @if($winterSeason->winterSeasonStatus == 'close' || old('winterSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelClose')</option>
                                                                </select>

                                                                @if ($errors->has('winterSeasonStatus'))
                                                                    <span class="help-block"><strong>{{ $errors->first('winterSeasonStatus') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_winter_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_open">@lang('openingClosingSeason.winterSeasonEarliestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_winter_open" name="earliest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_open', $winterSeason->earliest_winter_open)}}">

                                                                @if ($errors->has('earliest_winter_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_open') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('earliest_winter_close') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_close">@lang('openingClosingSeason.winterSeasonEarliestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="earliest_winter_close" name="earliest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_close', $winterSeason->earliest_winter_close)}}">

                                                                @if ($errors->has('earliest_winter_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_winter_open') ? ' has-error' : '' }}">
                                                                <label for="earliest_winter_open">@lang('openingClosingSeason.winterSeasonLatestOpen') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_winter_open" name="latest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_open', $winterSeason->latest_winter_open)}}">

                                                                @if ($errors->has('latest_winter_open'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_open') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('latest_winter_close') ? ' has-error' : '' }}">
                                                                <label for="latest_winter_close">@lang('openingClosingSeason.winterSeasonLatestClose') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="latest_winter_close" name="latest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_close', $winterSeason->latest_winter_close)}}">

                                                                @if ($errors->has('latest_winter_close'))
                                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_close') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group {{ $errors->has('winter_next_season') ? ' has-error' : '' }}">
                                                                <label for="winter_next_season">@lang('openingClosingSeason.winterNextSeason') <span class="required">*</span></label>

                                                                <input type="text" class="form-control" id="winter_next_season" name="winter_next_season" placeholder="@lang('openingClosingSeason.winterNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('winter_next_season', $winterSeason->winter_next_season)}}">

                                                                @if ($errors->has('winter_next_season'))
                                                                    <span class="help-block"><strong>{{ $errors->first('winter_next_season') }}</strong></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="rule">@lang('openingClosingSeason.winterSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                                                                <div>
                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_mon" name="winter_mon" value="1" @if($winterSeason->winter_mon == 1 || old('winter_mon') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.mondayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_tue" name="winter_tue" value="1" @if($winterSeason->winter_tue == 1 || old('winter_tue') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.tuesdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_wed" name="winter_wed" value="1" @if($winterSeason->winter_wed == 1 || old('winter_wed') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.wednesdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_thu" name="winter_thu" value="1" @if($winterSeason->winter_thu == 1 || old('winter_thu') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.thursdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_fri" name="winter_fri" value="1" @if($winterSeason->winter_fri == 1 || old('winter_fri') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.fridayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_sat" name="winter_sat" value="1" @if($winterSeason->winter_sat == 1 || old('winter_sat') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.saturdayWinter')
                                                                    </label>

                                                                    <label for="regularCheckbox">
                                                                        <input type="checkbox" id="winter_sun" name="winter_sun" value="1" @if($winterSeason->winter_sun == 1 || old('winter_sun') == 1) checked @endif>
                                                                        @lang('openingClosingSeason.sundayWinter')
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
                                                            <input type="hidden" name="winterSeasonId" value="{{$winterSeason->_id}}">
                                                            <button type="button" class="btn btn-danger pull-left jquery-postback-winter" data-loading-text="Loading..." autocomplete="off" value="{{$winterSeason->_id}}"><i class="fa fa-fw fa-trash"></i>@lang('openingClosingSeason.deleteWinterButton')</button>
                                                            <button type="submit" class="btn btn-primary pull-right" name="updateWinterSeason" value="updateWinterSeason"><i class="fa fa-fw fa-save"></i>@lang('openingClosingSeason.updateWinterButton')</button>
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
            confirmDeleteWinterSeason: '{{ trans('openingClosingSeason.confirmDeleteWinterSeason') }}',
            deleteWinterSeasonSuccess: '{{ trans('openingClosingSeason.deleteWinterSeasonSuccess') }}'
        };
    </script>

    <!-- openCloseSeason Js -->
    <script src="{{ asset('js/openCloseSeason.js') }}"></script>
@endsection