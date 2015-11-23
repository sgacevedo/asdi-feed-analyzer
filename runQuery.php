<?php	
	$table = '';
	$selector = '';
	
	//if the start date and end date range is set
	if(isset($_POST['AIRLINE_STARTDATE']) && isset($_POST['AIRLINE_ENDDATE'])){
	
		$dbMan = new DatabaseManager();
		$request = '';
	
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
		
		//if the checkbox is checked - get delays by airlines
		if(isset($_POST['AIRLINE_DELAYS'])){
			$request = new Request('getDelaysByAirlines', 'se_Flights');
			$request->addParameter('startDate', $_POST['AIRLINE_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRLINE_ENDDATE']);
			$request->transformCommand();
			$table = '<table class="table table-hover">'
					.'<thead>'
						.'<tr>'
							.'<th>Airline</th>'
							.'<th>Number of Delays</th>'
						.'</tr>'
					.'</thead>'
					.'<tbody>';
		}
		
		//otherwise, if the checkbox is not checked get probability of delay by specified airline
		else{
			$request = new Request('getProbabilityOfDelay', 'se_Flights');
			$request->addParameter('startDate', $_POST['AIRLINE_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRLINE_ENDDATE']);
			$request->addParameter('region', $_POST['AIRLINE_REGION']);
			$request->addParameter('airline', $_POST['AIRLINE_NAME']);
			$request->transformCommand();
			$table = '<table class="table table-hover">'
					.'<thead>'
						.'<tr>'
							.'<th>Flight Number</th>'
							.'<th>Airline Name</th>'
							.'<th>Departure Date</th>'
							.'<th>Departure Airport</th>'
							.'<th>Region</th>'
							.'<th>Filed Depart Time</th>'
							.'<th>Flown Depart Time</th>'
						.'</tr>'
					.'</thead>'
					.'<tbody>';
		}
	
		$results = $dbMan->executeQuery($request);
	
		if($results == null){
			//request failed
		}
		else{
			$rows = $results->num_rows;
			$selector = '#airlines .results';
			for ($i = 0 ; $i < $rows ; ++$i){
				$results->data_seek($i);
				$row = $results->fetch_array(MYSQLI_NUM);
					
				$table = $table . '<tr>';
				for($j = 0; $j < count($row); ++$j){
					
					$table = $table . '<td>'. $row[$j] .'</td>';
				}
				$table = $table . '</tr>';
	
			}
			$table = $table . '</tbody></table>';
			
			if($request->type = 'getProbabilityOfDelay'){
				
			}
		}
	}
	echo <<<_END
	<script>
	$(document).ready(function(){
		$("$selector").html('$table');
		console.log('hello');
	});
	</script>
_END;
	
?>