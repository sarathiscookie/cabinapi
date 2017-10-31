@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Cabin Information')

@section('css')
    <style type="text/css">
        .list-group-item{
            cursor: default;
        }
    </style>
@endsection

@inject('cabinInfo', 'App\Http\Controllers\Cabinowner\DetailsController')

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
                            <h4 class="box-title">@lang('details.billingBoxHeading')</h4>
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
                                            <strong>@lang('details.billingLabelCompanyName')</strong>
                                            <p class="text-muted"> @isset($userDetails) {{ $userDetails->company }} @endisset</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelZip')</strong>
                                            <p class="text-muted">{{ $cabin->zip }}</p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelPlace')</strong>
                                            <p class="text-muted">{{ $cabin->place }}</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelStreet')</strong>
                                            <p class="text-muted">{{ $cabin->street }}</p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelLegal')</strong>
                                            <p class="text-muted">{{ $cabin->legal }}</p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelTax')</strong>
                                            <p><span class="label label-default">{{ $cabin->tax }}</span></p>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelVat')</strong>
                                            <p><span class="label label-default">{{ $cabin->vat }}</span></p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>@lang('details.billingLabelFax')</strong>
                                            <p class="text-muted">{{ $cabin->fax }}</p>
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

                            @empty($cabin)
                               <p class="text-muted">@lang('details.noResult')</p>
                            @endempty
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box billing -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">@lang('details.cabinBoxHeading')</h4>
                        </div>

                        @isset($cabin)
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelName')</strong>
                                                <p class="list-group-item-text">{{ $cabin->name  }}</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelHeight')</strong>
                                                <p class="list-group-item-text">{{ $cabin->height }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelClub')</strong>
                                                <p class="list-group-item-text">{{ $cabin->club }}</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelCancel')</strong>
                                                <p class="list-group-item-text">@lang('details.cancelDeadlineBegin') {{ $cabin->reservation_cancel }} @lang('details.cancelDeadlineEnd')</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelAvailability')</strong>
                                                <p class="list-group-item-text">{{ $cabin->reachable }}</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelTour')</strong>
                                                <p class="list-group-item-text">{{ $cabin->tours }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelCheckIn')</strong>
                                                <p class="list-group-item-text">{{ $cabin->checkin_from }}</p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelCheckOut')</strong>
                                                <p class="list-group-item-text">{{ $cabin->reservation_to }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelFacility')</strong> <br>
                                                @foreach($cabin->interior as $interior)
                                                    <span class="label label-default">{{ $cabinInfo->interiorLabel($interior) }}</span>
                                                @endforeach
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelPrice')</strong>
                                                <p class="list-group-item-text">{{ $cabin->halfboard_price }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelPayType')</strong> <br>
                                                @foreach($cabin->payment_type as $payment)
                                                    @if($payment == '0')
                                                        <span class="label label-default">@lang('details.cabinBoxLabelPayTypeCash')</span>
                                                    @endif
                                                    @if($payment == '1')
                                                        <span class="label label-default">@lang('details.cabinBoxLabelPayTypeDebit')</span>
                                                    @endif
                                                    @if($payment == '2')
                                                        <span class="label label-default">@lang('details.cabinBoxLabelPayTypeCredit')</span>
                                                    @endif
                                                @endforeach
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelNeighbour')</strong> <br>
                                                @foreach($cabin->neighbour_cabin as $neighbour_cabin)
                                                    <span class="label label-default">{{ $cabinInfo->neighbourCabin($neighbour_cabin) }}</span>
                                                @endforeach
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelDeposit')</strong>
                                                <p class="list-group-item-text">{{ $cabin->prepayment_amount }}</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelWebsite')</strong>
                                                <p class="list-group-item-text">{{ $cabin->website }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelMoreDetails')</strong>
                                                <p class="list-group-item-text">{{ $cabin->other_details }}</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelRegion')</strong>
                                                <p class="list-group-item-text">{{ $cabin->region }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelLatitude')</strong>
                                                <p class="list-group-item-text">{{ $cabin->latitude }}</p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item">
                                                <strong>@lang('details.cabinBoxLabelLongitude')</strong>
                                                <p class="list-group-item-text">{{ $cabin->longitude }}</p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <a href="" class="btn btn-primary btn-block"><i class="fa fa-fw fa-edit"></i>@lang('details.contactLabelEditButton')</a>
                                    </div>
                                </div>
                            </div>
                        @endisset

                        @empty($cabin)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item">
                                            <p class="list-group-item-text">@lang('details.noResult')</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endempty

                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection