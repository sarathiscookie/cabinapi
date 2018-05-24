@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: New Booking')


@section('css')

    <!-- jQuery-ui -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <style type="text/css">
        .ui-datepicker td.ui-state-disabled {
            opacity: 100;
        }

        .notavilcls > span, .notavilcls a {
            background-image: none !important;
            color: White !important;
            opacity: 1 !important;
            background-color: rgba(255, 0, 0, 1) !important;;
        }

        .avilcls a {
            background-color: #008C46 !important;
        }

        .partialavilcls a {
            background-color: #FF9933 !important;
        }

        .gradient-gray {
            border-top: 1px solid #D2D6DE;
            background: linear-gradient(to right, rgb(236, 240, 245), rgb(255, 255, 255));
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            filter: alpha(opacity=70);
            -moz-opacity: 0.5;
            -khtml-opacity: 0.5;
            opacity: 0.5;
            z-index: 10000;

            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background: url('/img/loading.gif') center center no-repeat;
        }

        .cabinIndividuals {
            border: 1px solid #D2D6DE;
            margin: 5px;
            padding: 10px;
            background-color: #FFF;
        }
    </style>
@endsection
@inject('tours', 'App\Http\Controllers\mountainschool\TourController')
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
                <li><a href="/mountainschool/dashboard"><i
                                class="fa fa-dashboard"></i> @lang('mountainschool.breadcrumbOne')</a>
                </li>
                <li><a href="/mountainschool/bookings"><i class="fa fa-map-o"></i> @lang('mountainschool.breadcrumbTwo')
                    </a>
                </li>
                <li class="active">@lang('mountainschool.breadcrumbNewBooking')</li>
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


            <!--- form for add new Booking -->
            <form role="form" method="post" id="tourBookingFrm" name="tourBookingFrm"
                  action="{{ route('mountainschool.tours.bookingStore') }}">
                {{ csrf_field() }}
                <div class="box box-primary">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box-header with-border">
                                <h4 class="box-title"> @lang('mountainschool.nbBoxHeading')  </h4>
                            </div>
                            <div class="box-body" id="tourbox">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group   {{ $errors->has('tour_name') ? ' has-error' : '' }}">
                                            <label>@lang('mountainschool.lblTourName') <span
                                                        class="required">*</span></label>
                                            <select class="form-control" id="tour_name" name="tour_name">
                                                <option value="">@lang('mountainschool.lblTourNamePH')</option>
                                                @foreach($tours->toursList() as $key => $type)
                                                    <option value="{{ $type->_id  }}"
                                                    >{{ $type->tour_name }}</option>
                                                @endforeach
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
                                <button type="button" class="btn btn-primary  "
                                        name="duplicatingBooking" id="duplicatingBooking"
                                        data-loading-text="Loading..."
                                        value="duplicatingBooking"><i
                                            class="fa fa-fw fa-copy"></i>@lang('tours.btnDuplicatingBooking')
                                </button>
                                <button type="button" class="btn btn-primary "
                                        name="loadNew" id="loadNew"
                                        data-loading-text="Loading..."
                                        value="loadNew"><i
                                            class="fa fa-fw fa-table"></i>@lang('tours.btnNew')
                                </button>
                                <button type="button" class="btn btn-primary pull-right"
                                        name="newBooking" id="newBooking"
                                        data-loading-text="Adding..."
                                        value="newBooking"><i
                                            class="fa fa-fw fa-save"></i>@lang('tours.btnSave')
                                </button>
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
        /*  Duplicating Deatils*/
        $('#duplicatingBooking').click(function () {
            var $clone = $('.cabinPart:last').clone();
            $clone.find('input[name="ind_tour_no[]"]').val('');
            $clone.find('.has-error').removeClass('has-error');
            $clone.find('.help-block').html('');
            /* clone the selet box option too */
            //get original selects into a jq object
            var $originalSelects = $('.cabinPart:last').find('select');
            $clone.find('select').each(function (index, item) {
                //set new select to value of old select
                $(item).val($originalSelects.eq(index).val());
            });
            /* clone the selet box option too */
            $clone.appendTo('#appendDup');
            var posts = document.getElementsByClassName("cabinIndividuals");
            for (var i = 0; i < posts.length; i++) {
                posts[i].style["background-color"] = i % 2 === 0 ? "#FFFFFF" : "#F9FAFC";
            }
        });
        /*  When click on New button */
        $('#loadNew').click(function () {
            $("#tour_name").trigger("change");
        });
        /*  New booking functionality*/
        $('#newBooking').click(function () {
            //  ovelayLoading('add', divId); //adding loading effect
            if (checkIndivTourNumDup() != false) {
                var divId = 'tourbox';
                var url = '/mountainschool/bookingStore';
                var $btn = $(this);
                $('#' + divId).find('.has-error').removeClass('has-error');
                $('#' + divId).find('.help-block').html('<strong></strong>');
                $btn.button('loading');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("form").serialize() + '&' + $.param({'formPart': $btn.val()}),
                    success: function (data) {
                        //    ovelayLoading('remove');//remove loading effect
                        $btn.button('reset');
                        if ((data.errors)) {
                            $.each(data.errors, function (i, item) {

                                $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                                $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                $("input[name='" + i + "']").parent('.form-group').addClass('has-error');

                            });

                        } else {
                            //data = JSON.parse(data);
                        console.log(data.failureMsg);
                            if (data.successMsg !== undefined) {
                                var msgClass = 'alert-success';
                                var msgText = data.successMsg;
                            }
                            else {
                                var msgClass = 'alert-danger';
                                var msgText = data.failureMsg;
                            }

                            $(window).scrollTop(10);
                            var msg = '<div id="flash" class="alert ' + msgClass + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';
                            $("#tour_name").trigger("change");

                            $(msg).prependTo('#tourBookingFrm').fadeIn(100);
                            setTimeout(function () {
                                $('#tourBookingFrm #flash').fadeOut()
                            }, 2000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
                        // ovelayLoading('remove');//remove loading effect
                        if (jqXHR.status == 422) {
                            var errData = jqXHR.responseJSON;
                            $.each(errData, function (i, item) {
                                var spliKey = i.split('.');
                                var fname = spliKey[0];
                                $('input[name^="' + fname + '" ]').each(function (k, v) {
                                    if (spliKey[1] == k) {
                                        $(this).parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                        $(this).parent('.form-group').addClass('has-error');

                                    }

                                });
                                $('select[name^="' + fname + '" ]').each(function (k, v) {
                                    if (spliKey[1] == k) {
                                        $(this).parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                                        $(this).parent('.form-group').addClass('has-error');
                                    }

                                });

                            });

                            $btn.button('reset');
                        }
                    }
                });
            }
        });
        /* ------------------------------------- */
        $('#tour_name').change(function () {
            ovelayLoading('add', 'tourbox');
            var tourId = $('#tour_name').val();
            $.ajax({
                url: '/mountainschool/tours/gettour',
                data: {tourId: tourId},
                success: function (data) {
                    ovelayLoading('remove');
                    $('#cabindtls').html(data);
//console.log(data);
                },
            });
        });

        /* checking checkIndivTourNum duplication*/

        function checkIndivTourNumDup() {

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
        }

        /* Overlay after submit */
        function ovelayLoading(arg, appendDiv) {

            if (arg == 'add') {
                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo('#' + appendDiv);
            }
            if (arg == 'remove') {
                $("#overlay").remove();
            }
        }
    </script>

@endsection
