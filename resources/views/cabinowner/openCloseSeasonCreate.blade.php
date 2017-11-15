@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Opening & Closing Time Create')

@section('css')
    <!-- Date Range Picker -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
                @lang('openingClosingSeason.pageHeading')
                <small>@lang('openingClosingSeason.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li><a href="/cabinowner/season"><i class="fa fa-clock-o"></i>@lang('openingClosingSeason.breadcrumbTwo')</a></li>
                <li class="active">@lang('openingClosingSeason.formHeading')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <form role="form" method="post" action="{{ route('cabinowner.season.store') }}">
                            <div class="box-header with-border">
                                <h3 class="box-title">
                                    @lang('openingClosingSeason.formHeading')
                                </h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="col-md-12">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>@lang('openingClosingSeason.summerSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Sommersaison ist ca. im Zeitraum vom 01.05 – 31.10 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('summerSeasonYear') ? ' has-error' : '' }}">
                                                <label>@lang('openingClosingSeason.summerSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                <select class="form-control" name="summerSeasonYear" id="summerSeasonYear">
                                                    <option value="0">@lang('openingClosingSeason.summerSeasonChooseSeasonSelect')</option>
                                                    <?php
                                                    $firstYear = (int)date('Y');
                                                    $lastYear  = (int)date('Y', strtotime('+3 year'));
                                                    for($i = $firstYear; $i <= $lastYear; $i++)
                                                    {
                                                    ?>
                                                    <option value="{{$i}}" @if(old('summerSeasonYear') == $i) selected="selected" @endif>{{$i}}</option>
                                                    <?php
                                                    }
                                                    ?>
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
                                                    <option value="open" @if(old('summerSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelOpen')</option>
                                                    <option value="close" @if(old('summerSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.summerSeasonChooseStatusLabelClose')</option>
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

                                                <input type="text" class="form-control" id="earliest_summer_open" name="earliest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_open')}}">

                                                @if ($errors->has('earliest_summer_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('earliest_summer_close') ? ' has-error' : '' }}">
                                                <label for="earliest_summer_close">@lang('openingClosingSeason.summerSeasonEarliestClose') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_summer_close" name="earliest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_close')}}">

                                                @if ($errors->has('earliest_summer_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_summer_open') ? ' has-error' : '' }}">
                                                <label for="latest_summer_open">@lang('openingClosingSeason.summerSeasonLatestOpen') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_summer_open" name="latest_summer_open" placeholder="@lang('openingClosingSeason.summerSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_open')}}">

                                                @if ($errors->has('latest_summer_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_summer_close') ? ' has-error' : '' }}">
                                                <label for="latest_summer_close">@lang('openingClosingSeason.summerSeasonLatestClose') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_summer_close" name="latest_summer_close" placeholder="@lang('openingClosingSeason.summerSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_close')}}">

                                                @if ($errors->has('latest_summer_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('summer_next_season') ? ' has-error' : '' }}">
                                                <label for="summer_next_season">@lang('openingClosingSeason.summerNextSeason') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="summer_next_season" name="summer_next_season" placeholder="@lang('openingClosingSeason.summerNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('summer_next_season')}}">

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
                                                        <input type="checkbox" id="summer_mon" name="summer_mon" value="1" @if(old('summer_mon') == 1) checked @endif>
                                                        @lang('openingClosingSeason.monday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_tue" name="summer_tue" value="1" @if(old('summer_tue') == 1) checked @endif>
                                                        @lang('openingClosingSeason.tuesday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_wed" name="summer_wed" value="1" @if(old('summer_wed') == 1) checked @endif>
                                                        @lang('openingClosingSeason.wednesday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_thu" name="summer_thu" value="1" @if(old('summer_thu') == 1) checked @endif>
                                                        @lang('openingClosingSeason.thursday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_fri" name="summer_fri" value="1" @if(old('summer_fri') == 1) checked @endif>
                                                        @lang('openingClosingSeason.friday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_sat" name="summer_sat" value="1" @if(old('summer_sat') == 1) checked @endif>
                                                        @lang('openingClosingSeason.saturday')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_sun" name="summer_sun" value="1" @if(old('summer_sun') == 1) checked @endif>
                                                        @lang('openingClosingSeason.sunday')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <h4>@lang('openingClosingSeason.winterSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 30.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h4>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('winterSeasonYear') ? ' has-error' : '' }}">
                                                <label>@lang('openingClosingSeason.winterSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                <select class="form-control" name="winterSeasonYear" id="winterSeasonYear">
                                                    <option value="0">@lang('openingClosingSeason.winterSeasonChooseSeasonSelect')</option>
                                                    <?php
                                                        $firstYear = (int)date('Y');
                                                        $lastYear  = (int)date('Y', strtotime('+3 year'));
                                                        for($i = $firstYear; $i <= $lastYear; $i++)
                                                        {
                                                    ?>
                                                    <option value="{{$i}}" @if(old('winterSeasonYear') == $i) selected="selected" @endif>{{$i}}</option>
                                                    <?php
                                                        }
                                                    ?>
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
                                                    <option value="open" @if(old('winterSeasonStatus') == 'open') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelOpen')</option>
                                                    <option value="close" @if(old('winterSeasonStatus') == 'close') selected="selected" @endif>@lang('openingClosingSeason.winterSeasonChooseStatusLabelClose')</option>
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

                                                <input type="text" class="form-control" id="earliest_winter_open" name="earliest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_open')}}">

                                                @if ($errors->has('earliest_winter_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('earliest_winter_close') ? ' has-error' : '' }}">
                                                <label for="earliest_winter_close">@lang('openingClosingSeason.winterSeasonEarliestClose') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_winter_close" name="earliest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_close')}}">

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

                                                <input type="text" class="form-control" id="latest_winter_open" name="latest_winter_open" placeholder="@lang('openingClosingSeason.winterSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_open')}}">

                                                @if ($errors->has('latest_winter_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_winter_close') ? ' has-error' : '' }}">
                                                <label for="latest_winter_close">@lang('openingClosingSeason.winterSeasonLatestClose') <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_winter_close" name="latest_winter_close" placeholder="@lang('openingClosingSeason.winterSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_close')}}">

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

                                                <input type="text" class="form-control" id="winter_next_season" name="winter_next_season" placeholder="@lang('openingClosingSeason.winterNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('winter_next_season')}}">

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
                                                        <input type="checkbox" id="winter_mon" name="winter_mon" value="1" @if(old('winter_mon') == 1) checked @endif>
                                                        @lang('openingClosingSeason.mondayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_tue" name="winter_tue" value="1" @if(old('winter_tue') == 1) checked @endif>
                                                        @lang('openingClosingSeason.tuesdayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_wed" name="winter_wed" value="1" @if(old('winter_wed') == 1) checked @endif>
                                                        @lang('openingClosingSeason.wednesdayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_thu" name="winter_thu" value="1" @if(old('winter_thu') == 1) checked @endif>
                                                        @lang('openingClosingSeason.thursdayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_fri" name="winter_fri" value="1" @if(old('winter_fri') == 1) checked @endif>
                                                        @lang('openingClosingSeason.fridayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_sat" name="winter_sat" value="1" @if(old('winter_sat') == 1) checked @endif>
                                                        @lang('openingClosingSeason.saturdayWinter')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_sun" name="winter_sun" value="1" @if(old('winter_sun') == 1) checked @endif>
                                                        @lang('openingClosingSeason.sundayWinter')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" name="summerSeason" value="1">
                                        <input type="hidden" name="winterSeason" value="1">
                                        <button type="submit" class="btn btn-primary pull-right" name="storeSeason" value="storeSeason">@lang('openingClosingSeason.saveButton')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div>

            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <script>
        $(function(){
            /* Date picker functionality for summer season begin */
            $('#summerSeasonYear').on('change', function(){
                var summerSeasonYear     = $("#summerSeasonYear").val();
                if(summerSeasonYear != '0') {
                    // Set minDate and maxDate in calendar
                    var start            = new Date("May 01, "+summerSeasonYear);
                    var end              = new Date("October 31, "+summerSeasonYear);
                    var nextSummerStart  = new Date("May 01, "+(parseInt(summerSeasonYear)+1));
                    var nextSummerEnd    = new Date("October 31, "+(parseInt(summerSeasonYear)+1));

                    $('#earliest_summer_open').datepicker('option', 'minDate', start);
                    $('#earliest_summer_open').datepicker('option', 'maxDate', end);

                    $('#earliest_summer_close').datepicker('option', 'minDate', start);
                    $('#earliest_summer_close').datepicker('option', 'maxDate', end);

                    $('#latest_summer_open').datepicker('option', 'minDate', start);
                    $('#latest_summer_open').datepicker('option', 'maxDate', end);

                    $('#latest_summer_close').datepicker('option', 'minDate', start);
                    $('#latest_summer_close').datepicker('option', 'maxDate', end);

                    $('#summer_next_season').datepicker('option', 'minDate', nextSummerStart);
                    $('#summer_next_season').datepicker('option', 'maxDate', nextSummerEnd);
                }
            });

            $('#earliest_summer_open').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#earliest_summer_close').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#latest_summer_open').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#latest_summer_close').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#summer_next_season').datepicker({
                dateFormat: "dd.mm.yy"
            });
            /* Date picker functionality for summer season end */

            /* Date picker functionality for winter season begin */
            $('#winterSeasonYear').on('change', function(){

                var winterSeasonYear     = $("#winterSeasonYear").val();
                if(winterSeasonYear != '0') {
                    // Set minDate and maxDate in calendar
                    var start            = new Date("November 01, "+winterSeasonYear);
                    var end              = new Date("April 30, "+(parseInt(winterSeasonYear)+1));
                    var nextWinterStart  = new Date("November 01, "+(parseInt(winterSeasonYear)+1));
                    var nextWinterEnd    = new Date("April 30, "+(parseInt(winterSeasonYear)+2));

                    $('#earliest_winter_open').datepicker('option', 'minDate', start);
                    $('#earliest_winter_open').datepicker('option', 'maxDate', end);

                    $('#earliest_winter_close').datepicker('option', 'minDate', start);
                    $('#earliest_winter_close').datepicker('option', 'maxDate', end);

                    $('#latest_winter_open').datepicker('option', 'minDate', start);
                    $('#latest_winter_open').datepicker('option', 'maxDate', end);

                    $('#latest_winter_close').datepicker('option', 'minDate', start);
                    $('#latest_winter_close').datepicker('option', 'maxDate', end);

                    $('#winter_next_season').datepicker('option', 'minDate', nextWinterStart);
                    $('#winter_next_season').datepicker('option', 'maxDate', nextWinterEnd);
                }
            });

            $('#earliest_winter_open').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#earliest_winter_close').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#latest_winter_open').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#latest_winter_close').datepicker({
                dateFormat: "dd.mm.yy"
            });

            $('#winter_next_season').datepicker({
                dateFormat: "dd.mm.yy"
            });
            /* Date picker functionality for winter season end */
        });
    </script>
@endsection