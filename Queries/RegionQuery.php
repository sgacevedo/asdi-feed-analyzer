<?php	
	$table = '';
	
	/* If date range for region is set */
	if(isset($_POST['REGION_STARTDATE']) && isset($_POST['REGION_ENDDATE'])){
		
		$dbMan = new DatabaseManager();
		$request;
		
		/* Establish Connection with database */
		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}
			
		/* Create new request to get airports by delays */
		$request = new Request('getDelaysByRegions', 'se_Flights');
		
		/* Pass in date range variables */
		$request->addParameter('startDate', $_POST['REGION_STARTDATE']);
		$request->addParameter('endDate', $_POST['REGION_ENDDATE']);
		
		/* Create layout for table */
		$table = '<table class="table table-hover">'
					.'<thead>'
						.'<tr>'
							.'<th>Region</th>'
							.'<th>Number of Delays</th>'
						.'</tr>'
					.'</thead>'
					.'<tbody>';
		
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
			
			$table = $table . '<button type="button" class="btn btn-primary generateModel" onClick="' .$request->type. '(this)">Generate Model</button>';
		}
	}
	echo $table;
	
?>