@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Dashboard')

@section('css')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/flat/blue.css') }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>150</h3>

                            <p>New Bookings</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>

                            <p>Last month bookings</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>44</h3>

                            <p>New users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>65%</h3>

                            <p>Sales</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="nav-tabs-custom">
                        <!-- Tabs within a box -->
                        <ul class="nav nav-tabs pull-right">
                            <li class="active"><a href="#revenue-chart" data-toggle="tab">Cabin One</a></li>
                            <li><a href="#sales-chart" data-toggle="tab">Cabin Two</a></li>
                            <li class="pull-left header"><i class="fa fa-inbox"></i> Booking</li>
                        </ul>
                        <div class="tab-content no-padding">
                            <!-- Morris chart - Sales -->
                            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;"></div>
                            <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;"></div>
                        </div>
                    </div>
                    <!-- /.nav-tabs-custom -->
                </section>

                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <section class="col-lg-5 connectedSortable">
                    <!-- Map box -->
                    <div class="box box-solid bg-light-blue-gradient">
                        <div class="box-header">
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range">
                                    <i class="fa fa-calendar"></i></button>
                                <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
                                    <i class="fa fa-minus"></i></button>
                            </div>
                            <!-- /. tools -->

                            <i class="fa fa-map-marker"></i>

                            <h3 class="box-title">
                                Visitors
                            </h3>
                        </div>
                        <div class="box-body">
                            <div id="world-map" style="height: 250px; width: 100%;"></div>
                        </div>
                        <!-- /.box-body-->
                        <div class="box-footer no-border">
                            <div class="row">
                                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                    <div id="sparkline-1"></div>
                                    <div class="knob-label">Visitors</div>
                                </div>
                                <!-- ./col -->
                                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                                    <div id="sparkline-2"></div>
                                    <div class="knob-label">Online</div>
                                </div>
                                <!-- ./col -->
                                <div class="col-xs-4 text-center">
                                    <div id="sparkline-3"></div>
                                    <div class="knob-label">Exists</div>
                                </div>
                                <!-- ./col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box -->
                </section>
                <!-- right col -->
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap -->
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/knob/jquery.knob.js') }}"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
@endsection
