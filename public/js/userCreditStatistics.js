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
    $('#date_user_status_stat').daterangepicker({
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

    $('#date_user_status_stat').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD.MM.YYYY') + '-' + picker.endDate.format('DD.MM.YYYY'));
    });

    /* Date range functionality end */

    /* Chart generate */
    $('#graphUserStatusStat').hide();

    $('#generateUserStatusStat').on('click', function() {

        var $btn      = $(this).button('loading');
        var dates     = $('#date_user_status_stat').val();
        var daterange = dates.replace(/\s/g, '');

        $.ajax({
            url: '/admin/bookings/user/credit/statistics',
            dataType: "json",
            type: "POST",
            data:{ daterange:daterange }
        })
            .done(function( response ) {
                $('.alertUserStatusStat').hide();
                $('#graphUserStatusStat').show();
                $('#chartBookingStatistics').remove();
                $('#graphUserStatusStat').append('<canvas id="chartBookingStatistics" style="height: 400px;"></canvas>');

                var lineChart = {
                    labels: response.chartLabel,
                    datasets: response.chartData
                };

                var ctx = document.getElementById('chartBookingStatistics').getContext('2d');
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
                                pointHitRadius: 10
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: "Dates"
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(labels) {
                                        return '€' + labels;
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: "Total Amount"
                                }
                            }]
                        }
                    }
                });

                $('.response_array_sum').html('<label>Total</label><div class="input-group"> <div class=""><span class="label label-default">Money balance used <span class="badge">€'+response.total_balance_used_array_sum+'</span></span><span class="label label-default">Money balance <span class="badge">€'+response.total_money_balance_array_sum+'</span></span><span class="label label-default">Deleted balance <span class="badge">€'+response.total_money_deleted_array_sum+'</span></span></div></div>');

                $btn.button('reset');
            })
            .fail(function() {
                $('#graphUserStatusStat').hide();
                $('.alertUserStatusStat').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Something went wrong please try again.</div>');
                $btn.button('reset');
            });
    });
});