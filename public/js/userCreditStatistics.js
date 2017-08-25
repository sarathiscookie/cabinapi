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
                $('#graphUserStatusStat').show();
                $('#pieChartBookingStatistics').remove();
                $('#graphUserStatusStat').append('<canvas id="pieChartBookingStatistics" style="height: 400px;"></canvas>');

                var pieChart = {
                    labels: response.chartLabel,
                    datasets: response.chartData
                };

                var ctx = document.getElementById('pieChartBookingStatistics').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: pieChart,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'bottom'
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var precentage = Math.floor(((currentValue/total) * 100)+0.5);
                                    return precentage + "%";
                                }
                            }
                        }
                    }
                });

                $btn.button('reset');
            })
            .fail(function() {
                $('#graphUserStatusStat').hide();
                $('.alertUserStatusStat').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>OOPS!</strong> Something went wrong please try again.</div>');
                $btn.button('reset');
            });
    });
});