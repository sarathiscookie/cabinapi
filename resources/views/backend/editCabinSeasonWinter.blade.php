@isset($winterSeason)
    <tr class="season-edit-tr">
        <td colspan="8">
            <div class="box-body">
                <div class="row">
                    <!-- Div for summer season -->
                    <div class="col-md-12">
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h4>@lang('cabins.winterSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 30.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></i>
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

                            <form role="form" id="frmwinterseasonedit" method="post">
                                {{ csrf_field() }}
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('winterSeasonYear') ? ' has-error' : '' }}">
                                                <label>@lang('cabins.winterSeasonChooseSeasonLabel') <span class="required">*</span></label>

                                                <select class="form-control" name="winterSeasonYear" id="winterSeasonYear_upt">
                                                    <option value="0">@lang('cabins.winterSeasonChooseSeasonSelect')</option>
                                                    <?php
                                                    $firstYear = (int)date('Y');
                                                    $lastYear = (int)date('Y', strtotime('+2 year'));
                                                    for($i = $firstYear; $i <= $lastYear; $i++)
                                                    {
                                                    ?>
                                                       <option value="{{$i}}" @if($winterSeason->winterSeasonYear == $i || old('winterSeason') == $i) selected="selected" @endif>{{$i}}</option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>

                                                @if($errors->has('winterSeasonYear'))
                                                    <span class="help-block"><strong>{{ $errors->first('winterSeasonYear') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('winterSeasonStatus') ? ' has-error' : '' }}">
                                                <label>@lang('cabins.winterSeasonChooseStatusLabel') <span class="required">*</span></label>

                                                <select class="form-control" name="winterSeasonStatus">
                                                    <option value="0">@lang('cabins.winterSeasonChooseStatusSelect')</option>
                                                    <option value="open"
                                                            @if($winterSeason->winterSeasonStatus == 'open' || old('winterSeasonStatus') == 'open') selected="selected" @endif>@lang('cabins.winterSeasonChooseStatusLabelOpen')</option>
                                                    <option value="close"
                                                            @if($winterSeason->winterSeasonStatus == 'close' || old('winterSeasonStatus') == 'close') selected="selected" @endif>@lang('cabins.winterSeasonChooseStatusLabelClose')</option>
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
                                                <label for="earliest_winter_open">@lang('cabins.winterSeasonEarliestOpen')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_winter_open_upt"
                                                       name="earliest_winter_open"
                                                       placeholder="@lang('cabins.winterSeasonEarliestOpenPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('earliest_winter_open', ($winterSeason->earliest_winter_open)->format('d.m.y'))}}">

                                                @if ($errors->has('earliest_winter_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('earliest_winter_close') ? ' has-error' : '' }}">
                                                <label for="earliest_winter_close">@lang('cabins.winterSeasonEarliestClose')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="earliest_winter_close_upt"
                                                       name="earliest_winter_close"
                                                       placeholder="@lang('cabins.winterSeasonEarliestClosePlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('earliest_winter_close', ($winterSeason->earliest_winter_close)->format('d.m.y'))}}">

                                                @if ($errors->has('earliest_winter_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('earliest_winter_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_winter_open') ? ' has-error' : '' }}">
                                                <label for="earliest_winter_open">@lang('cabins.winterSeasonLatestOpen')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_winter_open_upt"
                                                       name="latest_winter_open"
                                                       placeholder="@lang('cabins.winterSeasonLatestOpenPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('latest_winter_open', ($winterSeason->latest_winter_open)->format('d.m.y'))}}">

                                                @if ($errors->has('latest_winter_open'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_open') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('latest_winter_close') ? ' has-error' : '' }}">
                                                <label for="latest_winter_close">@lang('cabins.winterSeasonLatestClose')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="latest_winter_close_upt"
                                                       name="latest_winter_close"
                                                       placeholder="@lang('cabins.winterSeasonLatestClosePlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('latest_winter_close', ($winterSeason->latest_winter_close)->format('d.m.y'))}}">

                                                @if ($errors->has('latest_winter_close'))
                                                    <span class="help-block"><strong>{{ $errors->first('latest_winter_close') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('winter_next_season') ? ' has-error' : '' }}">
                                                <label for="winter_next_season">@lang('cabins.winterNextSeason')
                                                    <span class="required">*</span></label>

                                                <input type="text" class="form-control" id="winter_next_season_upt"
                                                       name="winter_next_season"
                                                       placeholder="@lang('cabins.winterNextSeasonPlaceholder')"
                                                       data-date-format="dd.mm.yy" readonly="true"
                                                       value="{{old('winter_next_season', ($winterSeason->winter_next_season)->format('d.m.y'))}}">

                                                @if ($errors->has('winter_next_season'))
                                                    <span class="help-block"><strong>{{ $errors->first('winter_next_season') }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rule">@lang('cabins.winterSeasonSmallHeadingHoliday')
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                       data-placement="right"
                                                       title="Sollte Ihre Hütte an bestimmten Tagen geschlossen sein, dann wählen Sie bitte den entsprechenden Tag aus. (Sollten Sie z.B. Montag auswählen, so sind keine Übernachtungen von Montag auf Dienstag möglich)."></i></label>
                                                <div>
                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_mon" name="winter_mon" value="1" @if($winterSeason->winter_mon == 1 || old('winter_mon') == 1) checked @endif>
                                                        @lang('cabins.mondaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_tue" name="winter_tue" value="1" @if($winterSeason->winter_tue == 1 || old('winter_tue') == 1) checked @endif>
                                                        @lang('cabins.tuesdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_wed" name="winter_wed" value="1" @if($winterSeason->winter_wed == 1 || old('winter_wed') == 1) checked @endif>
                                                        @lang('cabins.wednesdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_thu" name="winter_thu" value="1" @if($winterSeason->winter_thu == 1 || old('winter_thu') == 1) checked @endif>
                                                        @lang('cabins.thursdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_fri" name="winter_fri" value="1" @if($winterSeason->winter_fri == 1 || old('winter_fri') == 1) checked @endif>
                                                        @lang('cabins.fridaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_sat" name="winter_sat" value="1" @if($winterSeason->winter_sat == 1 || old('winter_sat') == 1) checked @endif>
                                                        @lang('cabins.saturdaySht')
                                                    </label>

                                                    <label for="regularCheckbox">
                                                        <input type="checkbox" id="winter_sun" name="winter_sun" value="1" @if($winterSeason->winter_sun == 1 || old('winter_sun') == 1) checked @endif>
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
                                            <input type="hidden" name="winterSeasonId" value="{{$winterSeason->_id}}">
                                            <button type="button" class="btn btn-primary pull-left listSeason" name="back" value="Back">@lang('cabins.btnBack')</button>
                                            <button type="button" class="btn btn-primary pull-right" id="updateWinterSeason" name="updateWinterSeason" value="updateWinterSeason"><i class="fa fa-fw fa-save"></i>@lang('cabins.updateWinterButton')
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
@endisset
<!-- Helping object for translation -->

<script src="{{ asset('js/cabinSeasonEdit.js') }}"></script>
