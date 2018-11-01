@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: New Booking')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('mountainschool.nbHeading')
                <small>@lang('mountainschool.nbSubHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('mountainschool.breadcrumbOne')</a></li>
                <li class="active">@lang('mountainschool.breadcrumbNewBooking')</li>
            </ol>
        </section>

        @if(session()->has('successMsgSave'))
            <div id="flash" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session()->get('successMsgSave') }}
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <form role="form" method="post" id="tourBookingFrm" name="tourBookingFrm">
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('mountainschool.nbBoxHeading')  </h4>
                            </div>

                            <div class="box-body" id="tourbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tour_name') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblTourName') <span class="required">*</span></label>
                                            <select class="form-control" id="tour_name" name="tour_name">
                                                <option value="">@lang('mountainschool.lblTourNamePH')</option>
                                                @if(isset($tourList))
                                                    @foreach($tourList as $key => $type)
                                                        @if($type->tour_name != '')
                                                            <option value="{{ $type->_id  }}">{{ $type->tour_name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            <span class="help-block"><strong>  {{ $errors->first('tour_name') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="cabindtls">
                                    <div class="col-md-6"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                {{--<button type="button" class="btn btn-primary" name="duplicatingBooking" id="duplicatingBooking" data-loading-text="Loading..." value="duplicatingBooking"><i class="fa fa-fw fa-copy"></i>@lang('tours.btnDuplicatingBooking')</button>
                                <button type="button" class="btn btn-primary " name="loadNew" id="loadNew" data-loading-text="Loading..." value="loadNew"><i class="fa fa-fw fa-table"></i>@lang('tours.btnNew')</button>--}}
                                <button style="display: none;" type="button" class="btn btn-primary pull-right" name="newBooking" id="newBooking" data-loading-text="loading..." value="newBooking"><i class="fa fa-fw fa-save"></i>@lang('tours.btnSave')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <script>

        // ovelayLoading('add', 'tourbox');
        // url: '/mountainschool/checkAvailability',
        // $('#hidProceed').val('valSuccess');
        // ovelayLoading('remove');
        
        /*var count = 0;
        var datePickerOption = {
            dateFormat: "dd.mm.y",
            minDate: 0,
            onSelect: function(selectedDate) {}
        }

        // Duplicating Deatils
        $('#duplicatingBooking').click(function (e) {
            e.preventDefault();
            count++;
            var $clone = $('.cabinPart:last').clone();
            $clone.find('input[name="ind_tour_no[]"]').val('');
            $clone.find('.checkInCls').attr('id' ,'');
            $clone.find('#removeDup').remove();
            var removeHtml = '<div class="row" id="removeDup"> <div class="col-md-12"> <img src="/img/delete.png" class="removeDupCls" alt="Remove" hight="25" style="float: right  " width="25">' +
                             ' </div></div>';
            $clone.find('.cabinIndividuals'). prepend(removeHtml);
            $($clone.find('.checkInCls')).each(function (k,item) {
                 $(this).attr('id', 'check_in' + count+k)
            });

           //.datepicker(datePickerOption);
            $clone.find('.checkInCls').removeClass('hasDatepicker');
            $clone.find('.has-error').removeClass('has-error');
            $clone.find('.help-block').html('');
            //clone the selet box option too
            //get original selects into a jq object
            var $originalSelects = $('.cabinPart:last').find('select');
            $clone.find('select').each(function (index, item) {
                //set new select to value of old select
                $(item).val($originalSelects.eq(index).val());
            });

            // clone the select box option too
            $clone.appendTo('#appendDup');
            var posts = document.getElementsByClassName("cabinIndividuals");
            for (var i = 0; i < posts.length; i++) {
                posts[i].style["background-color"] = i % 2 === 0 ? "#FFFFFF" : "#F9FAFC";
            }

            calendaerDisp();
        });

         // Remove Duplicate Booking
        $('#cabindtls').on('click', '.removeDupCls', function() {
          $(this).parents('.cabinPart').remove();
        });

        //  When click on New button
        $('#loadNew').click(function () {
            $("#tour_name").trigger("change");
        });

        function calendaerDisp() {
           $('.checkInCls').each(function () {
               $(this).datepicker({
                   dateFormat: "dd.mm.y",
                   monthNames: ['Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'],
                   monthNamesShort: ["Jan", "Feb", "Mär", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez"],
                   dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
                   minDate: 0,

                   onSelect: function (date) {
                       if ($(this).parent('.form-group').closest('.row').find('.dayscls').val() != "") {
                           aviablityCheck($(this));
                           changeNextCabinFromDate($(this), $(this).parent('.form-group').closest('.row').find('.dayscls').val());
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
                   }
               });
           });
        }*/

        /* checking checkIndivTourNum duplication*/
        /*function checkIndivTourNumDup() {
            var array = $("input[name='ind_tour_no[]']")
                .map(function () {
                    return this.value; // $(this).val()
                }).get();

            var res = true;
            var temp = [];
            $.each(array, function (key, value) {
                // console.log(     key );
                if ($.inArray(value, temp) === -1) {
                    temp.push(value);
                } else {
                    $.each($("input[name='ind_tour_no[]']"), function (inkey, invalue) {
                        if (key == inkey) {
                            $(this).parent('.form-group').children('.help-block').html(' <strong>"' + value + '" Individual Tour No is a duplicate value' + '</strong> ');
                            $(this).parent('.form-group').addClass('has-error');
                        }
                    });
                    //  console.log(value +" is a duplicate value");
                    res = false;
                }
            });
            return res;
        }*/
    </script>
@endsection
