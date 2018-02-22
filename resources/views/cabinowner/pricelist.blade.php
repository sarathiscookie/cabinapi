@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Price List')

@section('css')
    <style type="text/css">

        .bg-info {
            text-align: center;
            width: 69%;
            margin-left: 10%;
            margin-top: 3%;
            padding: 5px 0 !important;
            float: left;
            margin-right: 2px;
        }

    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('pricelist.pricelistPageHeading')
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('pricelist.dashboard')</a></li>
                <li class="active">@lang('pricelist.prices')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('pricelist.prices')
                            </h3>
                            <a href="/cabinowner/pricelist/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>
                                @if(isset($cabin) && count($cabin->price_type) === 0)
                                    @lang('pricelist.addPriceButton')
                                @else
                                    @lang('pricelist.updatePriceButton')
                                @endif
                            </a>
                        </div>

                        @if (session()->has('success'))
                            <div id="flash" class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        @if (session()->has('failure'))
                            <div id="flash" class="alert alert-danger">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session()->get('failure') }}
                            </div>
                        @endif

                        <div class="box-body table-responsive">
                            @isset($cabin)
                                @if(!empty($cabin->price_type) && !empty($cabin->guest_type) && !empty($cabin->price) && count($cabin->price_type) > 0)
                                    <table class="table table-bordered table-striped table-hover table-responsive">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            @foreach ($cabin->price_type as $each_type)
                                                <th>{{$each_type}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>

                                        @php
                                            $j = 1;
                                            $k = 0;
                                            setlocale(LC_MONETARY, 'de_DE');
                                        @endphp

                                        <tbody>
                                        @foreach ($cabin->guest_type as $guest)
                                            <tr>
                                                <td style="font-weight: bold;">{{$guest}}</td>
                                                @foreach ($cabin->price_type as $each_type)
                                                    <td>{{ money_format('%i', $cabin->price[$k])}} &euro;</td>
                                                    @php $k++; @endphp
                                                @endforeach
                                            </tr>
                                            @php $j++; @endphp
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="bg-info">@lang('pricelist.noPricelistAdded')</p>
                                @endif
                            @endisset

                            @empty($cabin)
                                <p class="bg-info">@lang('pricelist.noPricelistAdded')</p>
                            @endempty
                        </div>

                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/pricelist.js') }}"></script>

    <!-- Helping object for translation -->
    <script>
        window.translations = {
            deleteRow: '{{ trans('pricelist.confirmDeleteRow') }}',
            deleteCol: '{{ trans('pricelist.confirmDeleteCol') }}'
        };
    </script>

@endsection
