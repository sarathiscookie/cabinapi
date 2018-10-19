@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Edit Tour')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('tours.heading')
                <small>@lang('tours.smHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/bookings"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a>
                </li>
                <li><a href="/mountainschool/tours"><i class="fa fa-map-o"></i> @lang('tours.breadcrumbtourList')</a>
                </li>
                <li class="active">@lang('tours.breadcrumbEditTour')</li>
            </ol>
        </section>

        @if (session()->has('successMsgSave'))
            <div id="flash" class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session()->get('successMsgSave') }}
            </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <!--- form for new cabins [form tag is appended for fields  in addNewCabin blade]-->
            <form id="addcabinFrm" name="addcabinFrm" mathod="post"></form>

            <!--- form for update Tour -->
            <form role="form" method="post" id="uptTourFrm" action="{{ route('mountainschool.tours.store') }}">

                {{ csrf_field() }}

                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('tours.editBoxHeading')  </h4>
                            </div>

                            <div class="box-body" id="tourbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tour_name') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblTourName') <span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" id="tour_name"
                                                   name="tour_name"
                                                   value="{{ old('tour_name'  , $tour->tour_name) }}"
                                                   placeholder="@lang('tours.lblTourNamePH')" maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('tour_name') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('tour_no') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblTourNo') <span
                                                        class="required">*</span></label>
                                            <input type="text" class="form-control" id="tour_no"
                                                   name="tour_no"
                                                   value="{{ old('tour_no' , $tour->tour_no) }}"
                                                   placeholder="@lang('tours.lblTourNoPH')" maxlength="100">
                                            <span class="help-block"><strong>  {{ $errors->first('tour_no') }}</strong></span>
                                        </div>
                                    </div>

                                </div>

                                @php
                                    $a = 0;
                                @endphp

                                @if(isset($tour))
                                    @foreach($tour->cabins  as $key => $val)
                                        @php ($a++)
                                        <div class="row" id="crowid{{$a}}" rid="{{$a}}">
                                            <div class="col-md-6">
                                                <div class="form-group"><label> Cabin {{$a}}<span class="required">*</span></label>
                                                    <input readonly="readonly" name="cabins[]" class="form-control" value="{{$val}}" type="text"></div>
                                            </div>
                                            <div class="col-md-1"><a href="javascript:void(0)" class="delRow"> <img src="/img/delete.png" alt="Remove" hight="25" style=" position: relative; bottom: -30px;" width="25"></a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if(isset($cabins))
                                    <div class="row" id="tour_cabins_row">
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('tour_cabins') ? ' has-error' : '' }}">
                                                <label>@lang('tours.lblCabin') <span class="required">*</span></label>
                                                <select id="tour_cabins" name="tour_cabins" class="form-control">
                                                    <option value="">@lang('tours.lblCabinPH')</option>
                                                    <option value="new_cabin">@lang('tours.CreateNewCabinLabel')</option>
                                                    @foreach($cabins  as $Key => $val )
                                                        <option value="{{$val->name}}">{{$val->name}}</option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="no_cabins" value="{{count($tour->cabins )}}" id="no_cabins">
                                                <input type="hidden" name="udtId" value="{{$tour->_id }}" id="udtId">

                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary pull-right" name="updateTour" id="updateTour" data-loading-text="Updating..." value="updateTour"><i class="fa fa-fw fa-save"></i>@lang('tours.btnSave')</button>
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
        /* Checking for the CSRF token */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var glo_i           = $('#no_cabins').val(); // global declaration for cabins select
        var lblCabin        = 'Cabin ';
        var lblReqSpanCabin = '<span  class="required">*</span>';

        $('#tour_cabins').change(function () {
            if ($('#tour_cabins').val() != '') {
                if (checkNewCabinSection() != false)         // use this if you are using id to check
                {
                    ovelayLoading('add', 'tourbox'); //adding loading effect

                    glo_i++;
                    if ($('#tour_cabins').val() == 'new_cabin') {
                        // $("#newcabinRowID").closest('div[class^="row"]').remove();
                        var arg = $("#createCabin").parents('.box-solid').closest('.row').attr('rid');
                        if (arg != undefined) {
                            cabinCountFilter(arg);
                        }
                        addNewCabin(glo_i);
                    }
                    else {
                        var html = ' <div class="row" id="crowid' + glo_i + '" rid="' + glo_i + '"><div class="col-md-6"  >    <div class="form-group"  >      <label> ' + lblCabin + glo_i + lblReqSpanCabin + '</label><input type="text" readonly="readonly" name="cabins[]" class="form-control" value="' + $('#tour_cabins').val() + '">  </div>   </div><div class="col-md-1"  ><a href="javascript:void(0)"  class="delRow"> <img src="/img/delete.png" alt="Remove" width="25" hight="25" style=" position: relative; bottom: -30px;"></a>  </div> </div>';

                        $(html).insertBefore("#tour_cabins_row");
                        $("#tour_cabins").val("").attr("selected", "selected"); // reset selectbox
                    }

                    cabinRequiredMark();

                    ovelayLoading('remove'); //remove loading effect
                }
            }
        });

        /* To rearrange id if cabin deleted*/
        function cabinCountFilter(arg) {
            $('#crowid' + arg).remove();
            for (var k = arg; k <= glo_i; k++) {
                var j = k - 1;
                $('#crowid' + k).attr('rid', j); // should be above "    $('#crowid'+k).attr('id','crowid'+j);"
                $('#crowid' + k + '  > div > div > label').html(lblCabin + j + lblReqSpanCabin);
                $('#crowid' + k).attr('id', 'crowid' + j);
            }
            glo_i = glo_i - 1;
        }

        /* To delete cabin */
        $(document).on('click', '.delRow', function () {
            var arg = $(this).closest('div[class^="row"]').attr('rid');
            var lbl = $(this).closest('label').text();
            $(this).parent('div').attr('id');
            cabinCountFilter(arg);
            cabinRequiredMark();
        });

        /* Functionality to update tour */
        $(document).on('click', '#updateTour', function (e) {
            var divId = 'tourbox';
            $('#' + divId).find('.has-error').removeClass('has-error');
            $('#' + divId).find('.help-block').html('<strong></strong>');

            if (checkNewCabinSection() != false)         // check new cabin add section exits
            {
                $('#no_cabins').val(glo_i);
                ovelayLoading('add', divId); //adding loading effect
                var $btn = $(this);
                $btn.button('loading');
                $.ajax({
                    type: "POST",
                    url: '/mountainschool/tours/updatetour',
                    data: $("#uptTourFrm").serialize() + '&' + $.param({'formPart': $btn.val()}),
                    dataType: 'JSON',
                    success: function (data) {

                        ovelayLoading('remove');//remove loading effect

                        $btn.button('reset');
                        if ((data.errors)) {
                            $.each(data.errors, function (i, item) {
                                $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                                $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                            });
                        }
                        else {
                            if (data.errorMsg != undefined) {
                                var msgClass = 'alert-danger';
                                var msgText = data.errorMsg;
                            }
                            else {
                                var msgClass = 'alert-success';
                                var msgText = data.successMsg;
                                window.location.href = "/mountainschool/tours";
                            }

                            var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';
                            $(msg).prependTo('#' + divId).fadeIn(100);
                            setTimeout(function () {
                                $('#' + divId + ' #flash').fadeOut()
                            }, 2000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                        ovelayLoading('remove');//remove loading effect
                        if (jqXHR.status == 422) {
                            var errData = jqXHR.responseJSON;
                            $.each(errData, function (i, item) {
                                $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                                $("input[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                                $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                                $("select[name='" + i + "']").after(' <span class="help-block"> <strong>' + item[0] + '</strong></span> ');
                            });
                            $btn.button('reset');
                        }
                    }
                });
            }
        });

        /*Required symbol change*/
        function cabinRequiredMark()
        {
            if(glo_i > 0) {
                $('#tour_cabins').prev('label').find('span[class^="required"]').html('');
            }
            else {
                $('#tour_cabins').prev('label').find('span[class^="required"]').html('*');
            }
        }
    </script>

    <!-- Tours details Js -->
    <script src="{{ asset('js/tours.js') }}"></script>
@endsection
