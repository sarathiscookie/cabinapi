<?php
//echo '<pre>';

//print_r($booking);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Booking Details</h4>
</div>
<div class="responseUpdateUserMessage"></div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">
                    <h4  class="list-group-item-heading">@lang("mountainschool.bookingNumber")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->invoice_number }} </span>
                    </p></li>
                <li class="list-group-item">
                    <h4 class="list-group-item-heading">@lang("mountainschool.cabinname") </h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->cabin_name }} </span>

                    </p></li>

                <li class="list-group-item"><h4  class="list-group-item-heading">@lang("mountainschool.tour_name")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->tour_name }} </span>
                    </p></li>
                @if ( $booking->ind_notice )
                <li class="list-group-item">
                    <h4 class="list-group-item-heading"> </h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->ind_notice }} </span>
                    </p></li>
                  @endif
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">
                    <h4 class="list-group-item-heading">@lang("mountainschool.from") - @lang("mountainschool.to") </h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->checkin_from }} -   {{ $booking->checkin_to }} </span>

                    </p></li>

                <li class="list-group-item"><h4  class="list-group-item-heading">@lang("mountainschool.total_guests"):</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->total_guests }} </span>
                    </p></li>
                <li class="list-group-item"><h4  class="list-group-item-heading">@lang("mountainschool.sleeps")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->sleeps }} </span>
                    </p></li>
                <li class="list-group-item"><h4  class="list-group-item-heading">@lang("mountainschool.status")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $booking->bookingStatusLabel  }}   </span>
                    </p></li>

            </ul>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">@lang("mountainschool.closeBtn")</button>
</div>