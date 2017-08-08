@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Bookings Statistics')

@section('css')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}" />
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@inject('dashboard', 'App\Http\Controllers\DashboardController')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('admin.bookings')
                <small>@lang('admin.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('admin.dashboard')</a></li>
                <li><i class="fa fa-table"></i> @lang('admin.bookings')</li>
                <li class="active">Statistics</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">

                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-th"></i>
                            <h3 class="box-title">Booking Statistics</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->

                        <!-- Select box for cabins or regions -->
                        <div class="box-body">
                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('statisticsAdmin.chooseCabin'): </label>
                                    <select class="form-control cabins" style="width: 100%;" id="cabin">
                                        <optgroup label="Cabins">
                                            @foreach($dashboard->cabins() as $cabin )
                                                <option>{{$cabin->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>

                            <!-- Date range -->
                            <div class="col-md-3 pull-left">
                                <div class="form-group">
                                    <label>@lang('statisticsAdmin.dateRange'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="daterange">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- /.form group -->
                            </div>

                            <div class="col-md-2 pull-left">
                                <div class="form-group">
                                    <label></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-primary" id="generate" data-loading-text="Generating..." autocomplete="off">@lang('statisticsAdmin.generateButton')</button>
                                    </div>
                                </div>
                                <!-- /.form group -->
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer" id="graph-container">
                            <!-- Chart JS - Sales -->
                            <canvas id="lineChartSales" style="height: 400px;"></canvas>
                        </div>
                        <!-- /.box-footer -->
                    </div>

                </section>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Chart.js -->
    <script src="{{ asset('plugins/chartJS/Chart.min.js') }}"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <!-- Statistics JS -->
    <script>
        $(function () {

            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //Initialize Select2 Elements
            $(".cabins").select2({
                placeholder: "select a cabin",
                allowClear: true
            });

            /* Date range picker */
            var start = moment().subtract(29, 'days');
            var end = moment();

            $('#daterange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Letzten 7 Tage': [moment().subtract(7, 'days'), moment()],
                    'Letzten 30 Tage': [moment().subtract(30, 'days'), moment()],
                    'Dieser Monat': [moment().startOf('month'), moment().endOf('month')],
                    'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'DD.MM.YYYY',
                    applyLabel: "Bestätigen",
                    cancelLabel: "Löschen",
                    daysOfWeek: [
                        "So",
                        "Mo",
                        "Di",
                        "Mi",
                        "Do",
                        "Fr",
                        "Sa"
                    ],
                }
            });

            /* Chart generate */
            $('#generate').on('click', function() {
                var $btn      = $(this).button('loading');
                var cabin     = $('.cabins').val();
                var dates     = $('#daterange').val();
                var daterange = dates.replace(/\s/g, '');

                $.ajax({
                    url: '/admin/dashboard/sales/graph',
                    dataType: "json",
                    type: "POST",
                    data:{ daterange:daterange, cabin:cabin}
                })
                    .done(function( response ) {
                        $('#lineChartSales').remove();
                        $('#graph-container').append('<canvas id="lineChartSales" style="height: 400px;"></canvas>');

                        var lineChart = {
                            labels: ["01-07-17", "02-07-17", "03-07-17", "04-07-17", "05-07-17", "06-07-17"],
                            datasets: [{
                                label: 'Fix',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255,99,132,1)',
                                borderWidth: 1,
                                data: ["20", "18", "11", "40", "50", "70"]
                            },
                                {
                                    label: 'Storniert',
                                    backgroundColor: 'rgba(79, 196, 127, 0.2)',
                                    borderColor: 'rgba(79, 196, 127, 1)',
                                    data: ["50", "38", "111", "20", "10", "70"]
                                },
                                {
                                    label: 'Abgeschlossen',
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    data: ["90", "28", "12", "60", "70", "80"]
                                },
                                {
                                    label: 'Anfrage',
                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    data: ["60", "10", "40", "50", "20", "100"]
                                },
                                {
                                    label: 'Warten auf Zahlung',
                                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                    borderColor: 'rgba(255, 159, 64, 1)',
                                    data: ["75", "35", "85", "25", "95", "10"]
                                }
                            ]
                        };

                        var ctx = document.getElementById('lineChartSales').getContext('2d');
                        var chart = new Chart(ctx, {
                            type: 'line',
                            data: lineChart,
                            options: {
                                elements: {
                                    rectangle: {
                                        fill: false,
                                        lineTension: 0.1,
                                        borderCapStyle: 'butt',
                                        borderDash: [],
                                        borderDashOffset: 0.0,
                                        borderJoinStyle: 'miter',
                                        pointBorderColor: "rgba(75,192,192,1)",
                                        pointBackgroundColor: "#fff",
                                        pointBorderWidth: 1,
                                        pointHoverRadius: 5,
                                        pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                        pointHoverBorderColor: "rgba(220,220,220,1)",
                                        pointHoverBorderWidth: 2,
                                        pointRadius: 1,
                                        pointHitRadius: 10,
                                    }
                                },
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    xAxes: [{
                                        scaleLabel: {
                                            display: true,
                                            labelString: "Dates",
                                        }
                                    }],
                                    yAxes: [{
                                        scaleLabel: {
                                            display: true,
                                            labelString: "Count",
                                        }
                                    }]
                                }
                            }
                        });

                        $btn.button('reset');
                    })
                    .fail(function() {
                        alert( "error" );
                        $btn.button('reset');
                    });
            });


            /* Calender */
            $("#calendar").datepicker();
        });

    </script>
@endsection