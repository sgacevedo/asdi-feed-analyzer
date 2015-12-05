<?php	
	$table = '';
	
	/* If date range and airport is set */
	if(isset($_POST['AIRPORT_STARTDATE']) && isset($_POST['AIRPORT_ENDDATE'])){
		
		$dbMan = new DatabaseManager();
		$request;
		
		/* Establish Connection with database */
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
		
		/* Get the value of the selected airport radio button */
		$airportRadioButton = $_POST['AIRPORT_RADIO'];
		
		/* If the user has selected all airports */
		if($airportRadioButton == "all_airports"){
			
			/* Create new request to get airports by delays */
			$request = new Request('getAirportsByDelays', 'se_Flights');
			
			/* Pass in date range variables */
			$request->addParameter('startDate', $_POST['AIRPORT_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRPORT_ENDDATE']);
			
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Airport</th>'
								.'<th>Number of Delays</th>'
							.'</tr>'
						.'</thead>'
						.'<tbody>';
		}
		/* If the user has selected one airport specifically */
		else if($airportRadioButton == "one_airport"){
			
			/* Get the value of the selected airport delay radio button */
			$delayRadioButton = $_POST['AIRPORT_DELAY_RADIO'];
			
			/* If the user selects delayed departures only */
			if($delayRadioButton == 'delayed_departures'){
				
				$request = new Request('getDelayedDeparturesByAirport', 'se_Airports');
				
				/* Pass in date range variables and airport */
				$request->addParameter('startDate', $_POST['AIRPORT_STARTDATE']);
				$request->addParameter('endDate', $_POST['AIRPORT_ENDDATE']);
				$request->addParameter('airport', $_POST['AIRPORT_NAME']);
				
				/* Create layout for table */
				$table = '<table class="table table-hover">'
							.'<thead>'
								.'<tr>'
									.'<th>Flight Number</th>'
									.'<th>Departure Date</th>'
									.'<th>Filed Departure Time</th>'
									.'<th>Flown Departure Time</th>'
								.'</tr>'
							.'</thead>'
							.'<tbody>';
			}
			
			/* If the user selects delayed arrivals only */
			else if($delayRadioButton == 'delayed_arrivals'){
				
				$request = new Request('getDelayedArrivalsByAirport', 'se_Airports');
				
				/* Pass in date range variables */
				$request->addParameter('startDate', $_POST['AIRPORT_STARTDATE']);
				$request->addParameter('endDate', $_POST['AIRPORT_ENDDATE']);
				$request->addParameter('airport', $_POST['AIRPORT_NAME']);
				
				/* Create layout for table */
				$table = '<table class="table table-hover">'
							.'<thead>'
								.'<tr>'
									.'<th>Flight Number</th>'
									.'<th>Arrival Date</th>'
									.'<th>Filed Arrival Time</th>'
									.'<th>Flown Arrival Time</th>'
								.'</tr>'
							.'</thead>'
							.'<tbody>';
			}
		}
		
		/* Transform the request into a command */
		$request->transformCommand();
		
		/* database manager executes query */
		$results = $dbMan->executeQuery($request);
		
		if($results == null){
			//request failed
		}
		else{
			$rows = $results->num_rows;
			
			for ($i = 0 ; $i < $rows ; ++$i){
				$results->data_seek($i);
				$row = $results->fetch_array(MYSQLI_NUM);
					
				$table = $table . '<tr>';
				for($j = 0; $j < count($row); ++$j){
						
					$table = $table . '<td>'. $row[$j] .'</td>';
				}
				$table = $table . '</tr>';
			}
			
			if($rows == 0){
				$table = $table . '<tr><td>No items</td></tr>';
			}
			
			$table = $table . '</tbody></table>';
			
			if($request->type == 'getDelayedDeparturesByAirport'){
				$table = $table . '<h4>Number of delayed flights departing from ' . $request->fields['airport'] . ' between ' . $request->fields['startDate'] . ' and ' . $request->fields['endDate'] . ': <span class="label label-default">' . $rows . '</span></h4>';
			}
			else if($request->type == 'getDelayedArrivalsByAirport'){
				$table = $table . '<h4>Number of delayed flights arriving to ' . $request->fields['airport'] . ' between ' . $request->fields['startDate'] . ' and ' . $request->fields['endDate'] . ': <span class="label label-default">' . $rows . '</span></h4>';
			}
			
			showExportButton('#airports .export');
		}
	}
	echo $table;
	
?>