@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Add Price List')

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
                <li><a href="/cabinowner/pricelist"><i class="fa fa-edit"></i> @lang('pricelist.prices')</a></li>
                <li class="active">@lang('pricelist.priceCreate')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('pricelist.priceCreate')
                                <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Hier können Sie all Ihre Preise zu den jeweiligen Kategorien eingeben.
Geben Sie immer eine Überschrift an der oberen Leiste und eine Überschrift an der Seitenleiste an. Anschließend können Sie die jeweiligen Preise hinzufügen."></i>
                            </h3>
                        </div>

                        <div class="box-body">
                            <!-- general form elements -->
                            @isset($cabin)
                                <form class="form-horizontal" role="form" name="imageupload" method="POST" action="{!! url('/cabinowner/pricelist/store') !!}">
                                    {{ csrf_field() }}
                                    <table id="mtable" border="1" class="table table-bordered table-striped table-hover" >
                                        @if(!empty($cabin->price_type) && !empty($cabin->guest_type) && !empty($cabin->price) && count($cabin->price_type) > 0)
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                @for ($i = 0; $i <= count($cabin->price_type); $i++)
                                                    <td class="btnTD"><button class="icol btn-primary">+</button>
                                                        @if($i != 0)
                                                            <input type="button" class='cdel btn-primary' value='-' />
                                                        @endif
                                                    </td>
                                                @endfor
                                            </tr>
                                            <tr><td class="btnTD"><button class="irow btn-primary">+</button></td>
                                                <td></td>
                                                @foreach ($cabin->price_type as $each_type)
                                                    <td><input class="form-control" name="price_type[]" value="{{$each_type}}" type="text"></td>
                                                @endforeach
                                            </tr>


                                            @php
                                                $j = 1;
                                                $k = 0;
                                            @endphp


                                            @foreach ($cabin->guest_type as $guest)
                                                <tr>
                                                    <td class="btnTD"><button class="irow btn-primary">+</button><input type="button" class='rdel btn-primary' value='-' /></td>
                                                    <td><input class="form-control" name="guest_type[]" value="{{$guest}}" type="text"></td>
                                                    @foreach ($cabin->price_type as $each_type)
                                                        <td><input class="form-control" name="price[]" value="{{$cabin->price[$k]}}" type="text"></td>
                                                        @php $k++; @endphp
                                                    @endforeach
                                                </tr>
                                                @php $j++; @endphp
                                            @endforeach
                                            </tbody>

                                        @else

                                            <tbody>
                                            <tr>
                                                <th style="text-align: center">Liste</th>
                                                <td class="btnTD"><button class="icol btn-primary">+</button></td>
                                                <td class="btnTD"><button class="icol btn-primary">+</button><input class="cdel btn-primary" value="-" type="button"></td>
                                            </tr>
                                            <tr>
                                                <td class="btnTD"><button class="irow btn-primary">+</button></td>
                                                <td></td>
                                                <td><input class="form-control" name="price_type[]" value="" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td class="btnTD"><button class="irow btn-primary">+</button><input class="rdel btn-primary" value="-" type="button"></td>
                                                <td><input class="form-control" name="guest_type[]" value="" type="text"></td>
                                                <td><input class="form-control" name="price[]" value="" type="text"></td>
                                            </tr>
                                            </tbody>

                                        @endif
                                    </table>

                                    <div class="row">
                                        <div class="col-md-12">

                                            <button type="submit" class="btn btn-primary" name="Add"><i class="fa fa-fw fa-save"></i>@lang('pricelist.Save')</button>
                                        </div>
                                    </div>

                                </form>
                            @endisset
                        </div>
                    </div>
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
