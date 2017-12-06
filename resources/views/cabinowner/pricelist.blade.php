@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Opening & Closing Time')

@section('css')
    <style type="text/css">
        .nounderline {
            text-decoration: none;
            cursor: pointer;
        }
        .required{
            color:red;
        }
        .bg-primary {
            color: #fff;
            background-color: #337ab7;
            text-align: center;
            width: 202px;
            margin-top: -1%;
            padding: 1% 0;
            display: inline-block;
            margin-bottom: 0px;
        }
        .bg-info {
            text-align: center;
            width: 69%;
            margin-left: 10%;
            margin-top: 3%;
            padding: 5px 0 !important;
            float: left;
            margin-right: 2px;
        }
        .btn.btn-success.set_mainimg, .btn.btn-success.set_profileimg {
            width: 45%;
            margin-top: -1%;
            padding: 1% 0;
            display: inline-block;
        }
        .img_button{
            margin-top: 1%;
            padding: 1% 3%;
            display: inline-block;
        }
        .thumbnail{
            text-align: center;
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
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li><i class="fa fa-edit"></i> @lang('pricelist.prices')</li>
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
                                @lang('Prices')
                            </h3>
                            <a href="/cabinowner/pricelist/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i>
                                @if($count_pricetype == 0)
                                    @lang('pricelist.addPriceButton')
                                @else
                                    @lang('pricelist.updatePriceButton')
                                @endif
                            </a>
                        </div>

                        @if($count_pricetype == 0)
                            <p class="bg-info">@lang('pricelist.noPricelistAdded')</p>
                        @else
                                    <table id="mtable" border="1" class="table table-bordered table-striped table-hover">
                                        <tbody>
                                            <tr><td style="align: center"></td>
                                            @for ($i=1;$i<=$count_pricetype;$i++)
                                                <td></td>
                                            @endfor
                                            </tr>
                                            <tr>
                                                <td></td>
                                            @foreach ($price_type as $each_type)
                                                <th>{{$each_type}}</th>
                                            @endforeach
                                            </tr>
                                            <?php $j=1;$k=0?>
                                            @foreach ($guest_type as $guest)
                                            <tr>
                                                <td style="font-weight: bold;">{{$guest}}</td>
                                                @foreach ($price_type as $each_type)
                                                    <td>{{$price[$k]}}</td>
                                                    <?php $k++;?>
                                                @endforeach
                                            </tr>
                                            <?php $j++;?>
                                            @endforeach
                                        </tbody>

                                    </table>
                            @endif
                        </div>

                        <!-- /.box-header -->
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <!-- /.content-wrapper -->
    <script src="{{ asset('js/pricelist.js') }}"></script>
    <!-- Helping object for translation -->
    <script>
        window.translations = {
            deleteImage: '{{ trans('image.confirmDeleteImage') }}',

        };
    </script>
@endsection
