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
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Hier können Sie all Ihre Preise zu den jeweiligen Kategorien eingeben.
Geben Sie immer eine Überschrift an der oberen Leiste und eine Überschrift an der Seitenleiste an. Anschließend können Sie die jeweiligen Preise hinzufügen. Preise bitte immer wie folgt angeben: „ 0,00€ “ ohne €! Mit + können Sie eine weitere Spalte erzeugen und mit – diese wieder entfernen."></i>
                            </h3>
                            <a href="/cabinowner/pricelist/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>
                                @if($count_pricetype === 0)
                                    @lang('pricelist.addPriceButton')
                                @else
                                    @lang('pricelist.updatePriceButton')
                                @endif
                            </a>
                        </div>

                        <div class="box-body table-responsive">
                            @if($count_pricetype === 0)
                                <p class="bg-info">@lang('pricelist.noPricelistAdded')</p>
                            @else
                                <table id="mtable" border="1" class="table table-bordered table-striped table-hover">
                                    <tbody>
                                    <tr><td style="align: center"></td>
                                        @for ($i = 1; $i <= $count_pricetype; $i++)
                                            <td></td>
                                        @endfor
                                    </tr>
                                    <tr>
                                        <td></td>
                                        @foreach ($price_type as $each_type)
                                            <th>{{$each_type}}</th>
                                        @endforeach
                                    </tr>

                                    @php
                                        $j=1;
                                        $k=0;
                                    @endphp

                                    @foreach ($guest_type as $guest)
                                        <tr>
                                            <td style="font-weight: bold;">{{$guest}}</td>
                                            @foreach ($price_type as $each_type)
                                                <td>{{$price[$k]}}</td>
                                                @php $k++; @endphp
                                            @endforeach
                                        </tr>
                                        @php $j++; @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
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
