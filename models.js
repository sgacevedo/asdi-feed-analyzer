function getDelaysByAirlines(button){
	button.remove();
	var outputContainer = $('#airlines .results .model');
	var xAxis = $('#airlines .results table tr td:nth-child(1)');
	var yAxis = $('#airlines .results table tr th:nth-child(2)').html();
	var values = $('#airlines .results table tr td:nth-child(2)');
	var title = "Number of Delayed Flights by Airlines"
	createColumnChart(outputContainer, xAxis, yAxis, values, title);
}

function getDelaysByRegions(button){
	button.remove();
	var outputContainer = $('#regions .results .model');
	var xAxis = $('#regions .results table tr td:nth-child(1)');
	var yAxis = $('#regions .results table tr th:nth-child(2)').html();
	var values = $('#regions .results table tr td:nth-child(2)');
	var title = "Number of Delayed Flights by Regions"
	createColumnChart(outputContainer, xAxis, yAxis, values, title);
}

function createColumnChart(outputContainer, xAxis, yAxis, values, title){
	var xValues = new Array();
	var vals = new Array();
	
	xAxis.each(function(){
		xValues.push($(this).html());	
	});
	
	values.each(function(){
		vals.push(Number($(this).html()));	
	});
	
	outputContainer.highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: title
        },
        xAxis: {
            categories: xValues,
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: yAxis
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10pt">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0; font-size: 10pt;">{series.name}: </td>' +
                '<td style="padding:0">{point.y:f}</td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: yAxis,
            data: vals

        }]
    });
}
