@extends('backend.layouts.app')

@section('title', 'Cabin API - Admin: User Details')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">

    <style type="text/css">
        .nounderline{
            text-decoration: none;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('userList.heading')
                <small>@lang('userList.controlPanel')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> @lang('userList.breadcrumbOne')</a></li>
                <li class="active">@lang('userList.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">@lang('userList.panelHeading')</h3>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>
                            <table id="user_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('userList.lastName')</th>
                                    <th>@lang('userList.firstName')</th>
                                    <th>@lang('userList.userName')</th>
                                    <th>@lang('userList.email')</th>
                                    <th>@lang('userList.amount')</th>
                                    <th>@lang('userList.bookingCount')</th>
                                    <th>@lang('userList.jumTo')</th>
                                    <th>@lang('userList.lastLogin')</th>
                                    <th>@lang('userList.rights')</th>
                                    <th>@lang('userList.actionOne')</th>
                                    <th>@lang('userList.actionTwo')</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <th><input type="text" id="4"  class="form-control input-sm search-input" placeholder="@lang('userList.searchEmail')"></th>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            userStatusResponseFailMsg: '{{ trans('userList.userStatusResponseFailMsg') }}'
        };
    </script>

    <!-- User details Js -->
    <script src="{{ asset('js/userList.js') }}"></script>
@endsection