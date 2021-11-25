import 'jquery-confirm'
const Highcharts = require('highcharts/highcharts');  // or require('highcharts/highstock');
import 'datatables.net'
import 'datatables.net-bs5'
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

    $('#'+idP).css({'width': progressBarWidth+'%', 'color':'black', 'font-weight': 'bold'})
    $('#'+idP).attr('aria-valuenow', total)
    $('#'+idP).attr('aria-valuemin', '0')
    $('#'+idP).attr('aria-valuemax', target)
    $('#'+idP).text(Math.round(progressBarWidth)+"%")
}

document.addEventListener('DOMContentLoaded', function () {
    let BASE_URI = '/admin/client/show-chart-goal-clients';
    let URI_SELLER_WEEK = '/seller/visits-week'
    let URI_SELLER_MONTH = '/seller/visits-month'
    let URI_SELLER_OF_MONTH = '/seller/visits-of-the-month'
    let URI_SELLER_QUARTER = '/seller/visits-quarter'
    let URL_LAST_WEEK = '/last-num-week'
    let URI_SELLER_UNVISITED_CLIENT = '/seller/clients-list';
    let URI_SELLER_TURNOVER_MONTHLY = '/seller/turnover-month';
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
    $('#selectedYear').text(year)
    $('#selectedMYear').text(year)
    getTheWeekOfYear(year)

    $('#yearFilter').change(e => {
        e.preventDefault()
        $('#selectedYear').text(e.target.value)
        $('#selectedMYear').text(e.target.value)
        postDataChartWeek('container-2',URI_SELLER_WEEK, {'year':e.target.value})
        postDataChartMonth('container-3',URI_SELLER_MONTH, {'year':e.target.value, 'month':''})
        postDataProgressQuarter(URI_SELLER_QUARTER, {'year': e.target.value})
        postDataCompChartMonth('container-CA', URI_SELLER_TURNOVER_MONTHLY, {'year': e.target.value})
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
    postDataCompChartMonth('container-CA', URI_SELLER_TURNOVER_MONTHLY, {})
    $('#weekSelect').change(e => {
        e.preventDefault()
       postDataChartWeek('container-2',URI_SELLER_WEEK, {'year':$('#selectedYear').text() , 'week': e.target.value})
    });
    $('#monthSelect').change(e => {
        e.preventDefault()
        console.log(e.target.value)
        postDataChartMonth('container-3',URI_SELLER_OF_MONTH, {'year':$('#selectedMYear').text() , 'month': e.target.value})
    });
    postDataProgressQuarter(URI_SELLER_QUARTER, {})
function postDataProgressQuarter(url, data) {
    $.post(url, data)
        .done(function (data) {
            console.log(data)
            for (let i = 0; i < data['targetNbVisits'].length; i++) {
                console.log(data['sellerNbVisits'][i]['nbVisits'])
                calculateProgressBar(data['sellerNbVisits'][i]['nbVisits'], data['targetNbVisits'][i],'PQ'+(i+1),'STQ'+(i+1), 'TTQ'+(i+1))
                // Progress bar of clients: the % of visited clients to visited 
                calculateProgressBar(data['sellerNbVisits'][i]['nbVisitedClients'], data.nbSellerClients, 'PC'+(i+1), 'CNVQ'+(i+1), 'TC'+(i+1))
                $('#CNVQ'+(i+1)).text(data.nbSellerClients - data['sellerNbVisits'][i]['nbVisitedClients'])

                $('#cardT'+(i+1)).click(function (){

                    console.log("nb unvisited "+ data['sellerNbVisits'][i]['nonVCQuarter'] )
                    /**/

                    $.post(URI_SELLER_UNVISITED_CLIENT, {'data': data['sellerNbVisits'][i]['nonVCQuarter'] })
                        .done(function (response) {
                            console.log(response)

                            $.dialog({
                                title: 'List of unvisited clients',
                                content: response,
                                animation: 'scale',
                                columnClass: 'col-md-8',
                                closeAnimation: 'scale',
                                type: 'green',
                                backgroundDismiss: true,
                            });
                        })
                        .fail(function() {
                            $.alert( "error" );
                        })
                        .always(function() {
                            //setTimeout(function(){location.reload()}, 2000);
                        });

                })

            }


        })
        .fail(function() {
            $.alert( "error" );
        })
        .always(function() {
            //setTimeout(function(){location.reload()}, 2000);
        });
}
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
    $.post(url, data)
        .done(function (data) {
           calculateProgressBar(data['totalMonth'], data['nbVisitsMonthTarget'], 'nbSellerMonthVisits', 'totalVisitMonth', 'totalMonthTarget')
            createTheChart(container,
                'column',
                'category',
                'Total visits per month',
                'Visits per month - '+$('#selectedMYear').text(),
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
function postDataCompChartMonth(container,url, data) {
    $.post(url, data)
        .done(function (response) {
            console.log(response['monthlyTurnover'])
           createCompChart(container,
                'xy',
                'Chiffre d\affaire VS. Objectif',
                'CA par mois - '+$('#selectedMYear').text(),
                
                response)
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

function createCompChart($container, type,title, subTitle, data){
    Highcharts.chart($container, {
        chart: {
            zoomType: type
        },
        title: {
            text: title
        },
        subtitle: {
            text: subTitle
        },
        xAxis: [{
            categories: data['month'],
            crosshair: true
        }],
        yAxis: [{ // Primary yAxis
            labels: {
                format: '{value} DT',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            title: {
                text: 'Objectif',
                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            }
        }, { // Secondary yAxis
            title: {
                text: 'Chiffre d\'affaire',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            labels: {
                format: '{value} DT',
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },
            opposite: true
        }],
        tooltip: {
            shared: true
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            x: 120,
            verticalAlign: 'top',
            y: 100,
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || // theme
                'rgba(255,255,255,0.25)'
        },
        series: [{
            name: 'Chiffre d\'affaire',
            type: 'column',
            yAxis: 1,
            data: data['monthlyTurnover'],
            tooltip: {
                valueSuffix: ' DT'
            }
    
        }, {
            name: 'Objectif',
            type: 'spline',
            data: data['targetTurnover'],
            tooltip: {
                valueSuffix: 'DT'
            }
        }]
    });
}

});