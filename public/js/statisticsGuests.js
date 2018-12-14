/**
 * Created by PhpStorm.
 * User: user
 * Date: 03-08-2018
 * Time: 10:44
 */
$(function () {

    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Date range functionality begin */
    $('#guests_count_stat').daterangepicker({
        autoUpdateInput: false,
        startDate: moment(),
        endDate: moment().add(30, 'days'),
        ranges: {
            'Letzten 7 Tage': [moment().subtract(7, 'days'), moment()],
            'Letzten 30 Tage': [moment().subtract(30, 'days'), moment()],
            'Dieser Monat': [moment().startOf('month'), moment().endOf('month')],
            'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD.MM.YYYY',
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

    $('#guests_count_stat').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    /* Date range functionality end */

    var dates     = $('#guests_count_stat').val();
    var daterange = dates.replace(/\s/g, '');
    var $btn      = $('#generateGuestsCountStat').button('loading');

    /* Function for chart */
    function chartStatistics(daterange)
    {
        $.ajax({
            url: '/cabinowner/statistics/guests/count',
            dataType: "json",
            type: "POST",
            data:{ daterange:daterange }
        })
            .done(function( response ) {
                $('#chartGuestsCountStatistics').remove();
                $('#graphGuestsCountStat').append('<canvas id="chartGuestsCountStatistics"></canvas>');

                var lineChart = {
                    labels: response.chartLabel,
                    datasets: response.chartData
                };

                var ctx = document.getElementById("chartGuestsCountStatistics").getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: lineChart,
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: "Datum"
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: "Anzahl Gäste"
                                }
                            }]
                        }
                    }
                });

                $('.response_array_sum').html('<span class="label label-default">Gäste gesamt im ausgewählten Zeitraum: <span class="badge">'+response.sleeps_sum+'</span>');
                $btn.button('reset');
            })
            .fail(function() {
                $('.alertGuestsCountStat').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>WHOOPS!</strong> Something went wrong please try again.</div>');
                $btn.button('reset');
            });
    }

    /* When page loads count of guest will show for 30 days */
    chartStatistics(daterange);

    $("#generateGuestsCountStat").on("click", function(){
        var $btn      = $(this).button('loading');
        var dates     = $('#guests_count_stat').val();
        var daterange = dates.replace(/\s/g, '');
        chartStatistics(daterange);
    });

});
