const Highcharts = require('highcharts/highcharts');  // or require('highcharts/highstock');
window.Highcharts = Highcharts;

function calculateProgressBar(total, target, idP, idTotal, idTarget) {
    let progressBarWidth = total;
    $('#'+idTotal).text(total)
    $('#'+idTarget).text(target)

    if(target !== 0 && total < target){
        progressBarWidth = (total*100)/target
    }
    if(target === total ){
        progressBarWidth = 100
    }

    $('#'+idP).css('width',progressBarWidth+'%')
    $('#'+idP).attr('aria-valuenow', total)
    $('#'+idP).attr('aria-valuemin', '0')
    $('#'+idP).attr('aria-valuemax', target)
    $('#'+idP).text(progressBarWidth+"%")
}

document.addEventListener('DOMContentLoaded', function () {
    let BASE_URI = '/admin/client/show-chart-goal-clients';
    let URI_SELLER_WEEK = '/seller/visits-week'
    let URI_SELLER_MONTH = '/seller/visits-month'
    let URL_LAST_WEEK = '/last-num-week'
    const d = new Date();
    let year = d.getFullYear();
    $('#years').change(e => {
        e.preventDefault()
        console.log(e.target.value)
        postData(BASE_URI,e.target.value,{year: e.target.value})
    })


    let postData = (url,year ,data ) => {
        $.post(url, data)
            .done(function (data) {
                console.log(data['Series'])
                console.log(data['categories'])
                chart('column',"Clients' goal -"+year+"", data)
            })
            .fail(function() {
                $.alert( "error" );
            })
            .always(function() {
                //setTimeout(function(){location.reload()}, 2000);
            });
    }
    postData(BASE_URI, year,{year:year })

    function chart(type, title, data){
        Highcharts.chart('container', {
            chart: {
                type: type
            },
            title: {
                text: title
            },
            xAxis: {
                categories: data['categories']
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Total fruit consumption'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: ( // theme
                            Highcharts.defaultOptions.title.style &&
                            Highcharts.defaultOptions.title.style.color
                        ) || 'gray'
                    }
                }
            },
            legend: {
                align: 'right',
                x: -30,
                verticalAlign: 'top',
                y: 25,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || 'white',
                borderColor: '#CCC',
                borderWidth: 1,
                shadow: false
            },
            tooltip: {
                headerFormat: '<b>{point.x}</b><br/>',
                pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: data['Series']
        });
    }

    /**
     * THE SELLER DASHBOARD
     */
    // The initialization of both week select and the current Year
    $('.selectedYear').text(year)
    getTheWeekOfYear(year)

    $('#yearFilter').change(e => {
        e.preventDefault()
        $('.selectedYear').text(e.target.value)
        getTheWeekOfYear(e.target.value)

    });

    //  fill in the dropdown of the week select
    function getTheWeekOfYear(year) {
        let x = document.getElementById("weekSelect");
        x.options.length = 0
        $.post(URL_LAST_WEEK, {year: year})
            .done(function (data) {
                console.log(data.lastWeek)
                for (let i = 1; i <= data.lastWeek; i++) {
                    let option = document.createElement("option");
                    option.text = "S "+i;
                    option.value = i.toString()
                    if(option.value === data.thisWeek ){
                        option.selected = true;
                    }
                    x.add(option);
                }
            })
            .fail(function() {
                $.alert( "error" );
            })
            .always(function() {
                //setTimeout(function(){location.reload()}, 2000);
            });
    }
    postDataChartWeek('container-2',URI_SELLER_WEEK, {})
    postDataChartMonth('container-3',URI_SELLER_MONTH, {})
    $('#weekSelect').change(e => {
        e.preventDefault()

       postDataChartWeek('container-2',URI_SELLER_WEEK, {'year':$('.selectedYear').text() , 'week': e.target.value})

    });
function postDataChartWeek(container,url, data) {
    $.post(url, data)
        .done(function (data) {
            calculateProgressBar(data['total'], data['nbVisitsTarget'], 'nbSellerVisits', 'totalVisitWeekly', 'totalTarget')
            createTheChart(container,
                'column',
                'category',
                'Total visits per day',
                'Visits per day during the week',
                'Week n°: '+data['week'],
                'Days',
                data['data'])
        })
        .fail(function() {
            $.alert( "error" );
        })
        .always(function() {
            //setTimeout(function(){location.reload()}, 2000);
        });
}
function postDataChartMonth(container,url, data) {
    console.log($('.selectedYear')[0])
    $.post(url, data)
        .done(function (data) {
           calculateProgressBar(data['totalMonth'], data['nbVisitsMonthTarget'], 'nbSellerMonthVisits', 'totalVisitMonth', 'totalMonthTarget')
            createTheChart(container,
                'column',
                'category',
                'Total visits per month',
                'Visits per month - '+$('.selectedYear')[1].innerHTML,
                'Month: '+data['month'],
                'Days',
                data['dataMonth'])
        })
        .fail(function() {
            $.alert( "error" );
        })
        .always(function() {
            //setTimeout(function(){location.reload()}, 2000);
        });
}
function createTheChart($container, type, typeX, titleY,title, subTitle, seriesName, data) {
    // Create the chart
    Highcharts.chart($container, {
        chart: {
            type: type
        },
        title: {
            text: title
        },
        subtitle: {
            text: subTitle
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: typeX
        },
        yAxis: {
            title: {
                text: titleY
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> of total<br/>'
        },

        series: [
            {
                name: seriesName,
                colorByPoint: true,
                data: data
            }
        ],
    });


}


























});