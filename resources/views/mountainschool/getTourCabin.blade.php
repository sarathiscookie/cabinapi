


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
                        <label>  @lang('mountainschool.lblIndividualTourNo')<span class="required">*</span></label>
                        <input type="text" class="form-control " id="ind_tour_no" name="ind_tour_no[]"
                               placeholder="@lang('mountainschool.lblIndividualTourNoPH')"
                               value="{{old('ind_tour_no'  )}}" maxlength="255">

                            <span class="help-block"><strong>{{ $errors->first('ind_tour_no') }}</strong></span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('tour_guide') ? ' has-error' : '' }}">
                        <label>  @lang('mountainschool.lblMountainGuides')<span class="required">*</span></label>
                        <input type="text" class="form-control " id="tour_guide" name="tour_guide[]"
                               placeholder="@lang('mountainschool.lblMountainGuidesPH')"
                               value="{{old('tour_guide' ,$tour->basic_settings->contact_person )}}" maxlength="255">


                            <span class="help-block"><strong>{{ $errors->first('tour_guide') }}</strong></span>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('ind_notice') ? ' has-error' : '' }}">
                        <label>  @lang('mountainschool.lblInd_notice')<span class="required">*</span></label>
                        <input type="text" class="form-control daterange" id="ind_notice" name="ind_notice[]"
                               placeholder="@lang('mountainschool.lblInd_noticePH')" value="{{old('ind_notice'  )}}"
                               maxlength="255">


                            <span class="help-block"><strong>{{ $errors->first('ind_notice') }}</strong></span>

                    </div>
                </div>
            </div>


            @isset($tour->cabins)
                @php              $c=0;  @endphp

                @foreach($tour->cabins as $key =>$valArray)

                    @php              $c++;  @endphp
                    <div class="row cabinlistcss" style="margin: 2px;">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12 gradient-gray">
                                    <h4 class="fa fa-home "><strong>{{$valArray['name']}}</strong></h4>
                                    <input type="hidden" value="{{$valArray['cId']}}" name="cabinId{{$c}}[]">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 ">&nbsp;</div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('no_guides') ? ' has-error' : '' }}">
                                        <label>  @lang('mountainschool.lblNoOfnGuides')<span
                                                    class="required">*</span></label>
                                        <input type="text" class="form-control guidesInputCls " id="no_guides{{ $c}}"
                                               name="no_guides{{ $c}}[]" placeholder="@lang('mountainschool.lblNoOfnGuidesPH')"
                                               value="{{old('no_guides' ,$tour->basic_settings->no_guides )}}">


                                            <span class="help-block"><strong>{{ $errors->first('no_guides') }}</strong></span>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('guests') ? ' has-error' : '' }}">
                                        <label>  @lang('mountainschool.lblNoOfGuests')<span
                                                    class="required">*</span></label>
                                        <input type="text" class="form-control guestsInputCls" id="guests{{ $c}}" name="guests{{ $c}}[]"
                                               placeholder="@lang('mountainschool.lblNoOfGuestsPH')"
                                               value="{{old('guests'  )}}">
                                        <span class="help-block"><strong>{{ $errors->first('guests') }}</strong></span>

                                    </div>
                                </div>
                                @if(isset($valArray['sleeping_place']) && $valArray['sleeping_place'] =="1")
                                    <div class="col-md-2">
                                        <div class="form-group {{ $errors->has('sleeps') ? ' has-error' : '' }}">
                                            <label>  @lang('mountainschool.lblSleepingPlace')<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control sleepsInputCls" id="sleeps{{ $c}}"
                                                   name="sleeps{{ $c}}[]"
                                                   placeholder="@lang('mountainschool.lblSleepingPlacePH')"
                                                   value="{{old('sleeps',$tour->basic_settings->no_guides  )}}">


                                                <span class="help-block"><strong>{{ $errors->first('sleeps') }}</strong></span>

                                        </div>
                                    </div>
                                @else
                                @if(isset($valArray['beds'])  )
                                    <div class="col-md-2">
                                        <div class="form-group {{ $errors->has('beds') ? ' has-error' : '' }}">
                                            <label>  @lang('mountainschool.lblMountainbeds')<span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control bedsInputCls" id="beds{{ $c}}" name="beds{{ $c}}[]"
                                                   placeholder="@lang('mountainschool.lblMountainbedsPH')"
                                                   value="{{old('beds'  ,$tour->basic_settings->no_guides )}}">


                                                <span class="help-block"><strong>{{ $errors->first('beds') }}</strong></span>

                                        </div>
                                    </div>
                                @endif
                                @endif
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('check_in') ? ' has-error' : '' }}">
                                        <label>  @lang('mountainschool.lblCheckIn')<span
                                                    class="required">*</span></label>
                                        <input type="text" class="form-control checkInCls" id="check_in{{ $c}}"
                                               name="check_in{{ $c}}[]" data-cabinId="{{$valArray['cId']}}"
                                               placeholder="@lang('mountainschool.lblCheckInPH')"
                                               value="{{old('check_in')}}">
                                            <span class="help-block"><strong>{{ $errors->first('check_in') }}</strong></span>

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group {{ $errors->has('days') ? ' has-error' : '' }}">
                                        <label>  @lang('mountainschool.lblMountainDays')<span class="required">*</span></label>
                                        <select name="days{{ $c}}[]" id="days{{ $c}}" class="form-control dayscls">
                                            <option value="">@lang('mountainschool.lblMountainDaysPH')</option>
                                            @for($i=1 ; $i<=30 ; $i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>

                                            <span class="help-block"><strong>{{ $errors->first('days') }}</strong></span>

                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>@lang('cabins.lblHalfboard')   </label>
                                        <div class=" checkbox">
                                            <label>
                                                <input type="checkbox" id="halfboard{{ $c}}"
                                                       name="halfboard{{ $c}}[]" {{( old('halfboard') == '1'  )? 'checked="checked"' :'' }} value="1" >
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
function aviablityCheck(arg){
    $this = arg;
    var datefromInput = $this.parent('.form-group').closest('.row').find('.checkInCls');
    var selDaysFeild  = $this.parent('.form-group').closest('.row').find('.dayscls');
    var sleepsFeild   = $this.parent('.form-group').closest('.row').find('.sleepsInputCls');
    var bedsFeild     = $this.parent('.form-group').closest('.row').find('.bedsInputCls');
    var dormFeild     = $this.parent('.form-group').closest('.row').find('.dormInputCls');
    ovelayLoading('add', 'tourbox') ;
    var start_from = datefromInput.val();
    $.ajax({
        url: '/mountainschool/checkAvailability',
        dataType: 'JSON',
        type: 'POST',
        async: false,
        data: {dateFrom: start_from, cabinId: datefromInput.data('cabinid'), selDays: selDaysFeild.val(), beds:bedsFeild.val(), dorms:dormFeild.val() , sleeps:sleepsFeild.val()} ,
        success: function (response) {
            datefromInput.next('.help-block').html('  ');
            datefromInput.parent('.form-group').removeClass('has-error');
            $('#newBooking').removeAttr('disabled');
            $('#hidProceed').val('valSuccess');

            ovelayLoading('remove') ;
        },
        error: function (err) {
            $('#newBooking').attr('disabled','disabled');
            $('#hidProceed').val('no');
            datefromInput.next('.help-block').html(' <strong>' + err. responseJSON.error + '</strong> ');
            datefromInput.parent('.form-group').addClass('has-error');
            ovelayLoading('remove') ;
        }
    });
}

    $(function () {

        $('.dayscls ').on("change", function () {


            var datefromInput = $(this).parent('.form-group').closest('.row').find('.checkInCls');

            changeNextCabinFromDate(datefromInput, $(this).val());


            aviablityCheck( $(this)  );

        });
        $('.bedsInputCls').on('keyup   change', function(e) {
            aviablityCheck( $(this)  );
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
    $('.checkInCls').each(function () {

        $(this).datepicker({
            dateFormat: "dd.mm.y",
            monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
            monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
            dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
            minDate: 0,

            onSelect: function (date) {

            if(  $(this).parent('.form-group').closest('.row').find('.dayscls').val() != ""){

                aviablityCheck( $(this) );
                changeNextCabinFromDate( $(this), $(this).parent('.form-group').closest('.row').find('.dayscls').val()) ;
            }



            },
            onChangeMonthYear: function (year, month, inst) {
                if (year != undefined && month != undefined) {
                    start_date = year + '-';
                    start_date += month + '-';
                    start_date += '01';
                }

                $.ajax({
                    url: '/mountainschool/calendarAvailability',
                    dataType: 'JSON',
                    type: 'POST',
                    async: false,
                    data: {dateFrom: start_date, cabinId: $(this).data('cabinid')},
                    success: function (response) {
                        //  console.log(response);
                        unavailableDates = response.disableDates;
                    },
                    error: function (err) {
                        alert(JSON.stringify(err));
                    }
                });

            },
            // beforeShowDay: colorize,


        });
    });
/*  changeNextCabinFromDate when select days and change  date after selet days*/
function changeNextCabinFromDate(datefromInput,daysVal) {
    var datePicId = datefromInput.attr('id');


    var selectedDate = $('#'+datePicId).datepicker('getDate');



    var msecsInADay = daysVal*86400000;
    var endDate = new Date(selectedDate.getTime() + msecsInADay);



    var dpId  =   $('#'+datePicId).attr('id') ;
    var nextDpIdstr =     dpId.split("check_in");
    var nextDpId = 'check_in'+ (parseInt(nextDpIdstr[1]) +1);
    $( '#'+nextDpId).datepicker( "option", "minDate", 0 );
    if(('#'+nextDpId).length >0 ){
        //Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
     $( '#'+nextDpId).datepicker('setDate', endDate);
     $( '#'+nextDpId).datepicker( "option", "minDate", endDate );
     $( '#'+nextDpId).datepicker( "option", "defaultDate", endDate );

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
    $('.guestsInputCls').keyup(function (data) {
        calcSleeps($(this), 'guidesInputCls');
    });
    $('.guidesInputCls').keyup(function (data) {
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


