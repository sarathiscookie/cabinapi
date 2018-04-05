
<form role="form" method="post" action="{{ route('admin.cabinlite.season.store') }}" id="frmseasoncreate">

<div class="col-md-12">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">
            <h4>@lang('cabins.summerSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Sommersaison ist ca. im Zeitraum vom 01.05 – 31.10 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('summerSeasonYear') ? ' has-error' : '' }}">
                <label>@lang('cabins.summerSeasonChooseSeasonLabel') <span class="required">*</span></label>

                <select class="form-control" name="summerSeasonYear" id="summerSeasonYear">
                    <option value="">@lang('cabins.summerSeasonChooseSeasonSelect')</option>
                    <?php
                    $firstYear = (int)date('Y');
                    $lastYear  = (int)date('Y', strtotime('+2 year'));
                    for($i = $firstYear; $i <= $lastYear; $i++)
                    {
                    ?>
                    <option value="{{$i}}" @if(old('summerSeasonYear') == $i) selected="selected" @endif>{{$i}}</option>
                    <?php
                    }
                    ?>
                </select>
               @if($errors->has('summerSeasonYear'))
                    <span class="help-block"><strong>{{ $errors->first('summerSeasonYear') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('summerSeasonStatus') ? ' has-error' : '' }}">
                <label>@lang('cabins.summerSeasonChooseStatusLabel') <span class="required">*</span></label>

                <select class="form-control" name="summerSeasonStatus">
                    <option value="0">@lang('cabins.summerSeasonChooseStatusSelect')</option>
                    <option value="open" @if(old('summerSeasonStatus') == 'open') selected="selected" @endif>@lang('cabins.summerSeasonChooseStatusLabelOpen')</option>
                    <option value="close" @if(old('summerSeasonStatus') == 'close') selected="selected" @endif>@lang('cabins.summerSeasonChooseStatusLabelClose')</option>
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
                <label for="earliest_summer_open">@lang('cabins.summerSeasonEarliestOpen') <span class="required">*</span></label>

                <input type="text" class="form-control" id="earliest_summer_open" name="earliest_summer_open" placeholder="@lang('cabins.summerSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_open')}}">

                @if ($errors->has('earliest_summer_open'))
                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_open') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('earliest_summer_close') ? ' has-error' : '' }}">
                <label for="earliest_summer_close">@lang('cabins.summerSeasonEarliestClose') <span class="required">*</span></label>

                <input type="text" class="form-control" id="earliest_summer_close" name="earliest_summer_close" placeholder="@lang('cabins.summerSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_summer_close')}}">

                @if ($errors->has('earliest_summer_close'))
                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_close') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('latest_summer_open') ? ' has-error' : '' }}">
                <label for="earliest_summer_open">@lang('cabins.summerSeasonLatestOpen') <span class="required">*</span></label>

                <input type="text" class="form-control" id="latest_summer_open" name="latest_summer_open" placeholder="@lang('cabins.summerSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_open')}}">

                @if ($errors->has('latest_summer_open'))
                    <span class="help-block"><strong>{{ $errors->first('latest_summer_open') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('latest_summer_close') ? ' has-error' : '' }}">
                <label for="latest_summer_close">@lang('cabins.summerSeasonLatestClose') <span class="required">*</span></label>

                <input type="text" class="form-control" id="latest_summer_close" name="latest_summer_close" placeholder="@lang('cabins.summerSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_summer_close')}}">

                @if ($errors->has('latest_summer_close'))
                    <span class="help-block"><strong>{{ $errors->first('latest_summer_close') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('summer_next_season') ? ' has-error' : '' }}">
                <label for="summer_next_season">@lang('cabins.summerNextSeason') <span class="required">*</span></label>

                <input type="text" class="form-control" id="summer_next_season" name="summer_next_season" placeholder="@lang('cabins.summerNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('summer_next_season')}}">

                @if ($errors->has('summer_next_season'))
                    <span class="help-block"><strong>{{ $errors->first('summer_next_season') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="rule">@lang('cabins.summerSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                <div>
                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_mon" name="summer_mon" value="1" @if(old('summer_mon') == 1) checked @endif>
                        @lang('cabins.monday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_tue" name="summer_tue" value="1" @if(old('summer_tue') == 1) checked @endif>
                        @lang('cabins.tuesday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_wed" name="summer_wed" value="1" @if(old('summer_wed') == 1) checked @endif>
                        @lang('cabins.wednesday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_thu" name="summer_thu" value="1" @if(old('summer_thu') == 1) checked @endif>
                        @lang('cabins.thursday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_fri" name="summer_fri" value="1" @if(old('summer_fri') == 1) checked @endif>
                        @lang('cabins.friday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_sat" name="summer_sat" value="1" @if(old('summer_sat') == 1) checked @endif>
                        @lang('cabins.saturday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="summer_sun" name="summer_sun" value="1" @if(old('summer_sun') == 1) checked @endif>
                        @lang('cabins.sunday')
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4>@lang('cabins.winterSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 30.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('winterSeasonYear') ? ' has-error' : '' }}">
                <label>@lang('cabins.winterSeasonChooseSeasonLabel') <span class="required">*</span></label>

                <select class="form-control" name="winterSeasonYear" id="winterSeasonYear">
                    <option value="0">@lang('cabins.winterSeasonChooseSeasonSelect')</option>
                    <?php
                    $firstYear = (int)date('Y');
                    $lastYear  = (int)date('Y', strtotime('+2 year'));
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
                <label>@lang('cabins.winterSeasonChooseStatusLabel') <span class="required">*</span></label>

                <select class="form-control" name="winterSeasonStatus">
                    <option value="0">@lang('cabins.winterSeasonChooseStatusSelect')</option>
                    <option value="open" @if(old('winterSeasonStatus') == 'open') selected="selected" @endif>@lang('cabins.winterSeasonChooseStatusLabelOpen')</option>
                    <option value="close" @if(old('winterSeasonStatus') == 'close') selected="selected" @endif>@lang('cabins.winterSeasonChooseStatusLabelClose')</option>
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
                <label for="earliest_winter_open">@lang('cabins.winterSeasonEarliestOpen') <span class="required">*</span></label>

                <input type="text" class="form-control" id="earliest_winter_open" name="earliest_winter_open" placeholder="@lang('cabins.winterSeasonEarliestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_open')}}">

                @if ($errors->has('earliest_winter_open'))
                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_open') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('earliest_winter_close') ? ' has-error' : '' }}">
                <label for="earliest_winter_close">@lang('cabins.winterSeasonEarliestClose') <span class="required">*</span></label>

                <input type="text" class="form-control" id="earliest_winter_close" name="earliest_winter_close" placeholder="@lang('cabins.winterSeasonEarliestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('earliest_winter_close')}}">

                @if ($errors->has('earliest_winter_close'))
                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_close') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('latest_winter_open') ? ' has-error' : '' }}">
                <label for="earliest_winter_open">@lang('cabins.winterSeasonLatestOpen') <span class="required">*</span></label>

                <input type="text" class="form-control" id="latest_winter_open" name="latest_winter_open" placeholder="@lang('cabins.winterSeasonLatestOpenPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_open')}}">

                @if ($errors->has('latest_winter_open'))
                    <span class="help-block"><strong>{{ $errors->first('latest_winter_open') }}</strong></span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('latest_winter_close') ? ' has-error' : '' }}">
                <label for="latest_winter_close">@lang('cabins.winterSeasonLatestClose') <span class="required">*</span></label>

                <input type="text" class="form-control" id="latest_winter_close" name="latest_winter_close" placeholder="@lang('cabins.winterSeasonLatestClosePlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('latest_winter_close')}}">

                @if ($errors->has('latest_winter_close'))
                    <span class="help-block"><strong>{{ $errors->first('latest_winter_close') }}</strong></span>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('winter_next_season') ? ' has-error' : '' }}">
                <label for="winter_next_season">@lang('cabins.winterNextSeason') <span class="required">*</span></label>

                <input type="text" class="form-control" id="winter_next_season" name="winter_next_season" placeholder="@lang('cabins.winterNextSeasonPlaceholder')" data-date-format="dd.mm.yy" readonly="true" value="{{old('winter_next_season')}}">

                @if ($errors->has('winter_next_season'))
                    <span class="help-block"><strong>{{ $errors->first('winter_next_season') }}</strong></span>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="rule">@lang('cabins.winterSeasonSmallHeadingHoliday') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                <div>
                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_mon" name="winter_mon" value="1" @if(old('winter_mon') == 1) checked @endif>
                        @lang('cabins.monday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_tue" name="winter_tue" value="1" @if(old('winter_tue') == 1) checked @endif>
                        @lang('cabins.tuesday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_wed" name="winter_wed" value="1" @if(old('winter_wed') == 1) checked @endif>
                        @lang('cabins.wednesday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_thu" name="winter_thu" value="1" @if(old('winter_thu') == 1) checked @endif>
                        @lang('cabins.thursday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_fri" name="winter_fri" value="1" @if(old('winter_fri') == 1) checked @endif>
                        @lang('cabins.friday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_sat" name="winter_sat" value="1" @if(old('winter_sat') == 1) checked @endif>
                        @lang('cabins.saturday')
                    </label>

                    <label for="regularCheckbox">
                        <input type="checkbox" id="winter_sun" name="winter_sun" value="1" @if(old('winter_sun') == 1) checked @endif>
                        @lang('cabins.sunday')
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            <input type="hidden" name="summerSeason" value="1">
            <input type="hidden" name="winterSeason" value="1">
            <input type="hidden" name="cabin_id"  value="{{$cabin->_id}}" >
            <button type="button" class="btn btn-primary"  id="storeSeason"  name="storeSeason" value="storeSeason">
                <i
                        class="fa fa-fw fa-save"></i>@lang('cabins.btnSave')            </button>
            <button type="button" class="btn btn-primary   "  id="listSeason" >
                @lang('cabins.btnBack')            </button></div>
    </div>

</div>

</form>
