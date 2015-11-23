$(function() {
		
    function cb(start, end) {
        $('#airlineDateRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var startDate = start._d.getFullYear() + '-' + (Number(start._d.getMonth())+1) + '-' + start._d.getDate();
        var endDate = end._d.getFullYear() + '-' + (Number(end._d.getMonth())+1) + '-' + end._d.getDate();
        $('input[name="AIRLINE_STARTDATE"]').val(startDate);
        $('input[name="AIRLINE_ENDDATE"]').val(endDate);
    }
    cb(moment().subtract(29, 'days'), moment());

    $('#airlineDateRange').daterangepicker({
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
    	console.log(isChecked);
    	
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
});