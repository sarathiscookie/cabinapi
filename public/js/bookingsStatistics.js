/**
 * Created by PhpStorm.
 * User: user
 * Date: 09-08-2017
 * Time: 08:06
 */
$(function () {

    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Initialize Select2 Elements
    $(".cabins").select2({
        placeholder: "select a cabin",
        allowClear: true
    });

    /* Date range picker */
    var start = moment().subtract(29, 'days');
    var end = moment();

    $('#daterange').daterangepicker({
        startDate: start,
        endDate: end,
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
            ],
        }
    });

    /* Chart generate */
    $('#graph-container').hide();

    $('#generate').on('click', function() {
        var $btn      = $(this).button('loading');
        var cabin     = $('.cabins').val();
        var dates     = $('#daterange').val();
        var daterange = dates.replace(/\s/g, '');

        $.ajax({
            url: '/admin/bookings/statistics',
            dataType: "json",
            type: "POST",
            data:{ daterange:daterange, cabin:cabin}
        })
            .done(function( response ) {
                $('#graph-container').show();
                $('#lineChartSales').remove();
                $('#graph-container').append('<canvas id="lineChartSales" style="height: 400px;"></canvas>');

                var lineChart = {
                    labels: response.chartLabel,
                    datasets: response.chartData
                };

                var ctx = document.getElementById('lineChartSales').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: lineChart,
                    options: {
                        elements: {
                            rectangle: {
                                fill: false,
                                lineTension: 0.1,
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: "rgba(75,192,192,1)",
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: "Dates",
                                }
                            }],
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: "Count",
                                }
                            }]
                        }
                    }
                });

                $btn.button('reset');
            })
            .fail(function() {
                $('#graph-container').hide();
                $('.alert-graph').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Something went wrong please try again.</div>');
                $btn.button('reset');
            });
    });


    /* Calender */
    $("#calendar").datepicker();
});