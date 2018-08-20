@extends('backend.layouts.app')

@section('title', 'Cabin API - Temporary Cart Deleted')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Deleted Cart Data</h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body table-responsive">

                            <table id="booking_data" class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.bookingNumber')</th>
                                    <th>@lang('admin.firstName')</th>
                                    <th>@lang('admin.lastName')</th>
                                    <th>@lang('admin.email')</th>
                                    <th>@lang('admin.txid')</th>
									<th>Excel-Data</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                @php
                                    $i = 1;
                                    $bookings = \App\Booking::where('is_delete', 1)->where('status', '8')->paginate(100);
                                @endphp
                                @foreach($bookings as $booking)
                                    @php $user = \App\Userlist::where('is_delete', 0)->find($booking->user); @endphp
									<!-- Some quick and dirty stuff from Daniel to compare against the data from PMI / PAYONE EXPORT !-->
									@php 
										$handle = fopen('../resources/tempbookings/completepmibookings.csv', "r");
										$header = true;

										$exceltext = "";
										$excelfounds = 0;
										
										while ($csvLine = fgetcsv($handle, 1000, ";")) {
											if ($header) {
												$header = false;
											} else {
												if( $csvLine[13] == $user->usrEmail);
												$exceltext = "User gefunden: ";
												$excelfounds++;
											}
										}
									
									@endphp
									
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $booking->invoice_number }}</td>
                                    <td>{{ $user->usrFirstname }}</td>
                                    <td>{{ $user->usrLastname }}</td>
                                    <td>{{ $user->usrEmail }}</td>
                                    <td>{{ $booking->txid }}</td>
									<td>{{ $exceltext }} {{ $excelfounds }} x</td>
                                </tr>
                                @endforeach
                                </tfoot>

                            </table>

                            <!-- Export buttons are append here -->
                            <div class="panel-body">
                                <div id="buttons"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        {{ $bookings->links() }}

                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

