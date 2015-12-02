var MAP;
var LINE;

$(document).ready(function(){
	var url = window.location.toString();
	url = url.split('?');
	if(url[1] != undefined){
		tabParameter = url[1].split('=')[1];
		tabParameter = '#' + tabParameter;
		
		//remove active 
		$('.nav.nav-tabs .active').removeClass('active');
		$('.tab-content .active').removeClass('in active');
		
		$('.nav.nav-tabs li > a[href="' + tabParameter + '"]').parents('li').addClass('active');
		$('.tab-content ' + tabParameter).addClass('in active');
	}
});

$(function() {
    function cb(start, end) {
        $('.dateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var startDate = start._d.getFullYear() + '-' + (Number(start._d.getMonth())+1) + '-' + start._d.getDate();
        var endDate = end._d.getFullYear() + '-' + (Number(end._d.getMonth())+1) + '-' + end._d.getDate();
        $('.startDate').val(startDate);
        $('.endDate').val(endDate);
    }
    cb(moment().subtract(29, 'days'), moment());

    $('.dateRange').daterangepicker({
    	showDropdowns: true,
        ranges: {
           /*'Today': [moment(), moment()],*/
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    	   'This Year': [moment().startOf('year'), moment().endOf('year')]
        }
    }, cb);
    
    $('#delaysByAirlines input').click(function(){
    	var isChecked = $(this).prop('checked');
    	
    	if(isChecked){
    		$('#airlineRegion').addClass('disabled');
    		$('#airlineRegion select').attr('disabled', 'disabled');
    		$('#airlineSelect').addClass('disabled');
    		$('#airlineSelect select').attr('disabled', 'disabled');
    	}
    	else if(!isChecked){
    		$('#airlineRegion').removeClass('disabled');
    		$('#airlineRegion select').removeAttr('disabled');
    		$('#airlineSelect').removeClass('disabled');
    		$('#airlineSelect select').removeAttr('disabled');
    	}
    });
    
    $('#airportSelect input').click(function(){
    	var selectedRadio = $(this).val();
    	
    	if(selectedRadio == 'one_airport'){
    		$(this).siblings('select').removeAttr('disabled');
    		$('#delaySelect input[value="delayed_arrivals"]').removeAttr('disabled');
    	}
    	else if(selectedRadio == 'all_airports'){
    		$('input[value="one_airport"]').siblings('select').attr('disabled', 'disabled');
    		$('#delaySelect input[value="delayed_arrivals"]').attr('disabled', 'disabled');
    		$('#delaySelect input[value="delayed_departures"]').prop('checked', 'true');
    	}
    });
    
    $('.plotButton').click(function(){
    	var row = $(this).parents('tr');
    	var point1 = row.find('td:nth-child(2)').html();
    	var point2 = row.find('td:nth-child(3)').html();
    	
    	//remove brackets and spaces
    	point1 = point1.replace("[", "").replace("]", "").replace(" ", "");
    	point2 = point2.replace("[", "").replace("]", "").replace(" ", "");
    	
    	//split coordinate set on comma
    	point1 = point1.split(",");
    	point2 = point2.split(",");
    	
    	if(LINE != undefined) removeLines();
    	addLine(point1[0], point1[1], point2[0], point2[1]);
    });
});

function initMap(){
	MAP = L.map('map').setView([38, -97], 4);
	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
	    maxZoom: 18,
	    id: 'sgacevedo.oa9j8l4j',
	    accessToken: 'pk.eyJ1Ijoic2dhY2V2ZWRvIiwiYSI6ImNpaG1udmVhczA2eXR1Mmo3YXRweGUyOHMifQ.dWZwd_Obb8axH0Ti4azpUg'
	}).addTo(MAP);
}

function addLine(startLat, startLong, endLat, endLong){
	LINE = L.polygon([
	                 	    [Number(startLat), Number(startLong)],
	                 	    [Number(endLat), Number(endLong)]
	                 	],
	                 	{color: 'red'}).addTo(MAP);
}

function removeLines(){
	MAP.removeLayer(LINE);
}

function testing(){
	//alert(1);
	
	var doc = new jsPDF();

	// All units are in the set measurement for the document
	// This can be changed to "pt" (points), "mm" (Default), "cm", "in"
	doc.fromHTML($('#airlines .results').get(0), 15, 15, {
		'width': 170, 
		'elementHandlers': true
	});
	
	doc.save('Test.pdf');

}