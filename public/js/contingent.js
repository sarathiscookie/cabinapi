$(function(){

    /* Rules: Get rule value from */
    /*var ruleValue = $( ".selectRules option:selected" ).val();

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
    }*/

    /* Show and hide regular and not regular */
    /*$( ".selectRules" ).on('change', function (e){
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
    });*/

    /* Date range functionality begins */
    $('#daterange').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: "DD.MM.YYYY",
            applyLabel: "Bestätigen",
            cancelLabel: "Löschen",
            daysOfWeek: [
                "So",
                "Mo",
                "Di",
                "Mi",
                "Do",
                "Fr",
                "Sa"
            ]
        }
    });

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + ' - ' + picker.endDate.format('DD.MM.YYYY'));
    });
    /* Date range functionality ends */
});