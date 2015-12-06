<?php	
	$table = '';
	
	/* If date range for airspace is set */
	if(isset($_POST['AIRSPACE_STARTDATE']) && isset($_POST['AIRSPACE_ENDDATE'])){
		
		$dbMan = new DatabaseManager();
		$request;
		
		/* Establish Connection with database */
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
		
		/* Get the value of the selected airport radio button */
		$airspaceRadioButton = $_POST['AIRSPACE_RADIO'];
		
		/* If the user has selected to rank airspaces by flights */
		if($airspaceRadioButton == "rankByFlights"){
				
			/* Create new request to get airspaces by flights */
			$request = new Request('getAirspacesByFlights', 'se_Airspaces');
				
			/* Pass in date range variables */
			$request->addParameter('startDate', $_POST['AIRSPACE_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRSPACE_ENDDATE']);
				
			/* Create layout for table */
			$table = '<table class="table table-hover">'
					.'<thead>'
					.'<tr>'
						.'<th>Airspace Id</th>'
						.'<th>Airspace Point 1</th>'
						.'<th>Airspace Point 2</th>'
						.'<th>Number of Flights</th>'
						.'<th></th>'
					.'</tr>'
					.'</thead>'
					.'<tbody>';
		}
		/* If the user has selected to rank airspaces by tracking messages */
		else if($airspaceRadioButton == "rankByTracking"){
			
			/* Create new request to get airspaces by flights */
			$request = new Request('getAirspacesByTrackingMessages', 'se_Airspaces');
			
			/* Pass in date range variables */
			$request->addParameter('startDate', $_POST['AIRSPACE_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRSPACE_ENDDATE']);
			
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Airspace Id</th>'
								.'<th>Airspace Point 1</th>'
								.'<th>Airspace Point 2</th>'
								.'<th>Number of Tracking Messages</th>'
								.'<th></th>'
							.'</tr>'
						.'</thead>'
						.'<tbody>';
		}
		/* If the user has selected to rank the airspaces by delays */
		else if($airspaceRadioButton == "rankByDelays"){
			
			/* Create new request to get airspaces by flights */
			$request = new Request('getAirspacesByDelays', 'se_Airspaces');
				
			/* Pass in date range variables */
			$request->addParameter('startDate', $_POST['AIRSPACE_STARTDATE']);
			$request->addParameter('endDate', $_POST['AIRSPACE_ENDDATE']);
				
			/* Create layout for table */
			$table = '<table class="table table-hover">'
						.'<thead>'
							.'<tr>'
								.'<th>Airspace Id</th>'
								.'<th>Airspace Point 1</th>'
								.'<th>Airspace Point 2</th>'
								.'<th>Number of Flights</th>'
								.'<th></th>'
							.'</tr>'
						.'</thead>'
						.'<tbody>';
		}
		
		/* If the user has selected to rank the airspaces by number of messages*/
		else if($airspaceRadioButton == 'rankByMessages'){
		
		/* Create new request to get airspaces by flights */
		$request = new Request('getAirspacesByCancelations', 'se_Airspaces');
		
		/* Pass in date range variables */
		$request->addParameter('startDate', $_POST['AIRSPACE_STARTDATE']);
		$request->addParameter('endDate', $_POST['AIRSPACE_ENDDATE']);
		
		/* Create layout for table */
		$table = '<table class="table table-hover">'
				.'<thead>'
				.'<tr>'
					.'<th>Airspace Id</th>'
					.'<th>Airspace Point 1</th>'
					.'<th>Airspace Point 2</th>'
					.'<th>Number of Cancelation Messages</th>'
					.'<th></th>'
				.'</tr>'
				.'</thead>'
				.'<tbody>';
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
				
				$table = $table . '<td><button type="button" class="btn btn-primary plotButton">Plot</button></td>';
				$table = $table . '</tr>';
			}
			
			if($rows == 0){
				$table = $table . '<tr><td>No items</td></tr>';
			}
			
			$table = $table . '</tbody></table><script type="text/javascript">$(document).ready(function(){ initMap(); });</script>';
			
			showExportButton('#airspace .export');
		}
	}
	echo $table;
	
?>