<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Styles -->
    {{--<link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('css/skins/skin-purple-light.min.css') }}">

    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-purple-light sidebar-mini">
@inject('miscellaneous', 'App\Http\Controllers\Cabinowner\DashboardController')
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="/cabinowner/dashboard" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>PI</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img src="/img/logo.png" style="max-height: 50px;" alt="Huetten-Holiday.de"></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <!-- <li class="dropdown messages-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success">4</span>
                        </a>
                    </li> -->
                    <!-- Notifications: style can be found in dropdown.less -->
                    <!-- <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">10</span>
                        </a>
                    </li> -->
                    <!-- Tasks: style can be found in dropdown.less -->
                    <!-- <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-danger">9</span>
                        </a>
                    </li> -->
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs">{!! $miscellaneous->cabinName() !!}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <p>
                                    @lang('cabinowner.welcomeToDashboard') - {{ Auth::user()->usrFirstname }} {{ Auth::user()->usrLastname }}
                                    {{--<small>Last login on 06.19.2017 12:30</small>
                                    <small>Last login on 05.19.2017 11:30</small>
                                    <small>Last login on 04.19.2017 10:30</small>
                                    <small>Last login on 03.19.2017 09:30</small>
                                    <small>Last login on 03.19.2017 09:30</small>
                                    <small>Last login on 03.19.2017 09:30</small>--}}
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn bg-purple btn-flat">@lang('cabinowner.profileDashboard')</a>
                                </div>
                                <div class="pull-right">

                                    <a href="{{ route('logout') }}" class="btn bg-purple btn-flat"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        @lang('cabinowner.logoutDashboard')
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>

                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <!-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> -->
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="@lang('cabinowner.searchSidebar')">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">@lang('cabinowner.menuSidebar')</li>
                {{--<li class="treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>@lang('cabinowner.userMenu')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active">
                            <a href="#"><i class="fa fa-circle-o"></i> @lang('cabinowner.userListMenu') <span class="pull-right-container"><span class="label label-primary pull-right">40</span></span></a>
                        </li>
                        <li><a href="#"><i class="fa fa-circle-o"></i> @lang('cabinowner.userStatisticsMenu')</a></li>
                    </ul>
                </li>--}}

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-table"></i> <span>@lang('cabinowner.bookingMenu')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="/cabinowner/bookings"><i class="fa fa-circle-o"></i> @lang('cabinowner.bookingListMenu') <span class="pull-right-container"><span class="label label-primary pull-right">{!! $miscellaneous->bookingCount() !!}</span></span></a>
                        </li>
                        {{--<li><a href=""><i class="fa fa-circle-o"></i> @lang('cabinowner.bookingStatisticsMenu')</a></li>--}}
                    </ul>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    @yield('content')

    <footer class="main-footer">
        <strong>Copyright &copy; 2017-2018 <a href="#">Huetten-Holiday</a>.</strong> All rights
        reserved.
    </footer>

</div>
<!-- ./wrapper -->

    <!-- Scripts -->
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.6 -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    @yield('scripts')

    <!-- Slimscroll -->
    <script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <!-- AdminLTE dashboard -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    {{--<script src="{{ asset('js/demo.js') }}"></script>--}}
</body>
</html>
