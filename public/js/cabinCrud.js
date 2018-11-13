/* Add more functinality for neighbour cabins */
$(document).ready(function () {
    var next = 0;

    $(".add-more-dynamic , .addNeighbourlbl").click(function (e) {
        e.preventDefault();
        $('#add_more').hide();
        var addto = "#addtoDiv";
        next = next + 1;
        var field_name = "other_neighbour_cabin[name][]";
        var field_url_name = "other_neighbour_cabin[url][]";
        var newIn = '<div class="row" id="other_neighbour_cabin_row' + next + '" > <div class="col-md-4"> <label>Name</label><input autocomplete="off" class="input form-control other-neighbour-cabin" id="other_neighbour_cabin' + next + '" name="' + field_name + '" type="text" /></div> <div class="col-md-6"> <label>Website</label><input  class="input form-control other-neighbour-cabin"   name="' + field_url_name + '" type="text"></div><div class="col-md-2 neighBtns">  <img src="/img/delete.png" alt="Remove"  id="remove' + (next  ) + '" style=" " class=" remove-me" for="' + (next  ) + '"  title ="Remove" width="25px" height="25px" ></div></div>';
        var newInput = $(newIn);

        $(addto).before(newInput);
        $("#count").val(next);
        $('.remove-me').unbind().on('click', function (e) {
            e.preventDefault();
            /* if only one while removing hiding the div which contains first field */
            if ($('.other-neighbour-cabin').length == 1) {
                $('#add_more').show();
            }
            else {
                var fieldNum = $(this).attr('for');
                var fieldID = "#other_neighbour_cabin" + fieldNum;
                var rowId = '#other_neighbour_cabin_row' + fieldNum;
                $(this).remove();
                $(fieldID).remove();
                $(rowId).remove();
            }
            return false;
        });
    });

});

$(document).ready(function () {
    /* Multiple select for neighbour cabins */
    if ($(".neighbour")[0]){
        $(".neighbour").select2();
    }

    /* Multiple select for facility of cabins */
    if ($("#facility")[0]){
        $("#facility").select2();
    }

    /* Multiple select for payment   */
    if ($("#payment")[0]){
        $("#payment").select2();
    }

    /* Editor for listing cabin more details*/
    if ($(".otherDetails")[0]) {
        $(".otherDetails").wysihtml5({
            locale: 'de-DE',
            toolbar: {
                "image": false,
                "link": false
            }
        });
    }

    /* for Half Board price textbox toggle If half board check should show price field*/
    if ($('#halfboard').is(':checked')) {
        $('#divHafPrice').fadeIn();
        $('#halfboard').val('1');
    }

    $('#halfboard').click(function () {
        if ($(this).is(':checked')) {
            $('#divHafPrice').fadeIn();
            $('#halfboard').val('1');
        } else {
            $('#divHafPrice').fadeOut();
            $('#halfboard').val('');
        }
    });

});

$(document).ready(function () {
    /* for make cabin code text in upper case */
    $('#cabin_code').keyup(function () {
        this.value = this.value.toUpperCase();
    });

    /*  Edit Section */
    $('#updateContactInfo').click(function (e) {
        e.preventDefault();
        updateCabin('contactInfoBox','/admin/cabinlite/updatecontactinfo' , this) ;
    });

    $('#updateCabin').click(function () {
        updateCabin('CabinInfoBox','/admin/cabinlite/updatecabininfo' , this) ;
    });

    $('#updateBillingInfo').click(function () {
        updateCabin('billingInfoBox','/admin/cabinlite/updatebillinginfo' , this) ;
    });

});

function updateCabin(divId,url,thisArg){

    //Check message already exists, if yes remove
    if(  $('#' + divId).children('#flash').length >0){
        $('#' + divId).children('#flash').remove();
    }

    ovelayLoading('add', divId); //adding loading effect
    var $btn = $(thisArg);
    $('#' +divId).find('.has-error').removeClass('has-error');
    $('#' +divId).find('.help-block').html('<strong></strong>');
    $btn.button('loading');

    $.ajax({
        dataType: 'JSON',
        type: "POST",
        url: url,
        data:  $("form").serialize()+'&'+$.param({ 'formPart': $btn.val() }),
        success: function (data) {
            ovelayLoading('remove');//remove loading effect
            $btn.button('reset');
            if ((data.errors)) {
                $.each(data.errors, function (i, item) {
                    $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                    $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                    $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                    $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                });
            }
            else {
                //  append success message
                if(data.errorMsg != undefined){
                    var msgClass= 'alert-danger' ;
                    var msgText = data.errorMsg ;

                }
                else {
                    var msgClass= 'alert-success' ;
                    var msgText = data.successMsg ;
                }
                var msg = '<div id="flash" class="alert '+ msgClass +'"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button>' + msgText + '</div>';

                $(msg).prependTo('#'+divId).fadeIn(100);
                setTimeout(function(){ $('#'+divId +' #flash').fadeOut() }, 2000);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) { // What to do if we fail
            ovelayLoading('remove');//remove loading effect
            if (jqXHR.status == 422) {
                var errData = jqXHR.responseJSON;
                $.each(errData, function (i, item) {
                    $("input[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                    $("input[name='" + i + "']").parent('.form-group').addClass('has-error');
                    $("select[name='" + i + "']").parent('.form-group').children('.help-block').html(' <strong>' + item[0] + '</strong> ');
                    $("select[name='" + i + "']").parent('.form-group').addClass('has-error');
                });
                $btn.button('reset');
            }
        }
    });
}

/* Overlay after submit */
function ovelayLoading(arg, appendDiv) {

    if (arg == 'add') {
        var overlay = jQuery('<div id="overlay"></div>');
        overlay.appendTo('#' + appendDiv);
    }
    if (arg == 'remove') {
        $("#overlay").remove();
    }
}

/* show drop down when mouseover on the  breadcrumb cabin name  */
$('.dropdown').hover(function(event){
    event.preventDefault();
    if(!($(this).hasClass("open")) ){ // Checks if its dropdown is already open so it doesn't close it.
        $('.dropdown-toggle', this).trigger('click');
    }
});

/* Scroll to a particulare ID*/
function scrollToID(argId){
    $('html, body').animate({
        'scrollTop' : $("#"+argId).position().top
    });
}