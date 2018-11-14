@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Update Season Details')

@section('css')

    <!-- Date Range Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/datepicker/datepicker3.css') }}"/>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('cabins.seasonHeading')
                <small>@lang('cabins.seasonHeadingSub')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> @lang('cabins.breadcrumbOne')</a></li>
                <li><a href="{{ route('admin.cabins.cabinlite') }}"><i class="fa fa fa-table"></i> @lang('cabins.breadcrumbTwo')</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000"
                       data-close-others="false">
                        <i class="fa fa-home fa-fw" aria-hidden="true"></i>{{$cabin->name}} <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a tabindex="-1" href="#">{{$cabin->name}}</a></li>
                        <li class="divider"></li>
                        <li>
                            <li class="fa fa-edit" style="margin: 8px "></li>
                            <a tabindex="-1" href="{{ route('admin.cabinlite.edit', [ 'id' => $cabin->_id ]) }}">
                                @lang('cabins.menuInfo')
                            </a>
                        </li>
                        <li>
                            <li class="fa fa-edit" style="margin: 8px "></li>
                            <a tabindex="-1" href="{{ route('admin.cabinlite.contingent.edit', [ 'id' => $cabin->_id ]) }}">
                                @lang('cabins.menuContigent')
                            </a>
                        </li>
                        {{--<li>
                            <li class="fa fa-edit" style="margin: 8px "></li>
                            <a tabindex="-1" href="{{ route('admin.cabinlite.image.edit', [ 'id' => $cabin->_id ]) }}">
                                @lang('cabins.menuImages')
                            </a>
                        </li>--}}
                    </ul>
                </li>
                <li class="fa fa-edit active">@lang('cabins.breadcrumbseason')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                @lang('cabins.boxHeadingSeasonIndex')
                            </h4>
                            <a href="javascript:void(0)" id="crtNewSeason" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>
                                @lang('cabins.createNewSeasonButton')
                            </a>
                        </div>

                        @if (session('failure'))
                            <div class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('failure') }}
                            </div>
                        @endif

                    <!-- /.box-header -->

                        <div class="box-body">
                            <div class="row" id="crtSeasonDiv" style="display: none">
                                @include('backend.createCabinSeason')
                            </div>

                            <div class="row" id="listSeasonDiv" style="display:block">
                                @include('backend.listCabinSeason')
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-12"></div>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('js/cabinCrud.js') }}"></script>
    <script src="{{ asset('js/cabinSeason.js') }}"></script>
    <!-- Date Range Picker -->
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
@endsection


