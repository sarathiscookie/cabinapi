@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Cabin Information')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('details.heading')
                <small>@lang('details.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('details.breadcrumbOne')</a></li>
                <li><i class="active"></i> @lang('details.breadcrumbTwo')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    <!--Contact Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">@lang('details.boxHeading')</h4>
                        </div>

                        @if (session('successContact'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('successContact') }}
                            </div>
                        @endif

                        <div class="box-body">
                            <ul class="list-group">
                                @isset($userDetails)
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelFirstName'):</b> <a class="pull-right">{{ $userDetails->usrFirstname }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelLastName'):</b> <a class="pull-right">{{ $userDetails->usrLastname }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelEmail'):</b> <a class="pull-right">{{ $userDetails->usrEmail }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelMobile'):</b> <a class="pull-right">{{ $userDetails->usrMobile }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelPhone'):</b> <a class="pull-right">{{ $userDetails->usrTelephone }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelZip'):</b> <a class="pull-right">{{ $userDetails->usrZip }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelCity'):</b> <a class="pull-right">{{ $userDetails->usrCity }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelStreet'):</b> <a class="pull-right">{{ $userDetails->usrAddress }}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>@lang('details.contactLabelCountry'):</b> <a class="pull-right">{{ $userDetails->usrCountry }}</a>
                                    </li>
                                @endisset

                                @empty($userDetails)
                                    <li class="list-group-item">
                                        <a><span class="label label-default">@lang('details.noResult')</span></a>
                                    </li>
                                @endempty

                            </ul>

                            <a href="/cabinowner/details/contact" class="btn btn-primary btn-block"><i class="fa fa-fw fa-edit"></i>@lang('details.contactLabelEditButton')</a>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box contact -->

                    <!-- Billing Box -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">Billing Information</h4>
                        </div>
                        <!-- /.box-header -->

                        @if (session('successBilling'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('successBilling') }}
                            </div>
                        @endif

                        <div class="box-body">
                            @isset($cabin)
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Legal Form</strong>
                                            <p class="text-muted">{{ $cabin->legal }}</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Tax Id Number</strong>
                                            <p><span class="label label-default">{{ $cabin->tax }}</span></p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Phone No</strong>
                                            <p class="text-muted">{{ $cabin->telephone }}</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Company Zip Code</strong>
                                            <p class="text-muted">{{ $cabin->zip }}</p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Company City</strong>
                                            <p class="text-muted">{{ $cabin->place }}</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Company Street</strong>
                                            <p class="text-muted">{{ $cabin->street }}</p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="/cabinowner/details/billing" class="btn btn-primary btn-block"><i class="fa fa-fw fa-edit"></i>@lang('details.contactLabelEditButton')</a>
                                        </div>
                                    </div>

                                </div>
                            @endisset

                            @empty($userDetails)
                               <p class="text-muted">@lang('details.noResult')</p>
                            @endempty
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box billing -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab">Cabin</a></li>
                            <li><a href="#timeline" data-toggle="tab">Facility</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="activity">
                                <!-- Post -->
                                <div class="post">
                                    <div class="user-block">
                                        {{--<img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                        <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                                        <span class="description">Shared publicly - 7:30 PM today</span>--}}
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore the hate as they create awesome
                                        tools to help create filler text for everyone from bacon lovers
                                        to Charlie Sheen fans.
                                    </p>
                                    {{--<ul class="list-inline">
                                        <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                        <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                        </li>
                                        <li class="pull-right">
                                            <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                                (5)</a></li>
                                    </ul>

                                    <input class="form-control input-sm" type="text" placeholder="Type a comment">--}}
                                </div>
                                <!-- /.post -->

                                <!-- Post -->
                                <div class="post clearfix">
                                    <div class="user-block">
                                        {{--<img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                                        <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                                        <span class="description">Sent you a message - 3 days ago</span>--}}
                                    </div>
                                    <!-- /.user-block -->
                                    <p>
                                        Lorem ipsum represents a long-held tradition for designers,
                                        typographers and the like. Some people hate it and argue for
                                        its demise, but others ignore the hate as they create awesome
                                        tools to help create filler text for everyone from bacon lovers
                                        to Charlie Sheen fans.
                                    </p>
                                </div>
                                <!-- /.post -->
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection