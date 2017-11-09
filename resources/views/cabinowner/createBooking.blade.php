@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Create new booking')

@section('css')
    <!-- -->
    <link rel="stylesheet" type="text/css" href="{{ asset('') }}" />

    <style type="text/css">
        .required{
            color:red;
        }
    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Booking
                <small>New</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('details.breadcrumbOne')</a></li>
                <li class="fa fa-edit active">New Booking</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Check Availability</h4>
                        </div>

                        <form role="form" method="post" action="">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" name="updateCabin" value="updateCabin"><i class="fa fa-fw fa-save"></i>Check</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.col -->

                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Create Booking</h4>
                        </div>

                        <form role="form" method="post" action="">
                            {{ csrf_field() }}
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Test <span class="required">*</span></label>

                                            <input type="text" class="form-control" id="height" name="height" placeholder="" value="">
                                        </div>
                                    </div>

                                </div>

                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary pull-right" name="updateCabin" value="updateCabin"><i class="fa fa-fw fa-save"></i>Create Bookings</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.col -->

            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <!--  -->
    <script type="text/javascript" src="{{ asset('') }}"></script>

    <script>
    </script>
@endsection
