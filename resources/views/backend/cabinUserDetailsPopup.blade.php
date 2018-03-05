
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">@lang("cabins.userUpdateModalHeading") <span class="sufixhfour">  &raquo  {{ $user->cabin_name_append }} </span> </h4>   </div>
            <div class="responseUpdateUserMessage"></div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalFirstName") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrFirstname}} </span>

                                  </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalLastName") </h4>
                                <p class="list-group-item-text">

                                    <span class="modalvalDisplay">   {{ $user->usrLastname}} </span>
                                     </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalEmail") </h4>
                                <p class="list-group-item-text">

                                    <span class="modalvalDisplay">   {{ $user->usrEmail}} </span>
                                  </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalTelephone") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrTelephone}} </span>
                                   </p></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalMobile") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrMobile}} </span>
                                </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalStreet") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrAddress}} </span>
                                    </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalZipcode") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrZip}} </span>
                                  </p></li>
                            <li class="list-group-item"><h4 class="list-group-item-heading">
                                    @lang("cabins.userUpdateModalCity") </h4>
                                <p class="list-group-item-text">
                                    <span class="modalvalDisplay">   {{ $user->usrCity}} </span>
                                     </p></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">@lang("cabins.closeBtn") </button>
            </div>
