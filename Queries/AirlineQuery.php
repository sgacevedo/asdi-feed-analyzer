<?php	
	$table = '';
	
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
							.'<th>Delayed</th>'
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
			$total = 0;
			$delays = 0;
			
			for ($i = 0 ; $i < $rows ; ++$i){
				$results->data_seek($i);
				$row = $results->fetch_array(MYSQLI_NUM);
					
				$table = $table . '<tr>';
				for($j = 0; $j < count($row); ++$j){
					
					$table = $table . '<td>'. $row[$j] .'</td>';
				}
				
				if($request->type == 'getProbabilityOfDelay'){
					$total++;
					if($row[5] < $row[6]){
						$table = $table . '<td><span class="label label-danger">Delayed</span></td></tr>';
						$delays++;
					}
					else{
						$table = $table . '<td><span class="label label-success">On-Time</span></td></tr>';
					}
				}
	
			}
			if($rows == 0){
				$table = $table . '<tr><td>No items</td></tr>';
			}
			
			$table = $table . '</tbody></table>';
			
			if($rows > 0 && $request->type != 'getProbabilityOfDelay'){
				$table = $table . '<button type="button" class="btn btn-primary generateModel" onClick="' .$request->type. '(this)">Generate Model</button>';
			}
			
			if($request->type == 'getProbabilityOfDelay' && $rows > 0){
				$delayPercentage = round(($delays/$total) * 100, 2);
				$table = $table . '<h4>' . $_POST['AIRLINE_NAME'] . ' probability of delays departing from the ' . $_POST['AIRLINE_REGION'] . ': <span class="label label-default">' . $delayPercentage . '%</span></h4>';
			}
		}
	}
	echo $table;
?>