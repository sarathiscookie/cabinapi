@extends('mountainschool.layouts.app')

@section('title', 'Cabin API - Mountain School: Tour List')


@section('css')
    <!-- DataTables -->

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
                @lang('tours.heading')
                <small>@lang('tours.smHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/mountainschool/dashboard"><i class="fa fa-dashboard"></i> @lang('tours.breadcrumbOne')</a></li>
                <li class="active">@lang('tours.breadcrumbTwo')</li>
            </ol>
        </section>


    <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
              <!---- Flash message-->
                    @if (session()->has('successMsgSave'))
                        <div id="flash" class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session()->get('successMsgSave') }}
                        </div>
                    @endif
                <!---- Flash message-->

                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">@lang('tours.panelHeading')</h3>
                            <a href="/mountainschool/tours/createtour" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>
                                @lang('tours.crtNewTour')        </a>
                        </div>
                        <!-- /.box-header -->

                        <div class="box-body table-responsive">
                            <div class="responseStatusMessage"></div>

                            <table id="tour_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>@lang('tours.tourNo')</th>
                                    <th>@lang('tours.tourName')</th>
                                    <th>@lang('tours.no_cabins')</th>
                                    <th>@lang('tours.cabins') </th>
                                    <th>@lang('tours.edit')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                <tr>
                                    <th><input type="text" id="0"  class="form-control input-sm search-input" placeholder="@lang('tours.searchTourCode')"></th>
                                    <th><input type="text" id="1"  class="form-control input-sm search-input-tourname" placeholder="@lang('tours.searchTourName')"></th>
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

                        <!-- Modal -->
                        <div id="owner_info" class="modal fade" role="dialog">

                        </div>
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


    <!-- Tours details Js -->
    <script src="{{ asset('js/tours.js') }}"></script>
@endsection
