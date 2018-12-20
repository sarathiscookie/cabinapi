@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Neighbour Cabin List')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('neighbourCabin.topHeading')
                <small>@lang('neighbourCabin.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('neighbourCabin.breadcrumbOne')</a></li>
                <li class="active">@lang('neighbourCabin.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('neighbourCabin.heading')</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>

                            <table id="normalCabin" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('neighbourCabin.cabinName')</th>
                                    <th>@lang('neighbourCabin.switchToNormal')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                        </div>
                        <!-- /.box-body -->

                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>

    <!-- Cabin details Js -->
    <script src="{{ asset('js/neighbourCabin.js') }}"></script>
@endsection
