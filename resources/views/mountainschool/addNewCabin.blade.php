<div class="box box-solid box-primary" style="border: 1px solid #605ca8;" id="newcabinRowID">
    <div class="box-header" style="background: #605ca8; background-color: #605ca8;">
        <h3 class="box-title"> @lang('tours.lblAddNewCab')</h3>
    </div><!-- /.box-header -->

    <div class="box-body">
        <div id="new_cabin">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('cabin_name') ? ' has-error' : '' }}">
                        <label>@lang('tours.lblCabinName') <span class="required">*</span></label>
                        <input name="cabin_name" type="text" id="cabin_name" placeholder="@lang('tours.lblCabinNamePH')" class="form-control" form="addcabinFrm">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('website') ? ' has-error' : '' }}">
                        <label>@lang('tours.lblWebsite') </label>
                        <input name="website" type="text" id="website" placeholder="@lang('tours.lblWebsitePH')" class="form-control" form="addcabinFrm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                        <label>@lang('tours.lblContactPerson') <span class="required">*</span></label>
                        <input name="contact_person" type="text" id="contact_person" placeholder="@lang('tours.lblContactPersonPH')" class="form-control" form="addcabinFrm">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label>@lang('tours.lblEmail') <span class="required">*</span></label>
                        <input name="email" type="text" id="email" placeholder="@lang('tours.lblEmailPH')" class="form-control" form="addcabinFrm">
                    </div>
                </div>
            </div>

        </div>
    </div><!-- /.box-body -->

    <div class="box-footer">
        <div class="row">
            <div class="col-md-12">
                <button type="button" class="btn btn-primary pull-right" name="createCabin" id="createCabin" data-loading-text="loading..." value="createCabin"><i class="fa fa-fw fa-save"></i> @lang('tours.btnSave')
                </button>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/tours.js') }}"></script>