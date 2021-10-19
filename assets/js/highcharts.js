const Highcharts = require('highcharts/highcharts');  // or require('highcharts/highstock');
window.Highcharts = Highcharts;
document.addEventListener('DOMContentLoaded', function () {
    const d = new Date();
    let year = d.getFullYear();
    $('#years').change(e => {
        e.preventDefault()
        console.log(e.target.value)
        postData('/client/admin/show-chart-goal-clients',e.target.value,{year: e.target.value})
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
    postData('/client/admin/show-chart-goal-clients', year,{year:year })

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
});