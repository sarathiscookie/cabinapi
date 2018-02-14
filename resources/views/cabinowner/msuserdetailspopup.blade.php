<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">@lang("msuserList.userUpdateModalHeading")  <span class="sufixhfour">  &raquo  {{ $userList->company }} </span> </h4>
</div>
<div class="responseUpdateUserMessage"></div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item">
                    <h4 class="list-group-item-heading">@lang("msuserList.userUpdateModalFirstName") </h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $userList->usrFirstname }} </span>

                    </p></li>
                <li class="list-group-item"><h4  class="list-group-item-heading">@lang("msuserList.userUpdateModalLastName")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $userList->usrLastname }} </span>
                    </p></li>
                <li class="list-group-item">
                    <h4 class="list-group-item-heading">@lang("msuserList.userUpdateModalEmail")</h4>
                    <p class="list-group-item-text">

                        <span class="modalvalDisplay">   {{ $userList->usrEmail }} </span>
                    </p></li>
                <li class="list-group-item"><h4 class="list-group-item-heading">
                        @lang("msuserList.userUpdateModalTelephone")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $userList->usrTelephone }} </span>
                    </p></li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                <li class="list-group-item"><h4 class="list-group-item-heading">
                        @lang("msuserList.userUpdateModalMobile")</h4>
                    <p class="list-group-item-text">

                        <span class="modalvalDisplay">   {{ $userList->usrMobile }} </span>
                    </p></li>
                <li class="list-group-item"><h4 class="list-group-item-heading">
                        @lang("msuserList.userUpdateModalStreet")</h4>
                    <p class="list-group-item-text">

                        <span class="modalvalDisplay">   {{ $userList->usrAddress }} </span>

                    </p></li>
                <li class="list-group-item"><h4 class="list-group-item-heading">
                        @lang("msuserList.userUpdateModalZipcode")</h4>
                    <p class="list-group-item-text">

                        <span class="modalvalDisplay">   {{ $userList->usrZip }} </span>
                    </p></li>
                <li class="list-group-item"><h4 class="list-group-item-heading">
                        @lang("msuserList.userUpdateModalCity")</h4>
                    <p class="list-group-item-text">
                        <span class="modalvalDisplay">   {{ $userList->usrCity }} </span>
                    </p></li>
            </ul>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">@lang("msuserList.closeBtn")</button>
</div>
            