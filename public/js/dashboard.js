/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

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
            'Last 7 Days': [moment().subtract(7, 'days'), moment()],
            'Last 30 Days': [moment().subtract(30, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD.MM.YYYY'
        }
    });

    $('#generate').on('click', function() {
        var $btn      = $(this).button('loading');
        var cabin     = $('.cabins').val();
        var dates     = $('#daterange').val();
        var daterange = dates.replace(/\s/g, '');

       $.ajax({
           url: '/admin/dashboard/sales/graph',
           dataType: "json",
           type: "POST",
           data:{ daterange:daterange, cabin:cabin}
       })
           .done(function( response ) {
               $('#lineChartSales').remove();
               $('#graph-container').append('<canvas id="lineChartSales" style="height: 400px;"></canvas>');
               var lineChart = {
                   labels: ["January", "February", "March", "April", "May", "June", "July"],
                   datasets: [{
                       label: 'Prepay Amount',
                       backgroundColor: 'rgba(255, 99, 132, 0.2)',
                       borderColor: 'rgba(255,99,132,1)',
                       borderWidth: 1,
                       data: [0, 10, 5, 25, 20, 30, 45]
                   },
                       {
                           label: 'Service Fee',
                           backgroundColor: 'rgba(79, 196, 127, 0.2)',
                           borderColor: 'rgba(79, 196, 127, 1)',
                           data:[0, 1.5, 2, 2.5, 3, 3.5, 4]
                       },
                       {
                           label: 'Total Prepay Amount',
                           backgroundColor: 'rgba(153, 102, 255, 0.2)',
                           borderColor: 'rgba(153, 102, 255, 1)',
                           data:[0, 11.5, 7, 27.5, 23, 33.5, 49]
                       }
                   ]
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
                   }
               });

               $btn.button('reset')
           })
           /*.fail(function() {
               alert( "error" );
           })*/;
    });

    /*var lineChart = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [{
            label: 'Prepay Amount',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255,99,132,1)',
            borderWidth: 1,
            data: [0, 10, 5, 25, 20, 30, 45]
        },
            {
                label: 'Service Fee',
                backgroundColor: 'rgba(79, 196, 127, 0.2)',
                borderColor: 'rgba(79, 196, 127, 1)',
                data:[0, 1.5, 2, 2.5, 3, 3.5, 4]
            },
            {
                label: 'Total Prepay Amount',
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                data:[0, 11.5, 7, 27.5, 23, 33.5, 49]
            }
        ]
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
        }
    });*/
  //The Calender
  $("#calendar").datepicker();
});
