$(function(){

    /* Rules: Get rule value from */
    var ruleValue = $( ".selectRules option:selected" ).val();

    if(ruleValue == 1) {
        $( ".regular" ).show();
        $( ".notRegular" ).hide();
    }
    else if(ruleValue == 2) {
        $( ".regular" ).hide();
        $( ".notRegular" ).show();
    }
    else {
        $( ".regular" ).hide();
        $( ".notRegular" ).hide();
    }

    /* Show and hide regular and not regular */
    $( ".selectRules" ).on('change', function (e){
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if( valueSelected == 1 ) {
            $( ".regular" ).show();
            $( ".notRegular" ).hide();
        }
        else if( valueSelected == 2 ) {
            $( ".regular" ).hide();
            $( ".notRegular" ).show();
        }
    });

    /* Daterange */
    $('#daterange').daterangepicker();
});