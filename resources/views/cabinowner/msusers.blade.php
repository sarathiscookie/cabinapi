@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: MountainSchool Details')
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
                @lang('msuserList.heading')
                <small>@lang('msuserList.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/dashboard"><i class="fa fa-dashboard"></i> @lang('msuserList.breadcrumbOne')</a></li>
                <li class="active">@lang('msuserList.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">@lang('msuserList.panelHeading')</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>
                            <table id="user_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th title="@lang('msuserList.usrCompany')">@lang('msuserList.usrCompany')</th>
                                    <th  title="@lang('msuserList.email')">@lang('msuserList.email')</th>
                                    <th  title="@lang('msuserList.lastName')">@lang('msuserList.lastName')</th>
                                    <th  title="@lang('msuserList.firstName')">@lang('msuserList.firstName')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <th><input type="text" id="2"  class="form-control input-sm search-input-name" placeholder="@lang('msuserList.searchCompany')"></th>
                                    <th><input type="text" id="3"  class="form-control input-sm search-input" placeholder="@lang('msuserList.searchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                </tr>
                                </tfoot>
                            </table>

                            <!-- Export buttons are append here -->

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
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <!-- User details Js -->
    <script src="{{ asset('js/msuserList.js') }}"></script>
@endsection
