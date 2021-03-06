@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Cabin List')

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
                @lang('cabins.heading')
                <small>@lang('cabins.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('cabins.breadcrumbOne')</a></li>
                <li class="active">@lang('cabins.breadcrumbTwo')</li>
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
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">@lang('cabins.panelHeading')</h3>
                            <a href="/admin/cabinlite/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>@lang('cabins.crtNewCabin')</a>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>

                            <table id="cabin_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('cabins.CabinCode')</th>
                                    <th>@lang('cabins.cabinName')</th>
                                    <th>@lang('cabins.email')</th>
                                    <th>@lang('cabins.name') </th>
                                    <th>@lang('cabins.cabinType')</th>
                                    <th>@lang('cabins.switchToNeighbour')</th>
                                    <th>@lang('cabins.update')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('cabins.searchCabinLite')"></th>
                                    <th><input type="text" id="2"  class="form-control input-sm search-input-cabin" placeholder="@lang('cabins.searchCabin')"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </tfoot>
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
    <script src="{{ asset('js/cabin-lite.js') }}"></script>
@endsection
