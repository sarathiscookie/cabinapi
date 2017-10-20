$(function(){

    /* Tooltip */
    $('[data-toggle="tooltip"]').tooltip();

    /* Functionality for check box */
    if($('#regularCheckbox').is(":checked")) {
        $('#regular').show();
    }
    else {
        $('#regular').hide();
        $('#regular').attr({
            style: "display:none"
        });
    }

    if($('#notRegularCheckbox').is(":checked")) {
        $('#notRegular').show();
    }
    else {
        $('#notRegular').hide();
        $('#notRegular').attr({
            style: "display:none"
        });
    }

    $('#regularCheckbox').on('change', function() {
        $('#regular').toggle();
    });

    $('#notRegularCheckbox').on('change', function() {
        $('#notRegular').toggle();
    });

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