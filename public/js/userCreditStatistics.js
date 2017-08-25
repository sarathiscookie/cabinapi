/**
 * Created by PhpStorm.
 * User: user
 * Date: 25-08-2017
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
    $('#daterange_book_statistics').daterangepicker({
        autoUpdateInput: false,
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

    $('#daterange_book_statistics').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    /* Date range functionality end */

    /* Chart generate */
    $('#graphBookingStatus').hide();

    $('#generateBookingStat').on('click', function() {
        var $btn      = $(this).button('loading');
        var cabin     = $('.cabins_book_statistics').val();
        var dates     = $('#daterange_book_statistics').val();
        var daterange = dates.replace(/\s/g, '');

        $.ajax({
            url: '/admin/bookings/statistics',
            dataType: "json",
            type: "POST",
            data:{ daterange:daterange, cabin:cabin}
        })
            .done(function( response ) {
                $('#graphBookingStatus').show();
                $('#lineChartBookingStatistics').remove();
                $('#graphBookingStatus').append('<canvas id="lineChartBookingStatistics" style="height: 400px;"></canvas>');

                var lineChart = {
                    labels: response.chartLabel,
                    datasets: response.chartData
                };

                var ctx = document.getElementById('lineChartBookingStatistics').getContext('2d');
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
                                    labelString: "Datum",
                                }
                            }],
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: "Anzahl",
                                }
                            }]
                        }
                    }
                });

                $btn.button('reset');
            })
            .fail(function() {
                $('#graphBookingStatus').hide();
                $('.alertBookingStat').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Something went wrong please try again.</div>');
                $btn.button('reset');
            });
    });
});