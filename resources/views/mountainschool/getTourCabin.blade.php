<div class="col-md-12">

    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('mountainschool.tourDetails')     </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body ">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group"><label> Tour No:</label>
                        {{$tour->tour_no}} </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group"><label> @lang('mountainschool.lblTourName'):</label>
                        {{$tour->tour_name}}</div>
                </div>
            </div>
            <div class="cabinPart">
                <div class="cabinIndividuals">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('ind_tour_no') ? ' has-error' : '' }}">
                                <label>  @lang('mountainschool.lblIndividualTourNo')<span
                                            class="required">*</span></label>
                                <input type="text" class="form-control " id="ind_tour_no" name="ind_tour_no[]"
                                       placeholder="@lang('mountainschool.lblIndividualTourNoPH')"
                                       value="{{old('ind_tour_no'  )}}" maxlength="255">

                                <span class="help-block"><strong>{{ $errors->first('ind_tour_no') }}</strong></span>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('tour_guide') ? ' has-error' : '' }}">
                                <label>  @lang('mountainschool.lblMountainGuides')<span
                                            class="required">*</span></label>
                                <input type="text" class="form-control " id="tour_guide" name="tour_guide[]"
                                       placeholder="@lang('mountainschool.lblMountainGuidesPH')"
                                       value="{{old('tour_guide' ,$tour->basic_settings->contact_person )}}"
                                       maxlength="255">


                                <span class="help-block"><strong>{{ $errors->first('tour_guide') }}</strong></span>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ $errors->has('ind_notice') ? ' has-error' : '' }}">
                                <label>  @lang('mountainschool.lblInd_notice')<span class="required"></span></label>
                                <input type="text" class="form-control daterange" id="ind_notice" name="ind_notice[]"
                                       placeholder="@lang('mountainschool.lblInd_noticePH')"
                                       value="{{old('ind_notice'  )}}"
                                       maxlength="255">
                                <span class="help-block"><strong>{{ $errors->first('ind_notice') }}</strong></span>

                            </div>
                        </div>
                    </div>


                    @isset($tour->cabins)
                        @php              $c=0;  @endphp

                        @foreach($tour->cabins as $key =>$valArray)

                            @php            $c++;  @endphp
                            <div class="row cabinlistcss" style="margin: 2px;">
                                <div class="col-md-12">
                                    <div class="row" style="margin-bottom: 5px;">
                                        <div class="col-md-12 gradient-gray">
                                            <h4 class="fa fa-home "><strong>{{$valArray['name']}}</strong></h4>
                                            <input class="otherCabinCls" type="hidden"
                                                   data-other_cabin="{{$valArray['other_cabin']}}"
                                                   value="{{$valArray['cId']}}" name="cabinId{{$c}}[]">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('no_guides') ? ' has-error' : '' }}">
                                                <label>  @lang('mountainschool.lblNoOfnGuides')<span
                                                            class="required">*</span></label>
                                                <select class="form-control guidesInputCls "   id="no_guides{{ $c}}"
                                                          name="no_guides{{ $c}}[]" >
                                                    <option value="0">@lang('mountainschool.lblNoOfnGuidesPH')</option>
                                                    @for ($n=1; $n<=8; $n++)
                                                    <option     @if(  old('no_guides') == $n ||  $tour->basic_settings->no_guides == $n ) selected="selected"
                                                                @endif value="{{$n}}" >{{$n}}   @lang('mountainschool.lblOptGuides')</option>
                                                    @endfor
                                                </select>
                                               <!--<input type="text" class="form-control guidesInputCls "
                                                       id="no_guides{{ $c}}"
                                                       name="no_guides{{ $c}}[]"
                                                       placeholder="@lang('mountainschool.lblNoOfnGuidesPH')"
                                                       value="{{old('no_guides' ,$tour->basic_settings->no_guides )}}"> -->

                                                <span class="help-block"><strong>{{ $errors->first('no_guides') }}</strong></span>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('guests') ? ' has-error' : '' }}">
                                                <label>  @lang('mountainschool.lblNoOfGuests')<span
                                                            class="required">*</span></label>
                                                <select class="form-control guestsInputCls "   id="guests{{ $c}}" name="guests{{ $c}}[]">
                                                    <option value="0">@lang('mountainschool.lblNoOfGuestsPH')</option>
                                                    @for ($n=1; $n<=30; $n++)
                                                        <option     @if(  old('guests') == $n  ) selected="selected"
                                                                    @endif value="{{$n}}" >{{$n}}  @lang('mountainschool.lblOptGuests')  </option>
                                                    @endfor
                                                </select>
                                               <!-- <input type="text" class="form-control guestsInputCls"
                                                       id="guests{{ $c}}" name="guests{{ $c}}[]"
                                                       placeholder="@lang('mountainschool.lblNoOfGuestsPH')"
                                                       value="{{old('guests'  )}}">-->
                                                <span class="help-block"><strong>{{ $errors->first('guests') }}</strong></span>

                                            </div>
                                        </div>
                                        @if(isset($valArray['sleeping_place']) && $valArray['sleeping_place'] =="1")
                                            <div class="col-md-2">
                                                <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                                    <label>  @lang('mountainschool.lblSleepingPlace')<span
                                                                class="required">*</span></label>
                                                    <select class="form-control sleepsInputCls "        id="sleeps{{ $c}}"
                                                            name="sleeps{{ $c}}[]" >
                                                        <option value="">@lang('mountainschool.lblSleepingPlacePH')</option>
                                                        @for ($n=1; $n<=40; $n++)
                                                            <option     @if(  old('sleeps') == $n ||  $tour->basic_settings->no_guides == $n ) selected="selected"
                                                                        @endif value="{{$n}}" >{{$n}}  @lang('mountainschool.lblOptSleepPlaz')  </option>
                                                        @endfor
                                                    </select>
                                                   <!-- <input type="text" class="form-control sleepsInputCls"
                                                           id="sleeps{{ $c}}"
                                                           name="sleeps{{ $c}}[]"
                                                           placeholder="@lang('mountainschool.lblSleepingPlacePH')"
                                                           value="{{old('sleeps',$tour->basic_settings->no_guides  )}}">-->


                                                    <span class="help-block"><strong>{{ $errors->first('sleeps') }}</strong></span>

                                                </div>
                                            </div>
                                        @else
                                            @if(isset($valArray['beds'])  )
                                                <div class="col-md-2">
                                                    <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                                        <label>  @lang('mountainschool.lblMountainbeds')<span
                                                                    class="required">*</span></label>
                                                        <select class="form-control bedsInputCls "      id="beds{{ $c}}" name="beds{{ $c}}[]" >
                                                            <option value="">@lang('mountainschool.lblMountainbedsPH')</option>
                                                            @for ($n=1; $n<=40; $n++)
                                                                <option     @if(  old('beds') == $n ||  $tour->basic_settings->no_guides == $n ) selected="selected"
                                                                            @endif value="{{$n}}" >{{$n}}  @lang('mountainschool.lblOptBeds')</option>
                                                            @endfor
                                                        </select>

                                                       <!-- <input type="text" class="form-control bedsInputCls"
                                                               id="beds{{ $c}}" name="beds{{ $c}}[]"
                                                               placeholder="@lang('mountainschool.lblMountainbedsPH')"
                                                               value="{{old('beds'  ,$tour->basic_settings->no_guides )}}">-->


                                                        <span class="help-block"><strong>{{ $errors->first('beds') }}</strong></span>

                                                    </div>
                                                </div>
                                            @endif

                                                @if(isset($valArray['dormitory'])  )
                                                    <div class="col-md-2">
                                                        <div class="form-group {{ $errors->has('dormitory') ? ' has-error' : '' }}">
                                                            <label>  @lang('mountainschool.lblMountaindorm')<span
                                                                        class="required"></span></label>

                                                            <select class="form-control dormitoryInputCls "   id="dormitory{{ $c}}" name="dormitory{{ $c}}[]" >
                                                                <option value="">@lang('mountainschool.lblMountaindormPH')</option>
                                                                @for ($n=1; $n<=40; $n++)
                                                                    <option     @if(  old('dormitory') == $n  ) selected="selected"
                                                                                @endif value="{{$n}}" >{{$n}}    @lang('mountainschool.lblOptDorms')</option>
                                                                @endfor
                                                            </select>


                                                           <!-- <input type="text" class="form-control dormitoryInputCls"
                                                                   id="dormitory{{ $c}}" name="dormitory{{ $c}}[]"
                                                                   placeholder="@lang('mountainschool.lblMountaindormPH')"
                                                                   value="{{old('dormitory')}}">-->
                                                            <span class="help-block"><strong>{{ $errors->first('dormitory') }}</strong></span>

                                                        </div>
                                                    </div>
                                                @endif
                                        @endif
                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('check_in') ? ' has-error' : '' }}">
                                                <label>  @lang('mountainschool.lblCheckIn')<span
                                                            class="required">*</span></label>
                                                <input type="text" class="form-control checkInCls"
                                                       id="check_in{{ $c}}"   name="check_in{{ $c}}[]" data-cabinId="{{$valArray['cId']}}"
                                                       placeholder="@lang('mountainschool.lblCheckInPH')"
                                                       value="{{old('check_in')}}">
                                                <span class="help-block"><strong>{{ $errors->first('check_in') }}</strong></span>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group {{ $errors->has('days') ? ' has-error' : '' }}">
                                                <label>  @lang('mountainschool.lblMountainDays')<span
                                                            class="required">*</span></label>
                                                <select name="days{{ $c}}[]" id="days{{ $c}}"
                                                        class="form-control dayscls">
                                                    <option value="">@lang('mountainschool.lblMountainDaysPH')</option>
                                                    @for($i=1 ; $i<=30 ; $i++)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>

                                                <span class="help-block"><strong>{{ $errors->first('days') }}</strong></span>

                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>@lang('cabins.lblHalfboard')   </label>
                                                <div class=" checkbox">
                                                    <label>
                                                        <input type="hidden"  name="hidHalfboard{{ $c}}[]"  class="HidHalfboardCls"value="">
                                                        <input type="checkbox" id="halfboard{{ $c}}" class="halfboardCls"
                                                               name="halfboard{{ $c}}[]"
                                                               {{( old('halfboard') == '1'  )? 'checked="checked"' :'' }}   >
                                                        @lang('cabins.half_board_available')</label></div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <input type="hidden" name="no_cabins" value="{{$tour->no_cabins}}">
                        <input type="hidden" name="hidProceed" id="hidProceed" value="no">
                    @endisset
                </div>
            </div>
        </div>
        <div id="appendDup"></div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>
<?php

$disableDates = array("2-5-2018", "4-5-2018", "15-5-2018");
$partialAvailDates = array("12-5-2018", "14-5-2018", "16-5-2018", "17-5-2018", "18-6-2018"); ?>
<script>

   // $('.halfboardCls').click(function () {
        $('#cabindtls').on('click', '.halfboardCls', function() {
        if ($(this).is(':checked')) {
            $(this).val('1');
            $(this).prev('.HidHalfboardCls').val('1');

        } else {
            $(this).val('');
            $(this).prev('.HidHalfboardCls').val('');
        }
        console.log( $(this).val());
    });

    function aviablityCheck(arg) {
        $this = arg;

        var other_cabin = $this .parent('.form-group').closest('.row').closest('.cabinlistcss').find('.otherCabinCls').data("other_cabin");

        if (other_cabin == "0") {
            var datefromInput = $this.parent('.form-group').closest('.row').find('.checkInCls');
            var selDaysFeild = $this.parent('.form-group').closest('.row').find('.dayscls');
            var sleepsFeild = $this.parent('.form-group').closest('.row').find('.sleepsInputCls');
            var bedsFeild = $this.parent('.form-group').closest('.row').find('.bedsInputCls');
            var dormFeild = $this.parent('.form-group').closest('.row').find('.dormInputCls');
            ovelayLoading('add', 'tourbox');
            var start_from = datefromInput.val();
            $.ajax({
                url: '/mountainschool/checkAvailability',
                dataType: 'JSON',
                type: 'POST',
                async: false,
                data: {
                    dateFrom: start_from,
                    cabinId: datefromInput.data('cabinid'),
                    selDays: selDaysFeild.val(),
                    beds: bedsFeild.val(),
                    dorms: dormFeild.val(),
                    sleeps: sleepsFeild.val()
                },
                success: function (response) {
                    datefromInput.next('.help-block').html('  ');
                    datefromInput.parent('.form-group').removeClass('has-error');
                    $('#newBooking').removeAttr('disabled');
                    $('#hidProceed').val('valSuccess');

                    ovelayLoading('remove');
                },
                error: function (err) {
                    $('#newBooking').attr('disabled', 'disabled');
                   $('#hidProceed').val('no');
                    datefromInput.next('.help-block').html(' <strong>' + err.responseJSON.error + '</strong> ');
                    datefromInput.parent('.form-group').addClass('has-error');
                    ovelayLoading('remove');
                }
            });
        }else{
            $('#hidProceed').val('valSuccess');
        }
    }

    $(function () {

    //    $('.dayscls ').on("change", function () {
        $('#cabindtls').on('change', '.dayscls', function() {
            var datefromInput = $(this).parent('.form-group').closest('.row').find('.checkInCls');

            changeNextCabinFromDate(datefromInput, $(this).val());

                aviablityCheck($(this));


        });
        $('.bedsInputCls ,.guestsInputCls , .guidesInputCls ').on('keyup   change', function (e) {
            aviablityCheck($(this));
        });
        /* Checking for the CSRF token */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
    /* Calendar booking availability begin */


    var orangeDates = <?php echo json_encode($partialAvailDates);?> ;

    var redDates = <?php echo json_encode($disableDates);?> ;


    var start_date = '';

    calendaerDisp();

    /*  changeNextCabinFromDate when select days and change  date after selet days*/
    function changeNextCabinFromDate(datefromInput, daysVal) {
        var datePicId = datefromInput.attr('id');


        var selectedDate = $('#' + datePicId).datepicker('getDate');

        var msecsInADay = daysVal * 86400000;
        var endDate = new Date(selectedDate.getTime() + msecsInADay);


        var dpId = $('#' + datePicId).attr('id');
        var nextDpIdstr = dpId.split("check_in");
        var nextDpId = 'check_in' + (parseInt(nextDpIdstr[1]) + 1);
        $('#' + nextDpId).datepicker("option", "minDate", 0);
        if (('#' + nextDpId).length > 0) {
            //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
            $('#' + nextDpId).datepicker('setDate', endDate);
            $('#' + nextDpId).datepicker("option", "minDate", endDate);
            $('#' + nextDpId).datepicker("option", "defaultDate", endDate);

        }


    }

    /* color change for calendar dates green, red,orange*/

    function colorize(date) {
        dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
        if ($.inArray(dmy, orangeDates) > -1) {
            return [true, "partialavilcls", "Available"];
        } else if ($.inArray(dmy, redDates) > -1) {
            return [true, "notavilcls", "Unavailable"];
        } else {
            return [true, "avilcls"];
        }
    }

    /* */

    //$('.guestsInputCls').keyup(function (data) {
    $('#cabindtls').on('change', '.guestsInputCls', function() {
        calcSleeps($(this), 'guidesInputCls');
    });

     $('#cabindtls').on('change ', '.guidesInputCls', function() {
        calcSleeps($(this), 'guestsInputCls');
    });

    /* Updated the val of sleeping place */
    function calcSleeps(argThis, addFieldCls) {

        var g_val = $(argThis).val();

        if (g_val !== "" && isInt(g_val) === false) {
            $(argThis).parent('.form-group').addClass('has-error');
            $(argThis).next('span').html('<strong>Should be a Number</strong>');
        } else {
            $(argThis).parent('.form-group').removeClass('has-error')
            $(argThis).next('span').html('');
            var sumVal = parseInt(g_val) + parseInt($(argThis).parent('.form-group').closest('.row').find('.' + addFieldCls).val());


            if ($(argThis).parent('.form-group').closest('.row').find('.sleepsInputCls').length > 0) {
                $(argThis).parent('.form-group').closest('.row').find('.sleepsInputCls').val(sumVal);
            } //if sleeping place 1
            else {
                $(argThis).parent('.form-group').closest('.row').find('.bedsInputCls').val(sumVal);
            }

        }
    }

    function isInt(value) {

        var er = /^-?[0-9]+$/;

        return er.test(value);
    }
</script>


