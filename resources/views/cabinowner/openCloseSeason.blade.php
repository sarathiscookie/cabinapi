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
    </style>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                @lang('openingClosingSeason.pageHeading')
                <small>@lang('openingClosingSeason.smallHeading')</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="/cabinowner/bookings"><i class="fa fa-dashboard"></i> @lang('openingClosingSeason.dashboard')</a></li>
                <li class="active"><i class="fa fa-clock-o"></i> @lang('openingClosingSeason.breadcrumbTwo')</li>
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
                                @lang('openingClosingSeason.listHeading')
                            </h3>
                            <a href="/cabinowner/season/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-fw fa-save"></i> @lang('openingClosingSeason.createNewSeasonButton')</a>
                        </div>

                        @if (session('successMsgSeasonSave'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('successMsgSeasonSave') }}
                            </div>
                        @endif

                        @if (session('successSummer'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('successSummer') }}
                            </div>
                        @endif

                        @if (session('successWinter'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session('successWinter') }}
                            </div>
                        @endif

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-default box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">@lang('openingClosingSeason.summerSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Sommersaison ist ca. im Zeitraum vom 01.05 – 31.10 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>@lang('openingClosingSeason.labelsummerSeasonYear')</th>
                                                    <th>@lang('openingClosingSeason.labelSeasonStatus')</th>
                                                    <th>@lang('openingClosingSeason.labelEarliestOpen')</th>
                                                    <th>@lang('openingClosingSeason.labelEarliestClose')</th>
                                                    <th>@lang('openingClosingSeason.labelLatestOpen')</th>
                                                    <th>@lang('openingClosingSeason.labelLatestClose')</th>
                                                    <th>@lang('openingClosingSeason.labelHoliday')</th>
                                                    <th>@lang('openingClosingSeason.labelAction')</th>
                                                </tr>
                                                @php
                                                    $i = 1;
                                                    $j = 1;
                                                @endphp
                                                @if(isset($seasons))
                                                    @foreach ($seasons as $season)
                                                        @if($season->summerSeason == 1)
                                                            <tr>
                                                                <td>{{ $i++  }}</td>
                                                                <td>{{ $season->summerSeasonYear }}</td>
                                                                <td>@if ($season->summerSeasonStatus === 'open') <span class="label label-success">@lang('openingClosingSeason.summerSeasonChooseStatusLabelOpen')</span> @elseif ($season->summerSeasonStatus === 'close') <span class="label label-danger">@lang('openingClosingSeason.summerSeasonChooseStatusLabelClose')</span> @else <span class="label label-default">@lang('openingClosingSeason.noResult')</span> @endif</td>
                                                                <td>{{ $season->earliest_summer_open->format('d.m.y') }}</td>
                                                                <td>{{ $season->earliest_summer_close->format('d.m.y') }}</td>
                                                                <td>{{ $season->latest_summer_open->format('d.m.y') }}</td>
                                                                <td>{{ $season->latest_summer_close->format('d.m.y') }}</td>
                                                                <td>
                                                                    @if ($season->summer_mon == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.monday')</span>
                                                                    @endif

                                                                    @if ($season->summer_tue == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.tuesday')</span>
                                                                    @endif

                                                                    @if ($season->summer_wed == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.wednesday')</span>
                                                                    @endif

                                                                    @if ($season->summer_thu == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.thursday')</span>
                                                                    @endif

                                                                    @if ($season->summer_fri == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.friday')</span>
                                                                    @endif

                                                                    @if ($season->summer_sat == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.saturday')</span>
                                                                    @endif

                                                                    @if ($season->summer_sun == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.sunday')</span>
                                                                    @endif
                                                                </td>
                                                                <td><a href="/cabinowner/season/summer/edit/{{$season->_id}}" class="btn btn-block btn-primary btn-sm"><i class="fa fa-fw fa-edit"></i> @lang('openingClosingSeason.editButton')</a></td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </table>

                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-default box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">@lang('openingClosingSeason.winterSeasonHeading') <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 31.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i></h3>
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body table-responsive no-padding">
                                            @if (session('successMsgWinterSave'))
                                                <div class="alert alert-success">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    {{ session('successMsgWinterSave') }}
                                                </div>
                                            @endif
                                            <table class="table table-hover">
                                                <tr>
                                                    <th>Id</th>
                                                    <th>@lang('openingClosingSeason.labelsummerSeasonYear')</th>
                                                    <th>@lang('openingClosingSeason.labelSeasonStatus')</th>
                                                    <th>@lang('openingClosingSeason.labelEarliestOpen')</th>
                                                    <th>@lang('openingClosingSeason.labelEarliestClose')</th>
                                                    <th>@lang('openingClosingSeason.labelLatestOpen')</th>
                                                    <th>@lang('openingClosingSeason.labelLatestClose')</th>
                                                    <th>@lang('openingClosingSeason.labelHoliday')</th>
                                                    <th>@lang('openingClosingSeason.labelAction')</th>
                                                </tr>

                                                @if(isset($seasons))
                                                    @foreach ($seasons as $season)
                                                        @if($season->winterSeason == 1)
                                                            <tr>
                                                                <td>{{ $j++  }}</td>
                                                                <td>{{ $season->winterSeasonYear }}</td>
                                                                <td>@if ($season->winterSeasonStatus === 'open') <span class="label label-success">@lang('openingClosingSeason.winterSeasonChooseStatusLabelOpen')</span> @elseif ($season->winterSeasonStatus === 'close') <span class="label label-danger">@lang('openingClosingSeason.winterSeasonChooseStatusLabelClose')</span> @else <span class="label label-default">@lang('openingClosingSeason.noResult')</span> @endif</td>
                                                                <td>{{ $season->earliest_winter_open->format('d.m.y') }}</td>
                                                                <td>{{ $season->earliest_winter_close->format('d.m.y') }}</td>
                                                                <td>{{ $season->latest_winter_open->format('d.m.y') }}</td>
                                                                <td>{{ $season->latest_winter_close->format('d.m.y') }}</td>
                                                                <td>
                                                                    @if ($season->winter_mon == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.monday')</span>
                                                                    @endif

                                                                    @if ($season->winter_tue == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.tuesday')</span>
                                                                    @endif

                                                                    @if ($season->winter_wed == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.wednesday')</span>
                                                                    @endif

                                                                    @if ($season->winter_thu == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.thursday')</span>
                                                                    @endif

                                                                    @if ($season->winter_fri == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.friday')</span>
                                                                    @endif

                                                                    @if ($season->winter_sat == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.saturday')</span>
                                                                    @endif

                                                                    @if ($season->winter_sun == 1)
                                                                        <span class="label label-default">@lang('openingClosingSeason.sunday')</span>
                                                                    @endif
                                                                </td>
                                                                <td><a href="/cabinowner/season/winter/edit/{{$season->_id}}" class="btn btn-block btn-primary btn-sm"><i class="fa fa-fw fa-edit"></i> @lang('openingClosingSeason.editButton')</a></td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </table>

                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>

                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')
    <script>
        /* Tooltip */
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endsection