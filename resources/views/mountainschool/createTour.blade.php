@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Create Tour')

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
                <li><a href="/mountainschool/tours"><i class="fa fa-map-marker"></i> @lang('tours.breadcrumbtourList')</a>
                </li>
                <li class="active">@lang('tours.breadcrumbCreateTour')</li>
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
            <!--- form for add new cabins [form tag is appended for fileds  in addNewCabin blade]-->
            <form id="addcabinFrm" name="addcabinFrm" mathod="post"></form>
            <!--- form for add new cabins ends-->
            <!--- form for add new Tour -->
            <form role="form" method="post" id="addTourFrm" action="{{ route('mountainschool.tours.store') }}">
                {{ csrf_field() }}
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('tours.boxHeading')  </h4>
                            </div>

                            <div class="box-body" id="tourbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tour_name') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblTourName') <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="tour_name" name="tour_name" value="{{ old('tour_name') }}" placeholder="@lang('tours.lblTourNamePH')" maxlength="100">
                                            <span class="help-block"><strong>{{ $errors->first('tour_name') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tour_no') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblTourNo') <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="tour_no" name="tour_no" value="{{ old('tour_no') }}" placeholder="@lang('tours.lblTourNoPH')" maxlength="100">
                                            <span class="help-block"><strong>{{ $errors->first('tour_no') }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="tour_cabins_row">
                                    <div class="col-md-6">
                                        <div class="form-group {{ $errors->has('tour_cabins') ? ' has-error' : '' }}">
                                            <label>@lang('tours.lblCabin') <span class="required">*</span></label>
                                            <select id="tour_cabins" name="tour_cabins" class="form-control">
                                                <option value="">@lang('tours.lblCabinPH')</option>
                                                <optgroup label="@lang('tours.registeredCabin')">
                                                    @foreach($cabins  as $Key => $val )
                                                        @if($val->other_cabin == '0')
                                                            <option value="{{$val->name}}">{{$val->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="@lang('tours.neighborCabin')">
                                                    @foreach($cabins  as $Key => $val )
                                                        @if($val->other_cabin == '1')
                                                            <option value="{{$val->name}}">{{$val->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                                <optgroup label="@lang('tours.createNewCabin')">
                                                    <option value="new_cabin">@lang('tours.CreateNewCabinLabel')</option>
                                                </optgroup>
                                            </select>
                                            <input type="hidden" name="no_cabins" id="no_cabins">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary pull-right" name="createTour" id="createTour" data-loading-text="@lang('tours.creatingLoader')" value="createTour"><i class="fa fa-fw fa-save"></i>@lang('tours.btnSave')</button>
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

        var glo_i           = 0;
        var lblCabin        = '{{ __('tours.lblCabin') }}';
        var lblReqSpanCabin = '<span  class="required">*</span>';
        $('#tour_cabins').change(function () {
            if ($('#tour_cabins').val() != '') {
                if (checkNewCabinSection() != false)
                {
                    ovelayLoading('add', 'tourbox');
                    glo_i++;
                    if ($('#tour_cabins').val() == 'new_cabin') {
                        var arg = $("#createCabin").parents('.box-solid').closest('.row').attr('rid');
                        if (arg != undefined) {
                            cabinCountFilter(arg);
                        }
                        addNewCabin(glo_i);
                    }
                    else {
                        var html = ' <div class="row" id="crowid' + glo_i + '" rid="' + glo_i + '"><div class="col-md-6"  >    <div class="form-group"  >      <label> ' + lblCabin + ' ' + glo_i + lblReqSpanCabin + '</label><input type="text" readonly="readonly" name="cabins[]" class="form-control" value="' + $('#tour_cabins').val() + '">  </div>   </div><div class="col-md-1"  ><a href="javascript:void(0)"  class="delRow"> <img src="/img/delete.png" alt="Remove" width="25" hight="25" style=" position: relative; bottom: -30px;"></a>  </div> </div>';

                        $(html).insertBefore("#tour_cabins_row");
                        $("#tour_cabins").val("").attr("selected","selected");
                    }

                    cabinRequiredMark();
                    ovelayLoading('remove');
                }
            }
        });

        /* for rearranging id if cabin deleted*/
        function cabinCountFilter(arg) {
            $('#crowid' + arg).remove();

            for (var k = arg; k <= glo_i; k++) {
                var j = k - 1;
                $('#crowid' + k).attr('rid', j);
                $('#crowid' + k + '  > div > div > label'). html(lblCabin + j + lblReqSpanCabin);
                $('#crowid' + k).attr('id', 'crowid' + j);
            }
            glo_i = glo_i - 1;
        }

        /* for delete*/
        $(document).on('click', '.delRow', function () {
            var arg = $(this).closest('div[class^="row"]').attr('rid');
            var lbl = $(this).closest('label').text();
            $(this).parent('div').attr('id');
            cabinCountFilter(arg);
            cabinRequiredMark();
        });

        /* Create tour function*/
        $(document).on('click', '#createTour', function (e) {

            var divId = 'tourbox';

            $('#' + divId).find('.has-error').removeClass('has-error');
            $('#' + divId).find('.help-block').html('<strong></strong>');

            if (checkNewCabinSection() != false)
            {
                $('#no_cabins').val(glo_i);

                ovelayLoading('add', divId);
                var $btn = $(this);

                $btn.button('loading');
                $.ajax({
                    type: "POST",
                    url: '/mountainschool/tours/store',
                    data: $("#addTourFrm").serialize() + '&' + $.param({'formPart': $btn.val()}),
                    dataType: 'JSON',
                    success: function (data) {
                        ovelayLoading('remove');

                        $btn.button('reset');

                        if((data.errors)) {
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
                                var msgText  = data.errorMsg;
                            }
                            else {
                                var msgClass = 'alert-success';
                                var msgText  = data.successMsg;
                                window.location.href = "/mountainschool/tours";
                            }

                            var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                            $(msg).prependTo('#' + divId).fadeIn(100);

                            setTimeout(function () {
                                $('#' + divId + ' #flash').fadeOut()
                            }, 2000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        ovelayLoading('remove');

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
        function cabinRequiredMark(){
            if(glo_i >0){
                $('#tour_cabins').prev('label').find('span[class^="required"]').html('');
            }else{
                $('#tour_cabins').prev('label').find('span[class^="required"]').html('*');
            }
        }
    </script>

    <!-- Tours details Js -->
    <script src="{{ asset('js/tours.js') }}"></script>
@endsection
