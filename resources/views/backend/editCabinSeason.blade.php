@if(!empty($summerSeason))
    <tr class="season-edit-tr">
        <td colspan="8">
            <div class="box-body" style="background-color: #ECF0F5">
                <div class="row">
                    <!-- Div for summer season -->
                    <div class="col-md-12">
                        <div class="box box-default box-solid">


                            @if (session('failure'))
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    {{ session('failure') }}
                                </div>
                            @endif

                            <div class="statusResponse"></div>

                            <form role="form" method="post" action="{{ route('admin.cabinlite.season.summer.update') }}"
                                  id="frmseasonedit">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('summerSeasonYear') ? ' has-error' : '' }}">
                                                <label>@lang('cabins.summerSeasonChooseSeasonLabel') <span
                                                            class="required">*</span></label>

                                                <select class="form-control" name="summerSeasonYear"
                                                        id="summerSeasonYearUpt">
                                                    <option value="0">@lang('cabins.summerSeasonChooseSeasonSelect')</option>
                                                    <?php
                                                    $firstYear = (int)date('Y');
                                                    $lastYear = (int)date('Y', strtotime('+2 year'));
                                                    for($i = $firstYear; $i <= $lastYear; $i++)
                                                    {
                                                    ?>
                                                    <option value="{{$i}}"
                                                            @if($summerSeason->summerSeasonYear == $i || old('summerSeason') == $i) selected="selected" @endif>{{$i}}</option>
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
                                                <label>@lang('cabins.summerSeasonChooseStatusLabel') <span
                                                            class="required">*</span></label>

                                                <select class="form-control" name="summerSeasonStatus">
                                                    <option value="0">@lang('cabins.summerSeasonChooseStatusSelect')</option>
                                                    <option value="open"
                                                            @if($summerSeason->summerSeasonStatus == 'open' || old('summerSeasonStatus') == 'open') selected="selected" @endif>@lang('cabins.summerSeasonChooseStatusLabelOpen')</option>
                                                    <option value="close"
                                                            @if($summerSeason->summerSeasonStatus == 'close' || old('summerSeasonStatus') == 'close') selected="selected" @endif>@lang('cabins.summerSeasonChooseStatusLabelClose')</option>
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
                                                <label for="earliest_summer_open">@lang('cabins.summerSeasonEarliestOpen')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_summer_open_upt"
                                                       name="earliest_summer_open"
                                                       placeholder="@lang('cabins.summerSeasonEarliestOpenPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('earliest_summer_open', ($summerSeason->earliest_summer_open)->format('d.m.y'))}}">

                                                @if ($errors->has('earliest_summer_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('earliest_summer_close') ? ' has-error' : '' }}">
                                                <label for="earliest_summer_close">@lang('cabins.summerSeasonEarliestClose')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_summer_close_upt"
                                                       name="earliest_summer_close"
                                                       placeholder="@lang('cabins.summerSeasonEarliestClosePlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('earliest_summer_close', ($summerSeason->earliest_summer_close)->format('d.m.y'))}}">

                                                @if ($errors->has('earliest_summer_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_summer_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_summer_open') ? ' has-error' : '' }}">
                                                <label for="earliest_summer_open">@lang('cabins.summerSeasonLatestOpen')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_summer_open_upt"
                                                       name="latest_summer_open"
                                                       placeholder="@lang('cabins.summerSeasonLatestOpenPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('latest_summer_open', ($summerSeason->latest_summer_open)->format('d.m.y'))}}">

                                                @if ($errors->has('latest_summer_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_summer_close') ? ' has-error' : '' }}">
                                                <label for="latest_summer_close">@lang('cabins.summerSeasonLatestClose')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_summer_close_upt"
                                                       name="latest_summer_close"
                                                       placeholder="@lang('cabins.summerSeasonLatestClosePlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('latest_summer_close', ($summerSeason->latest_summer_close)->format('d.m.y'))}}">

                                                @if ($errors->has('latest_summer_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_summer_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('summer_next_season') ? ' has-error' : '' }}">
                                                <label for="earliest_summer_open">@lang('cabins.summerNextSeason') <span
                                                            class="required">*</span></label>

                                                <input type="text" class="form-control" id="summer_next_season_upt"
                                                       name="summer_next_season"
                                                       placeholder="@lang('cabins.summerNextSeasonPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('summer_next_season', $summerSeason->summer_next_season->format('d.m.y'))}}">

                                                @if ($errors->has('summer_next_season'))
                                                    <span class="help-block"><strong>{{ $errors->first('summer_next_season') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rule">@lang('cabins.summerSeasonSmallHeadingHoliday') <i
                                                            class="fa fa-question-circle" data-toggle="tooltip"
                                                            data-placement="right"
                                                            title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                                                <div>
                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_mon" name="summer_mon"
                                                               value="1"
                                                               @if($summerSeason->summer_mon == 1 || old('summer_mon') == 1) checked @endif>
                                                        @lang('cabins.mondaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_tue" name="summer_tue"
                                                               value="1"
                                                               @if($summerSeason->summer_tue == 1 || old('summer_tue') == 1) checked @endif>
                                                        @lang('cabins.tuesdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_wed" name="summer_wed"
                                                               value="1"
                                                               @if($summerSeason->summer_wed == 1 || old('summer_wed') == 1) checked @endif>
                                                        @lang('cabins.wednesdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_thu" name="summer_thu"
                                                               value="1"
                                                               @if($summerSeason->summer_thu == 1 || old('summer_thu') == 1) checked @endif>
                                                        @lang('cabins.thursdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_fri" name="summer_fri"
                                                               value="1"
                                                               @if($summerSeason->summer_fri == 1 || old('summer_fri') == 1) checked @endif>
                                                        @lang('cabins.fridaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_sat" name="summer_sat"
                                                               value="1"
                                                               @if($summerSeason->summer_sat == 1 || old('summer_sat') == 1) checked @endif>
                                                        @lang('cabins.saturdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="summer_sun" name="summer_sun"
                                                               value="1"
                                                               @if($summerSeason->summer_sun == 1 || old('summer_sun') == 1) checked @endif>
                                                        @lang('cabins.sundaySht')
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
                                            <button type="button" class="btn btn-primary pull-left listSeason"
                                                    name="back" value="Back">@lang('cabins.btnBack')</button>


                                            <button type="button" class="btn btn-primary pull-right"
                                                    name="updateSummerSeason" id="updateSummerSeason"
                                                    value="updateSummerSeason"><i
                                                        class="fa fa-fw fa-save"></i>@lang('cabins.updateSummerButton')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-footer -->

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <!-- /.box-body -->
@endif
<script src="{{ asset('js/cabinSeasonEdit.js') }}"></script>

