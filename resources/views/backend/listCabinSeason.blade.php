<div class="col-md-6" id="summerSection">


    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('cabins.summerSeasonHeading') <i class="fa fa-question-circle"
                                                                         data-toggle="tooltip" data-placement="right"
                                                                         title="Die Sommersaison ist ca. im Zeitraum vom 01.05 – 31.10 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i>
            </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <tr>
                    <th>@lang('cabins.labelsummerSeasonYear')</th>
                    <th>@lang('cabins.labelSeasonStatus')</th>
                    <th>@lang('cabins.labelEarliestOpen')</th>
                    <th>@lang('cabins.labelEarliestClose')</th>
                    <th>@lang('cabins.labelLatestOpen') </th>
                    <th>@lang('cabins.labelLatestClose')</th>
                    <th>@lang('cabins.labelHoliday')</th>
                    <th> @lang('cabins.labelAction') </th>
                </tr>
                @if(isset($seasons))
                    @foreach ($seasons as $season)
                        @if($season->summerSeason == 1)
                            <tr id="summer_{{$season->_id}}" data-season="{{$season->_id}}" class="season-tr">
                                <td>{{ $season->summerSeasonYear }}</td>
                                <td>@if ($season->summerSeasonStatus === 'open') <span
                                            class="label label-success">@lang('cabins.summerSeasonChooseStatusLabelOpen')</span> @elseif ($season->summerSeasonStatus === 'close')
                                        <span class="label label-danger">@lang('cabins.summerSeasonChooseStatusLabelClose')</span> @else
                                        <span class="label label-default">@lang('cabins.noResult')</span> @endif</td>
                                <td>{{ $season->earliest_summer_open->format('d.m.y') }}</td>
                                <td>{{ $season->earliest_summer_close->format('d.m.y') }}</td>
                                <td>{{ $season->latest_summer_open->format('d.m.y') }}</td>
                                <td>{{ $season->latest_summer_close->format('d.m.y') }}</td>
                                <td>
                                    @if ($season->summer_mon == 1)
                                        <span class="label label-default">@lang('cabins.monday')</span>
                                    @endif

                                    @if ($season->summer_tue == 1)
                                        <span class="label label-default">@lang('cabins.tuesday')</span>
                                    @endif

                                    @if ($season->summer_wed == 1)
                                        <span class="label label-default">@lang('cabins.wednesday')</span>
                                    @endif

                                    @if ($season->summer_thu == 1)
                                        <span class="label label-default">@lang('cabins.thursday')</span>
                                    @endif

                                    @if ($season->summer_fri == 1)
                                        <span class="label label-default">@lang('cabins.friday')</span>
                                    @endif

                                    @if ($season->summer_sat == 1)
                                        <span class="label label-default">@lang('cabins.saturday')</span>
                                    @endif

                                    @if ($season->summer_sun == 1)
                                        <span class="label label-default">@lang('cabins.sunday')</span>
                                    @endif
                                </td>
                                <td> <a href="javascript:void(0)"
                                       class="btn  btn-primary btn-sm edit-season" style="padding: 0px 0px !important;"><i class="fa fa-fw fa-edit"></i></a>
                                    <a   style="margin:2% ; padding: 0px 0px !important;" href="javascript:void(0)" onclick="deleteSesonSummer('{{$season->_id}}' , '{{ csrf_token() }}')" class="btn  btn-danger btn-sm delete-season"   ><i class="fa fa-fw fa-trash"></i></a>

                                </td>
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

<div class="col-md-6" id="winterSection">
    <div class="box box-default box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">@lang('cabins.winterSeasonHeading') <i class="fa fa-question-circle"
                                                                         data-toggle="tooltip" data-placement="right"
                                                                         title="Die Wintersaison ist ca. im Zeitraum vom 01.11 – 30.04 Bitte wählen Sie das passende Jahr und den dazugehörigen Status aus."></i>
            </h3>
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
                    <th>@lang('cabins.labelsummerSeasonYear')</th>
                    <th>@lang('cabins.labelSeasonStatus')</th>
                    <th>@lang('cabins.labelEarliestOpen') </th>
                    <th>@lang('cabins.labelEarliestClose') </th>
                    <th>@lang('cabins.labelLatestOpen') </th>
                    <th>@lang('cabins.labelLatestClose') </th>
                    <th>@lang('cabins.labelHoliday')</th>
                    <th> @lang('cabins.labelAction')  </th>
                </tr>

                @if(isset($seasons))
                    @foreach ($seasons as $season)
                        @if($season->winterSeason == 1)
                        <tr   id="winter_{{$season->_id}}" data-season="{{$season->_id}}" class="season-tr">
                                <td>{{ $season->winterSeasonYear }}</td>
                                <td>@if ($season->winterSeasonStatus === 'open') <span
                                            class="label label-success">@lang('cabins.winterSeasonChooseStatusLabelOpen')</span> @elseif ($season->winterSeasonStatus === 'close')
                                        <span class="label label-danger">@lang('cabins.winterSeasonChooseStatusLabelClose')</span> @else
                                        <span class="label label-default">@lang('cabins.noResult')</span> @endif</td>
                                <td>{{ $season->earliest_winter_open->format('d.m.y') }}</td>
                                <td>{{ $season->earliest_winter_close->format('d.m.y') }}</td>
                                <td>{{ $season->latest_winter_open->format('d.m.y') }}</td>
                                <td>{{ $season->latest_winter_close->format('d.m.y') }}</td>
                                <td>
                                    @if ($season->winter_mon == 1)
                                        <span class="label label-default">@lang('cabins.monday')</span>
                                    @endif

                                    @if ($season->winter_tue == 1)
                                        <span class="label label-default">@lang('cabins.tuesday')</span>
                                    @endif

                                    @if ($season->winter_wed == 1)
                                        <span class="label label-default">@lang('cabins.wednesday')</span>
                                    @endif

                                    @if ($season->winter_thu == 1)
                                        <span class="label label-default">@lang('cabins.thursday')</span>
                                    @endif

                                    @if ($season->winter_fri == 1)
                                        <span class="label label-default">@lang('cabins.friday')</span>
                                    @endif

                                    @if ($season->winter_sat == 1)
                                        <span class="label label-default">@lang('cabins.saturday')</span>
                                    @endif

                                    @if ($season->winter_sun == 1)
                                        <span class="label label-default">@lang('cabins.sunday')</span>
                                    @endif
                                </td>
                            <td> <a href="javascript:void(0)"
                                    class="btn  btn-primary btn-sm edit-winter-season" style=" padding: 0px 0px !important;"><i class="fa fa-fw fa-edit"></i></a>
                                <a  style="margin:2%; padding: 0px 0px !important;" href="javascript:void(0)" onclick="deleteSesonWinter('{{$season->_id}}' , '{{ csrf_token() }}')" class="btn  btn-danger btn-sm delete-season"><i class="fa fa-fw fa-trash"></i></a>

                            </td>
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
<script>
    window.translations = {
        confirmDeleteWinterSeason: '{{ trans('cabins.confirmDeleteWinterSeason') }}',
        confirmDeleteSummerSeason: '{{ trans('cabins.confirmDeleteSummerSeason') }}'
    };
</script>