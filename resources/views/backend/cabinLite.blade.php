@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: Cabin Lite List')
@section('css')
    <!-- DataTables -->

    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

    <style type="text/css">
        .nounderline{
            text-decoration: none;
            cursor: pointer;
        }
        .modalvalDisplay{
            display: inline-block;
            max-width: 235px;
            width: 235px;
            min-height: 30px;
            border: 1px solid #D2D6DE;
            background-color: #EEEEEE;
            padding: 5px;
            overflow: auto;
        }
        .sufixhfour{
            font-size: 14px;
            font-weight: bold;
        }
    </style>
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

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">@lang('cabins.panelHeading')</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>
                            <table id="user_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('cabins.CabinCode')</th>
                                    <th>@lang('cabins.cabinName')</th>
                                    <th>@lang('cabins.email')</th>
                                    <th>@lang('cabins.userName')</th>
                                    <th>@lang('cabins.lastName')</th>
                                    <th>@lang('cabins.firstName')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input" placeholder="@lang('cabins.searchCabinLite')"></th>
                                    <th><input type="text" id="2"  class="form-control input-sm search-input-cabin" placeholder="@lang('cabins.searchCabin')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>

                            <!-- Export buttons are append here -->
                            <div class="panel-body">
                                <div id="buttons"></div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <!-- modal -->
                        <div class="modalUserList"></div>
                        <!-- /.modal -->

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




    <!-- User details Js -->
    <script src="{{ asset('js/cabin-lite.js') }}"></script>
@endsection
