@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Dashboard')

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

                            <p>Bookings</p>
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

                            <p>Sales</p>
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

                            <p>Users</p>
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
                            <h3>65</h3>

                            <p>Cabins</p>
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
                <section class="col-lg-12 connectedSortable">

                    <div class="box box-primary box-solid">
                        <div class="box-header with-border">
                            <i class="fa fa-th"></i>
                            <h3 class="box-title">@lang('statisticsAdmin.salesLabel')</h3>

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
                                    <label>@lang('bookingStatistics.cabinLabel'): </label>
                                    <select class="form-control cabins" style="width: 100%;" id="cabin">
                                        <option></option>
                                        <option value="allCabins">All Cabins</option>
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
                                    <label>@lang('bookingStatistics.daterangeLabel'): </label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="daterange" placeholder="@lang('bookingStatistics.dateRangePlaceholder')">
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

                            <div class="col-md-4 pull-right alert-graph">
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <!-- Chart JS - Sales -->
                        <div class="box-footer" id="graph-container">
                        </div>
                        <!-- /.box-footer -->
                    </div>

                </section>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-7 connectedSortable">
                    <!-- quick email widget -->
                    <div class="box box-primary">
                        <div class="box-header">
                            <i class="fa fa-envelope"></i>

                            <h3 class="box-title">Quick Email</h3>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-primary btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <div class="box-body">
                            <form action="#" method="post">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="emailto" placeholder="Email to:">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject">
                                </div>
                                <div>
                                    <textarea class="textarea" placeholder="Message" style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                                <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                    </div>
                </section>

                <!-- Right col -->
                <section class="col-lg-5 connectedSortable">
                    <!-- Calendar -->
                    <div class="box box-solid bg-green-gradient">
                        <div class="box-header">
                            <i class="fa fa-calendar"></i>

                            <h3 class="box-title">Calendar</h3>
                            <!-- tools box -->
                            <div class="pull-right box-tools">
                                <!-- button with a dropdown -->
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-bars"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#">Add new event</a></li>
                                        <li><a href="#">Clear events</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">View calendar</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <!--The calendar -->
                            <div id="calendar" style="width: 100%"></div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-black">
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Progress bars -->
                                    <div class="clearfix">
                                        <span class="pull-left">Task #1</span>
                                        <small class="pull-right">90%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 90%;"></div>
                                    </div>

                                    <div class="clearfix">
                                        <span class="pull-left">Task #2</span>
                                        <small class="pull-right">70%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 70%;"></div>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <div class="clearfix">
                                        <span class="pull-left">Task #3</span>
                                        <small class="pull-right">60%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 60%;"></div>
                                    </div>

                                    <div class="clearfix">
                                        <span class="pull-left">Task #4</span>
                                        <small class="pull-right">40%</small>
                                    </div>
                                    <div class="progress xs">
                                        <div class="progress-bar progress-bar-green" style="width: 40%;"></div>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box -->
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

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            cabinPlaceholder: '{{ trans('bookingStatistics.cabinPlaceholder') }}'
        };
    </script>

    <!-- Dashboard Js -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
