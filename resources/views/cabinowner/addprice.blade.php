@extends('cabinowner.layouts.app')

@section('title', 'Cabin API - Cabin Owner: Opening & Closing Time')

@section('css')

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
                <li><i class="fa fa-edit"></i> @lang('openingClosingSeason.breadcrumbOne')</li>
                <li class="active">@lang('pricelist.pricelistPageHeading')</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <!-- general form elements -->

                        <div class="box-header with-border">
                            <h3 class="box-title">
                                @lang('pricelist.prices')
                            </h3>
                           <br/><br/>
                            <form class="form-horizontal" role="form" name="imageupload" method="POST" action="{!! url('/cabinowner/pricelist/store') !!}">
                                {{ csrf_field() }}
                                <table id="mtable" border="1" class="table table-bordered table-striped table-hover" >
                                    @if ($count_pricetype>0)
                                        <tbody>
                                        <tr><td style="align: center"></td>
                                            @for ($i=0;$i<=$count_pricetype;$i++)
                                                <td><button class="icol btn-primary">+</button>
                                                @if($i!=0)
                                                        <input type="button" class='cdel btn-primary' value='-' />
                                                    @endif
                                                </td>

                                            @endfor
                                        </tr>
                                        <tr><td><button class="irow btn-primary">+</button></td>
                                            <td></td>
                                            @foreach ($price_type as $each_type)
                                                <td><input class="form-control" name="price_type[]" value="{{$each_type}}" type="text"></td>
                                            @endforeach
                                        </tr>
                                        <?php $j=1;$k=0?>
                                        @foreach ($guest_type as $guest)
                                            <tr>
                                                <td><button class="irow btn-primary">+</button><input type="button" class='rdel btn-primary' value='-' /></td>
                                                <td><input class="form-control" name="guest_type[]" value="{{$guest}}" type="text"></td>
                                                @foreach ($price_type as $each_type)
                                                    <td><input class="form-control" name="price[]" value="{{$price[$k]}}" type="text"></td>
                                                    <?php $k++;?>
                                                @endforeach
                                            </tr>
                                            <?php $j++;?>
                                        @endforeach
                                        </tbody>
                                    @else

                                        <tbody>
                                            <tr>
                                                <td style="align: center"></td>
                                                <td><button class="icol btn-primary">+</button></td>
                                                <td><button class="icol btn-primary">+</button><input class="cdel btn-primary" value="-" type="button"></td>
                                            </tr>
                                            <tr>
                                                <td><button class="irow btn-primary">+</button></td>
                                                <td></td>
                                                <td><input class="form-control" name="price_type[]" value="" type="text"></td>
                                            </tr>
                                            <tr>
                                                <td><button class="irow btn-primary">+</button><input class="rdel btn-primary" value="-" type="button"></td>
                                                <td><input class="form-control" name="guest_type[]" value="" type="text"></td>
                                                <td><input class="form-control" name="price[]" value="" type="text"></td>
                                            </tr>
                                        </tbody>
                                    @endif
                                </table>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <button type="submit" class="btn btn-primary" name="Add"><i class="fa fa-fw fa-save"></i>@lang('pricelist.Save')</button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="responseMessage">@if(@$imagesSuccessStatus)<div class="alert alert-success alert-dismissible"> <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <h4><i class="icon fa fa-check"></i> {{$imagesSuccessStatus}} </h4></div>@endif</div>


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
            deleteRow: '{{ trans('pricelist.confirmDeleteRow') }}',
            deleteCol: '{{ trans('pricelist.confirmDeleteCol') }}',

        };
    </script>
@endsection
