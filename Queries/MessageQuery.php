<?php
$table = '';

	/* if the message date range is posted */
	if(isset($_POST['MESSAGE_STARTDATE']) && isset($_POST['MESSAGE_ENDDATE'])){

		$dbMan = new DatabaseManager();
		$request = '';
		
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
		
		$startDate = $_POST['MESSAGE_STARTDATE'];
		$endDate = $_POST['MESSAGE_ENDDATE'];
		$departureAirport = $_POST['MESSAGE_DEPARTING_AIRPORT'];
		$arrivalAirport = $_POST['MESSAGE_ARRIVAL_AIRPORT'];
		
		$table = '<table class="table table-hover">'
					.'<thead>'
						.'<tr>'
							.'<th>Flight Number</th>'
							.'<th>Message Date</th>'
							.'<th>Message Time</th>'
							.'<th>Message Type</th>'
							.'<th>Message Description</th>'
						.'</tr>'
					.'</thead>'
					.'<tbody>';
		
		$request = new Request('getMessages', 'se_Flights');
		$request->addParameter('startDate', $startDate);
		$request->addParameter('endDate', $endDate);
		$request->addParameter('depart_airport', $departureAirport);
		$request->addParameter('arrival_airport', $arrivalAirport);
		
		/* Determine what messages to provide in the query results */
		
		/* If the Amendment/Cancelation checkbox is checked */
		if(isset($_POST['MESSAGES_AMENDMENTS'])){
			$request->addParameter('getAmendments', true);
		}
		else{ $request->addParameter('getAmendments', false);}
			
		/* If the Crossing checkbox is checked */
		if(isset($_POST['MESSAGES_CROSSINGS'])){
			$request->addParameter('getCrossing', true);
		}
		else{ $request->addParameter('getCrossing', false);}
		
		/* If the Departures checkbox is checked */
		if(isset($_POST['MESSAGES_DEPARTURES'])){
			$request->addParameter('getDeparture', true);
		}
		else{ $request->addParameter('getDeparture', false);}
		
		/* If the Arrivals checkbox is checked */
		if(isset($_POST['MESSAGES_ARRIVALS'])){
			$request->addParameter('getArrival', true);
		}
		else{ $request->addParameter('getArrival', false);}
		
		/* If the Tracking checkbox is checked */
		if(isset($_POST['MESSAGES_TRACKING'])){
			$request->addParameter('getTracking', true);
		}
		else{ $request->addParameter('getTracking', false);}
		
		
		/* Determine what to order the results by */
		$orderBy = $_POST['SORT_SElECT'];
		
		if($orderBy == 'sort_by_type'){
			$request->addParameter('orderBy', 'MessageType');
		}
		else if($orderBy == 'sort_by_flightNumber'){
			$request->addParameter('orderBy', 'flight_number');
		}
		
		/* Transform command */
		$request->transformCommand();
		
		/* Execute Query */
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
		
			}
			if($rows == 0){
				$table = $table . '<tr><td>No items</td></tr>';
			}
				
			$table = $table . '</tbody></table>';
				
		}
	}
	
	echo $table;
	
?>