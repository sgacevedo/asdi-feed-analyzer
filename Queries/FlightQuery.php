<?php	
	$table = '';
	
	/* If the start date and end date range is set */
	if(isset($_POST['FLIGHT_STARTDATE']) && isset($_POST['FLIGHT_ENDDATE'])){
		
		$dbMan = new DatabaseManager();
		$request = '';
		
		/* Establish Connection with database */
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
	
		$departureAirport = $_POST['DEPARTING_AIRPORT'];
		$arrivalAirport = $_POST['ARRIVAL_AIRPORT'];
		
		$flightRadioButton = $_POST['FLIGHTS_SELECT'];
		
		if($flightRadioButton == 'show_delays'){
			$request = new Request('getDelayedFlights', 'se_Flights');
			$request->addParameter('startDate', $_POST['FLIGHT_STARTDATE']);
			$request->addParameter('endDate', $_POST['FLIGHT_ENDDATE']);
			$request->addParameter('depart_airport', $departureAirport);
			$request->addParameter('arrival_airport', $arrivalAirport);
			
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Flight Number</th>'
								.'<th>Departure Date</th>'
								.'<th>Filed Departure Time</th>'
								.'<th>Flown Departure Time</th>'
								.'<th>Arrival Date</th>'
								.'<th>Filed Arrival Time</th>'
								.'<th>Flown Arrival Time</th>'
							.'</tr>'
						.'</thead>'
						.'<tbody>';
		}
	else if ($flightRadioButton == 'show_no_delays'){
			$request = new Request('getOnTimeFlights', 'se_Flights');
			$request->addParameter('startDate', $_POST['FLIGHT_STARTDATE']);
			$request->addParameter('endDate', $_POST['FLIGHT_ENDDATE']);
			$request->addParameter('depart_airport', $departureAirport);
			$request->addParameter('arrival_airport', $arrivalAirport);
				
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Flight Number</th>'
								.'<th>Departure Date</th>'
								.'<th>Filed Departure Time</th>'
								.'<th>Flown Departure Time</th>'
								.'<th>Arrival Date</th>'
								.'<th>Filed Arrival Time</th>'
								.'<th>Flown Arrival Time</th>'
							.'</tr>'
						.'</thead>'
						.'<tbody>';
		}
		else if ($flightRadioButton == 'show_all'){
			$request = new Request('getAllFlights', 'se_Flights');
			$request->addParameter('startDate', $_POST['FLIGHT_STARTDATE']);
			$request->addParameter('endDate', $_POST['FLIGHT_ENDDATE']);
			$request->addParameter('depart_airport', $departureAirport);
			$request->addParameter('arrival_airport', $arrivalAirport);
		
			/* Create layout for table */
			$table = '<table class="table table-hover">'
					.'<thead>'
					.'<tr>'
						.'<th>Flight Number</th>'
						.'<th>Departure Date</th>'
						.'<th>Filed Departure Time</th>'
						.'<th>Flown Departure Time</th>'
						.'<th>Arrival Date</th>'
						.'<th>Filed Arrival Time</th>'
						.'<th>Flown Arrival Time</th>'
						.'<th></th>'
					.'</tr>'
					.'</thead>'
					.'<tbody>';
		}
		else if ($flightRadioButton == 'show_amendments'){
			$request = new Request('getFlightCancelations', 'se_Flights');
			$request->addParameter('startDate', $_POST['FLIGHT_STARTDATE']);
			$request->addParameter('endDate', $_POST['FLIGHT_ENDDATE']);
			$request->addParameter('depart_airport', $departureAirport);
			$request->addParameter('arrival_airport', $arrivalAirport);
			
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Flight Number</th>'
								.'<th>Departure Date</th>'
								.'<th>Filed Departure Time</th>'
								.'<th>Arrival Date</th>'
								.'<th>Filed Arrival Time</th>'
								.'<th>Amendment Message</th>'
							.'</tr>'
						.'</thead>'
					.'<tbody>';
		}
		
		/* Transform the request into a command */
		$request->transformCommand();
		
		$results = $dbMan->executeQuery($request);
		
		if($results == null){
			//request failed
		}
		else{
			$delaySum = 0;
			$rows = $results->num_rows;
				
			for ($i = 0 ; $i < $rows ; ++$i){
				$results->data_seek($i);
				$row = $results->fetch_array(MYSQLI_NUM);
					
				$table = $table . '<tr>';
				for($j = 0; $j < count($row); ++$j){
		
					$table = $table . '<td>'. $row[$j] .'</td>';
				}
	
				if($request->type == 'getDelayedFlights'){
					
					if($row[3] > $row[2]){
						$delay = subtractTime($row[3], $row[2]);
						//echo $row[3] . ' - ' . $row[2] . ' = ' . $delay . ' <br />';
						$delaySum += $delay;
					}
					else{
						$delay = $row[6] - $row[5];
						$delaySum += $delay;
					}
				}
				else if($request->type == 'getAllFlights' && $rows > 0){
					if(($row[3] > $row[2]) || ($row[6] > $row[5])){
						$table = $table . '<td><span class="label label-danger">Delayed</span></td></tr>';
					}
					else{
						$table = $table . '<td><span class="label label-success">On-Time</span></td></tr>';
					}
				}
				
				$table = $table . '</tr>';
			}
				
			if($rows == 0){
				$table = $table . '<tr><td>No items</td></tr>';
			}
				
			$table = $table . '</tbody></table>';
			
			if($request->type == 'getDelayedFlights' && $rows > 0){
				$table = $table . '<h4>Number of delayed flights from ' . $request->fields['depart_airport'] . ' to ' . $request->fields['arrival_airport'] . ' between ' . $request->fields['startDate'] . ' and ' . $request->fields['endDate'] . ': <span class="label label-default">' . $rows . '</span></h4>';
				$table = $table . '<h4>Average Delay Time: <span class="label label-default">' . round($delaySum/$rows, 2) . ' minutes</span></h4>';
			}
			else if($request->type == 'getOnTimeFlights' && $rows > 0){
				$table = $table . '<h4>Number of on-time flights from ' . $request->fields['depart_airport'] . ' to ' . $request->fields['arrival_airport'] . ' between ' . $request->fields['startDate'] . ' and ' . $request->fields['endDate'] . ': <span class="label label-default">' . $rows . '</span></h4>';
			}
			
			showExportButton('#flights .export');
		}
	}
	echo $table;
	
	/* Subtracts two time quantities HH:MM:SS 
	 * Returns the number of minutes between two times */
	function subtractTime($t1, $t2){
		
		/* Split time strings by ':' */
		$t1 = explode(":", $t1);
		$t2 = explode(":", $t2);
		
		/* Create Decimal representations of time */
		$t1 = $t1[0] . '.' . $t1[1];
		$t2 = $t2[0] . '.' . $t2[1];
		
		/* Calculate the difference between the times 
		 * Round to 2 decimal places */
		$difference = round($t1-$t2, 2);
		
		
		$wholeNumber = explode(".", $difference)[0];
		$decimal = explode(".", $difference)[1];
		
		if($wholeNumber > 0){
			$difference = ($wholeNumber * 60) + $decimal;
		}
		else{
			$difference = $decimal;
		}
		
		/* Remove all leading zeros of the difference value */
		return ltrim($difference, '0');
	}
	
?>