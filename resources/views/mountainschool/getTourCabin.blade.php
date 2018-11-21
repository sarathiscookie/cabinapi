<div class="col-md-12">

    <div class="box box-default box-solid">
        <div class="box-header with-border d-flex align-items-center">
            <h3 class="box-title">@lang('mountainschool.tourDetails')</h3>
            <i class="fa fa-times-circle text-danger ml-auto" id="remove"></i>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label> Tour No:</label>{{$tour->tour_no}}</div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label> @lang('mountainschool.lblTourName'):</label>{{$tour->tour_name}}</div>
                </div>
            </div>
            <div class="cabinPart">
                <div class="cabinIndividuals">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('ind_tour_no') ? ' has-error' : '' }}">
                                <label>@lang('mountainschool.lblIndividualTourNo')<span class="required">*</span></label>
                                <input type="text" class="form-control " id="ind_tour_no" name="ind_tour_no[]" placeholder="@lang('mountainschool.lblIndividualTourNoPH')" value="{{old('ind_tour_no'  )}}" maxlength="100">
                                <span class="help-block"><strong>{{ $errors->first('ind_tour_no') }}</strong></span>

                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('tour_guide') ? ' has-error' : '' }}">
                                <label>@lang('tours.lblContactPerson')<span class="required">*</span></label>
                                <input type="text" class="form-control " id="tour_guide" name="tour_guide[]" placeholder="@lang('mountainschool.lblMountainGuidesPH')" value="{{old('tour_guide' ,$tour->basic_settings['contact_person'] )}}" maxlength="255">
                                <span class="help-block"><strong>{{ $errors->first('tour_guide') }}</strong></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('ind_notice') ? ' has-error' : '' }}">
                                <label>@lang('mountainschool.lblInd_notice')<span class="required"></span></label>
                                <input type="text" class="form-control daterange" id="ind_notice" name="ind_notice[]" placeholder="@lang('mountainschool.lblInd_noticePH')" value="{{old('ind_notice')}}" maxlength="255">
                                <span class="help-block"><strong>{{ $errors->first('ind_notice') }}</strong></span>
                            </div>
                        </div>
                    </div>
                    @inject('calendarServices', 'App\Http\Controllers\Mountainschool\CalendarController')

                    @isset($tour->cabins)
                        @php
                            $c = 0;
                        @endphp

                        @foreach($tour->cabins as $key => $valArray)
                            @php
                                $c++;
                                $cabinId  = $valArray['cId'];
                                $calendar = $calendarServices->calendar($cabinId);
                            @endphp
                            <div class="row cabinlistcss" style="margin: 2px;">
                                <div class="col-md-12">
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-12 gradient-gray">
                                            <h4 class="fa fa-home"><strong>{{$valArray['name']}} @if($valArray['other_cabin'] == '1')<span class="badge">@lang('tours.neighborCabin')</span>@endif</strong></h4>
                                            <input class="otherCabinCls" type="hidden" data-other_cabin="{{$valArray['other_cabin']}}" value="{{$valArray['cId']}}" name="cabinId[{{ $tour_index - 1 }}][]">
                                        </div>
                                    </div>

                                    <div class="col-md-12 error" id="errors_{{ $c - 1 }}_{{ $tour_index - 1 }}" style="display: none;"></div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('guests') ? ' has-error' : '' }}">
                                                <label>@lang('mountainschool.lblNoOfGuests')<span class="required">*</span></label>
                                                <select class="form-control guestsInputCls" id="guests" name="guests[{{ $tour_index - 1 }}][]">
                                                    <option value="0">@lang('mountainschool.lblNoOfGuestsPH')</option>
                                                    @for ($n=1; $n<=30; $n++)
                                                        <option @if(old('guests', $tour->basic_settings['guests']) == $n) selected @endif value="{{$n}}">{{$n}} @lang('mountainschool.lblOptGuests')</option>
                                                    @endfor
                                                </select>

                                                <span class="help-block"><strong>{{ $errors->first('guests') }}</strong></span>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('no_guides') ? ' has-error' : '' }}">
                                                <label>@lang('mountainschool.lblNoOfnGuides')</label>
                                                <select class="form-control guidesInputCls" id="no_guides" name="no_guides[{{ $tour_index - 1 }}][]" >
                                                    <option value="0">@lang('mountainschool.lblNoOfnGuidesPH')</option>
                                                    @for($n=1; $n<=8; $n++)
                                                        <option @if(old('no_guides', $tour->basic_settings['no_guides']) == $n) selected @endif value="{{$n}}">{{$n}} @lang('mountainschool.lblOptGuides')</option>
                                                    @endfor
                                                </select>

                                                <span class="help-block"><strong>{{ $errors->first('no_guides') }}</strong></span>
                                            </div>
                                        </div>

                                        <input type="hidden" name="sleeping_place[{{ $tour_index - 1 }}][]" value="{{ $valArray['sleeping_place'] }}">

                                        @if($valArray['other_cabin'] == '0')
                                            @if($valArray['sleeping_place'] != 1)
                                                <div class="col-md-2">
                                                    <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                                        <label>@lang('mountainschool.lblMountainbeds')</label>
                                                        <select class="form-control bedsInputCls" id="beds" name="beds[{{ $tour_index - 1 }}][]">
                                                            <option value="">@lang('mountainschool.lblMountainbedsPH')</option>
                                                            @for ($n=1; $n<=40; $n++)
                                                                <option @if(old('beds', $tour->basic_settings['beds']) == $n) selected @endif value="{{$n}}">{{$n}} @lang('mountainschool.lblOptBeds')</option>
                                                            @endfor
                                                        </select>

                                                        <span class="help-block"><strong>{{ $errors->first('beds') }}</strong></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group {{ $errors->has('dormitory') ? ' has-error' : '' }}">
                                                        <label> @lang('mountainschool.lblMountaindorm')</label>

                                                        <select class="form-control dormitoryInputCls" id="dormitory" name="dormitory[{{ $tour_index - 1 }}][]">
                                                            <option value="">@lang('mountainschool.lblMountaindormPH')</option>
                                                            @for ($n=1; $n<=40; $n++)
                                                                <option @if(old('dormitory', $tour->basic_settings['dorms']) == $n) selected @endif value="{{$n}}">{{$n}} @lang('mountainschool.lblOptDorms')</option>
                                                            @endfor
                                                        </select>

                                                        <span class="help-block"><strong>{{ $errors->first('dormitory') }}</strong></span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-2">
                                                    <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                                        <label>@lang('mountainschool.lblSleepingPlace')<span class="required">*</span></label>
                                                        <select class="form-control sleepsInputCls" id="sleeps" name="sleeps[{{ $tour_index - 1 }}][]">
                                                            <option value="">@lang('mountainschool.lblSleepingPlacePH')</option>
                                                            @for($n=1; $n<=40; $n++)
                                                                <option @if(old('sleeps', $tour->basic_settings['sleeps']) == $n) selected @endif value="{{$n}}" >{{$n}} @lang('mountainschool.lblOptSleepPlaz')  </option>
                                                            @endfor
                                                        </select>

                                                        <span class="help-block"><strong>{{ $errors->first('sleeps') }}</strong></span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="calendar" data-id="{{ $tour_index }}{{ $c }}" data-cabinid="{{ $valArray['cId'] }}">
                                            <div class="holiday{{ $tour_index }}{{ $c }}" data-holiday="{{ $calendar[0] }}"></div>
                                            <div class="green{{ $tour_index }}{{ $c }}" data-green="{{ $calendar[1] }}"></div>
                                            <div class="orange{{ $tour_index }}{{ $c }}" data-orange="{{ $calendar[2] }}"></div>
                                            <div class="red{{ $tour_index }}{{ $c }}" data-red="{{ $calendar[3] }}"></div>
                                            <div class="notSeasonTime{{ $tour_index }}{{ $c }}" data-notseasontime="{{ $calendar[4] }}"></div>

                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->has('check_in') ? ' has-error' : '' }}">
                                                    <label>@lang('mountainschool.lblCheckIn')<span class="required">*</span></label>
                                                    <input type="text" class="form-control checkInCls" id="check_in{{ $tour_index }}{{ $c }}" name="check_in[{{ $tour_index - 1 }}][]" placeholder="@lang('mountainschool.lblCheckInPH')" value="" readonly autocomplete="off">

                                                    <span class="help-block"><strong>{{ $errors->first('check_in') }}</strong></span>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->has('check_out') ? ' has-error' : '' }}">
                                                    <label>@lang('mountainschool.lblCheckOut')<span class="required">*</span></label>
                                                    <input type="text" class="form-control checkOutCls" id="check_out{{ $tour_index }}{{ $c }}" name="check_out[{{ $tour_index - 1}}][]" placeholder="@lang('mountainschool.lblCheckOutPH')" value="" readonly autocomplete="off">

                                                    <span class="help-block"><strong>{{ $errors->first('check_out') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>

                                        @if($valArray['halfboard'] == '1' && $valArray['halfboard_price'] != '')
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>@lang('cabins.lblHalfboard')</label>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" id="halfboard{{ $tour_index }}" class="halfboardCls" name="halfboard[{{ $tour_index - 1 }}][]" value="1" @if(old('halfboard', $tour->basic_settings['half_board']) == '1') checked @endif>@lang('cabins.half_board_available')
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" name="no_cabins" value="{{$tour->no_cabins}}">
                    @endisset
                </div>
            </div>
        </div>
        {{--<div id="appendDup"></div>--}}
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>




