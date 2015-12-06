<?php
    class ASDICommandConstructor extends CommandConstructor
    {
        
        //constuctor
        public function __construct(){ }
        
        public function transformCommand($request){
            $command = '';
            
        	if($request->type == 'SELECT *'){
            	$command = $this->selectAll($request);
            }
            else if($request->type == 'INSERT'){
            	$command = $this->insert($request);
            }
      		else if($request->type == 'Delete Restriction'){
            	$command = $this->deleteRestriction($request);
            }
            else if($request->type == 'Approve Restriction'){
            	$command = $this->approveRestriction($request);
            }
            else if($request->type == 'getPendingAirlineRestrictions'){
            	$command = $this->getPendingAirlineRestrictions();
            }
            else if($request->type == 'getPendingRegionRestrictions'){
            	$command = $this->getPendingRegionRestrictions();
            }
            else if($request->type == 'getDelaysByAirlines'){
            	$command = $this->getDelaysByAirlines($request);
            }
            else if($request->type == 'getProbabilityOfDelay'){
            	$command = $this->getProbabilityOfDelay($request);
            }
            else if($request->type == 'getValidAirlines'){
            	$command = $this->getValidAirlines($request);
            }
            else if($request->type == 'getAirports'){
            	$command = $this->getValidAirports();
            }
            else if($request->type == 'getValidRegions'){
            	$command = $this->getValidRegions($request);
            }
            else if($request->type == 'getAirportsByDelays'){
            	$command = $this->getAirportsByDelays($request);
            }
       	 	else if($request->type == 'getDelayedDeparturesByAirport'){
            	$command = $this->getDelayedDeparturesByAirport($request);
            }
            else if($request->type == 'getDelayedArrivalsByAirport'){
            	$command = $this->getDelayedArrivalsByAirport($request);
            }
            else if($request->type == 'getDelayedFlights'){
            	$command = $this->getDelayedFlights($request);
            }
      		else if($request->type == 'getOnTimeFlights'){
            	$command = $this->getOnTimeFlights($request);
            }
            else if($request->type == 'getAllFlights'){
            	$command = $this->getAllFlights($request);
            }
            else if($request->type == 'getFlightCancelations'){
            	$command = $this->getFlightCancelations($request);
            }
       		else if($request->type == 'getDelaysByRegions'){
            	$command = $this->getDelaysByRegions($request);
            }
            else if($request->type == 'getOnTimeByRegions'){
            	$command = $this->getOnTimeByRegions($request);
            }
        	else if($request->type == 'getAirspacesByTrackingMessages'){
            	$command = $this->getAirspacesByTrackingMessages($request);
            }
            else if($request->type == 'getAirspacesByFlights'){
            	$command = $this->getAirspacesByFlights($request);
            }
            else if($request->type == 'getAirspacesByDelays'){
            	$command = $this->getAirspacesByDelays($request);
            }
            else if($request->type == 'getAirspacesByCancelations'){
            	$command = $this->getAirspacesByCancelations($request);
            }
            else if($request->type == 'getMessages'){
            	$command = $this->getMessages($request);
            }
            return $command;
        }
        
        //SELECT * sql command
        public function selectAll($request){
        	//counter
        	$i = 1;
        	
        	//number of parameters included in the request
        	$numParameters = count($request->fields);
       	
       		//command - sql sting
       		$command = 'SELECT * FROM ' . $request->dbTable;
        	
       		//if there are parameters for where clause
       		if(count($request->fields) > 0){
       	
       			$command = $command . ' WHERE ';
       	
       			//for each item in the array
       			foreach($request->fields as $field => $value) {
       				$command = $command . $field . ' = "' . $value . '" ';
       	
       				//if not last element in array - add 'AND '
       				if($i++ != $numParameters) {
       					$command = $command . 'AND ';
       				}
        		}
       		}
       		return $command;
        }
        
        //INSERT sql command
        public function insert($request){
        	//counter
        	$i = 1;
        	
        	//number of parameters included in the request
        	$numParameters = count($request->fields);
        	
        	//comamand - sql string
        	$command = 'INSERT INTO ' . $request->dbTable . ' (';
        	
        	//values string
        	$values = 'VALUES (';
        	
        	//for each item in the array
        	foreach($request->fields as $field => $value) {
        	
        		$command = $command . $field;
        		$values = $values . '"' . $value . '"';
        	
        		//if last element in array - close paraenthesis
        		if($i++ == $numParameters) {
        			$command = $command . ') ';
        			$values = $values . ');';
        		}
        		//otherwise, not last element in array - add comma
        		else{
        			$command = $command . ', ';
        			$values = $values . ', ';
        		}
        	}
        	
        	return $command = $command . ''. $values;
        }
        
   		public function deleteRestriction($request){
        	$command = "DELETE FROM $request->dbTable "
        				."WHERE restriction_id = " . $request->fields['restriction_id'] . ";";
        	return $command;
        }
        
        public function approveRestriction($request){
        	$command = "UPDATE $request->dbTable "
        				."SET status = '" . $request->fields['status'] . "' WHERE restriction_id = " . $request->fields['restriction_id'] . ";";
        	return $command;
        }
        	
    	public function getAirlineRestrictions(){
        	$command = '';
        	
        	/* If the current user is a general user, only show the airlines they are not restricted to */
        	if($GLOBALS['user']->type == 'GENERAL_USER'){
        		$airlines = $GLOBALS['user']->getNonRestrictedAirlines();
        	
        		$command = $command . "AND (";
        	
        		for($i = 0; $i < count($airlines); $i++){
        			$command = $command . "f.airline_name = '" . $airlines[$i] . "'";
        			 
        			if($i+1 != count($airlines)){
        				$command = $command . " OR ";
        			}
        		}
        	
        		$command = $command . ") ";
        	}
        	
        	return $command;
        }
        
        public function getRegionRestrictions(){
        	$command = '';
        	 
        	/* If the current user is a general user, only show the regions they are not restricted to */
        	if($GLOBALS['user']->type == 'GENERAL_USER'){
        		$regions = $GLOBALS['user']->getNonRestrictedRegions();
        		 
        		$command = $command . "AND (";
        		 
        		for($i = 0; $i < count($regions); $i++){
        			$command = $command . "ap.region = '" . $regions[$i] . "'";
        
        			if($i+1 != count($regions)){
        				$command = $command . " OR ";
        			}
        		}
        		 
        		$command = $command . ") ";
        	}
        	 
        	return $command;
        }
        
   		public function getPendingAirlineRestrictions(){
        	$command = "SELECT "
        					."r.restriction_id, "
        					."r.user_id, "
        					."u.firstName, "
        					."u.lastName, "
        					."r.airline_name, "
        					."r.status "
        				."FROM se_Airline_Restrictions as r "
        					."INNER JOIN se_Users as u "
        						."ON r.user_id = u.user_id "
        				."WHERE r.status = 'PENDING_APPROVAL';";
        	return $command;
        }
        
        public function getPendingRegionRestrictions(){
        	$command = "SELECT "
        				."r.restriction_id, "
        				."r.user_id, "
        				."u.firstName, "
        				."u.lastName, "
        				."r.region, "
        				."r.status "
        			."FROM se_Region_Restrictions as r "
        				."INNER JOIN se_Users as u "
        					."ON r.user_id = u.user_id "
        			."WHERE r.status = 'PENDING_APPROVAL';";
        	return $command;
        }
        
        public function getDelaysByAirlines($request){
        	$command = "SELECT f.airline_name, COUNT(f.airline_name) "
        			."FROM se_Flights f "
        			."INNER JOIN se_Departure d "
						."ON f.flight_number = d.flight_number "
					."INNER JOIN se_Arrival a "
						."ON f.flight_number = a.flight_number "
					."WHERE (d.departure_time > f.departure_time OR a.arrival_time > f.arrival_time) "
						."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
						."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
						.$this->getAirlineRestrictions()
        			. "GROUP BY f.airline_name " 
					."ORDER BY COUNT(f.airline_name) DESC;";
        	
        	return $command;
        }
        
        public function getProbabilityOfDelay($request){
        	$command = "SELECT f.flight_number, f.airline_name, f.departure_date, f.departure_airport, a.region, f.departure_time, d.departure_time "
        			."FROM se_Flights f "
        			."INNER JOIN se_Airports a "
        				."ON f.departure_airport = a.airport_name "
        			."INNER JOIN se_Departure d "
        				."ON f.flight_number = d.flight_number "
        			."WHERE a.region = '" . $request->fields['region'] . "' "
        				."AND f.airline_name = '" . $request->fields['airline'] . "' "	
        				."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        				."AND f.departure_date <= '" . $request->fields['endDate'] . "';";
        	return $command;
        }
        
        public function getValidAirlines($request){
        	$command = "SELECT a.airline_name "
        			."FROM se_Airlines a "
        			."LEFT JOIN "
        				."(SELECT * FROM se_Airline_Restrictions WHERE user_id = " . $request->fields['user_id'] . ") as r "
        				."ON r.airline_name = a.airline_name "
        			."WHERE r.airline_name IS NULL;";
        	return $command;
        }
        
    public function getValidAirports(){
        	$command = "SELECT ap.airport_name " 
        				."FROM se_Airports ap " 
						."LEFT JOIN se_Flights as f1 "
							."ON ap.airport_name = f1.departure_airport " 
						."LEFT JOIN se_Flights as f2 "
							."ON ap.airport_name = f2.arrival_airport " 
						."WHERE f1.departure_airport = ap.airport_name "  
							."OR f2.arrival_airport = ap.airport_name " 
						."GROUP BY airport_name;";
        	return $command;	
        }
        
        public function getValidRegions($request){
        	$command = "SELECT a.region "
        			."FROM se_Airports a "
        			."LEFT JOIN "
        				."(SELECT * FROM se_Region_Restrictions WHERE user_id = " . $request->fields['user_id'] . ") as r "
        				."ON r.region = a.region "
        			."WHERE r.region IS NULL "
        			."GROUP BY a.region;";
        	return $command;
        }
        
        public function getAirportsByDelays($request){
        	$command = "SELECT f.departure_airport, COUNT(f.departure_airport) "
					."FROM se_Flights f "
					."INNER JOIN se_Departure as d "
						."ON f.flight_number = d.flight_number "
					."WHERE (d.departure_time > f.departure_time) "
						."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
						."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
					."GROUP BY f.departure_airport "
					."ORDER BY COUNT(f.departure_airport) DESC;";
        	return $command;
        }
        
        public function getDelayedDeparturesByAirport($request){
        	$command = "SELECT f.flight_number, f.departure_date, f.departure_time as filed_time, d.departure_time as flown_time "
					."FROM se_Flights f "
					."INNER JOIN se_Departure d "
						."ON f.flight_number = d.flight_number " 
					."WHERE d.departure_time > f.departure_time " 
						."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
						."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
						."AND f.departure_airport = '" . $request->fields['airport'] . "';";
        	return $command;	
        }
        
        public function getDelayedArrivalsByAirport($request){
        	$command = "SELECT f.flight_number, f.arrival_date, f.arrival_time as filed_time, a.arrival_time as flown_time "
					."FROM se_Flights f "
					."INNER JOIN se_Arrival a "
						."ON f.flight_number = a.flight_number " 
					."WHERE a.arrival_time > f.arrival_time " 
						."AND f.arrival_date >= '" . $request->fields['startDate'] . "' "
						."AND f.arrival_date <= '" . $request->fields['endDate'] . "' "
						."AND f.arrival_airport = '" . $request->fields['airport'] . "';";
        	return $command;	
        }
        
        public function getDelayedFlights($request){
        	$command = "SELECT f.flight_number, "
        				."f.departure_date, "
        				."f.departure_time as filed_depart, "
        				."d.departure_time as flown_depart, "
        				."f.arrival_date, "
        				."f.arrival_time as filed_arrival, "
        				."a.arrival_time as flown_arrival "
					."FROM se_Flights f "
					."INNER JOIN se_Departure d "
						."ON f.flight_number = d.flight_number " 
					."INNER JOIN se_Arrival a "
						."ON f.flight_number = a.flight_number "
					."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
						."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
						."AND (d.departure_time > f.departure_time OR a.arrival_time > f.arrival_time) "
						."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
						."AND f.departure_date <= '" . $request->fields['endDate'] . "';";
        	return $command;
        }
        
        public function getOnTimeFlights($request){
        	$command = "SELECT f.flight_number, "
        				."f.departure_date, "
        				."f.departure_time as filed_depart, "
        				."d.departure_time as flown_depart, "
        				."f.arrival_date, "
        				."f.arrival_time as filed_arrival, "
        				."a.arrival_time as flown_arrival "
					."FROM se_Flights f "
					."INNER JOIN se_Departure d "
						."ON f.flight_number = d.flight_number " 
					."INNER JOIN se_Arrival a "
						."ON f.flight_number = a.flight_number "
					."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
						."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
						."AND d.departure_time = f.departure_time "
						."AND a.arrival_time = f.arrival_time "
						."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
						."AND f.departure_date <= '" . $request->fields['endDate'] . "';";
        	return $command;
        }
        
        public function getAllFlights($request){
        	$command = "SELECT f.flight_number, "
        				."f.departure_date, "
        				."f.departure_time as filed_depart, "
        				."d.departure_time as flown_depart, "
        				."f.arrival_date, "
        				."f.arrival_time as filed_arrival, "
        				."a.arrival_time as flown_arrival "
        			."FROM se_Flights f "
        			."INNER JOIN se_Departure d "
        				."ON f.flight_number = d.flight_number "
        			."INNER JOIN se_Arrival a "
        				."ON f.flight_number = a.flight_number "
        			."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
        				."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
        				."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        				."AND f.departure_date <= '" . $request->fields['endDate'] . "';";
        	return $command;
        }
        
        public function getFlightCancelations($request){
        	$command = "SELECT f.flight_number, "
        				."f.departure_date, "
        				."f.departure_time as filed_depart, "
        				."f.arrival_date, "
        				."f.arrival_time as filed_arrival, "
        				."a.amendment_description "
        				."FROM se_Flights f "
						."INNER JOIN se_Cancelation c "
							."ON c.flight_number = f.flight_number "
						."INNER JOIN se_Amendment a "
							."ON a.cancelation_id = c.cancelation_id "
						."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
							."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
							."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
							."AND f.departure_date <= '" . $request->fields['endDate'] . "';";
        	return $command;
        }
        
            public function getDelaysByRegions($request){
        	$command = "SELECT region, SUM(numberOfDelays) "
        				."FROM ( " 
        					."SELECT * FROM ( "
        						."SELECT ap.region, COUNT(ap.region) as numberOfDelays "
								."FROM se_Flights f "
								."INNER JOIN se_Departure d "
									."ON f.flight_number = d.flight_number " 
								."INNER JOIN se_Arrival a "
									."ON f.flight_number = a.flight_number "
								."INNER JOIN se_Airports as ap "
									."ON f.departure_airport = ap.airport_name "
								."WHERE (d.departure_time > f.departure_time OR a.arrival_time > f.arrival_time) "
									."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
									."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
									.$this->getRegionRestrictions()
								."GROUP BY (ap.region)) s1	"
								."UNION "
							."SELECT * FROM ( "
								."SELECT ap.region, COUNT(ap.region) as numberOfDelays "
								."FROM se_Flights f "
								."INNER JOIN se_Departure d "
									."ON f.flight_number = d.flight_number " 
								."INNER JOIN se_Arrival a "
									."ON f.flight_number = a.flight_number "
								."INNER JOIN se_Airports as ap "
									."ON f.arrival_airport = ap.airport_name "
								."WHERE (d.departure_time > f.departure_time OR a.arrival_time > f.arrival_time) "	
									."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
									."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
									.$this->getRegionRestrictions()
								."GROUP BY (ap.region)) s2) as t "
						."GROUP BY region;";
        	return $command;
        }

        public function getOnTimeByRegions($request){
        	$command = "SELECT region, SUM(numberOfDelays) "
        				."FROM ( "
        					."SELECT * FROM ( "
        						."SELECT ap.region, COUNT(ap.region) as numberOfDelays "
        							."FROM se_Flights f "
        							."INNER JOIN se_Departure d "
        								."ON f.flight_number = d.flight_number "
        							."INNER JOIN se_Arrival a "
        								."ON f.flight_number = a.flight_number "
        							."INNER JOIN se_Airports as ap "
        								."ON f.departure_airport = ap.airport_name "
        							."WHERE (d.departure_time = f.departure_time AND a.arrival_time = f.arrival_time) "
        								."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        								."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
        								.$this->getRegionRestrictions()
        							."GROUP BY (ap.region)) s1	"
        							."UNION "
        						."SELECT * FROM ( "
        							."SELECT ap.region, COUNT(ap.region) as numberOfDelays "
        							."FROM se_Flights f "
        							."INNER JOIN se_Departure d "
        								."ON f.flight_number = d.flight_number "
        							."INNER JOIN se_Arrival a "
        								."ON f.flight_number = a.flight_number "
        							."INNER JOIN se_Airports as ap "
        								."ON f.arrival_airport = ap.airport_name "
        							."WHERE (d.departure_time = f.departure_time AND a.arrival_time = f.arrival_time) "
        								."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        								."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
        								.$this->getRegionRestrictions()
        							."GROUP BY (ap.region)) s2) as t "
        						."GROUP BY region;";
        	return $command;
        }        
        
        public function getAirspacesByTrackingMessages($request){
        	$command = "SELECT " 
        					."t.airspace_id, "
        					."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.beginning_lat, ', '), asp.beginning_long), '] ')) as Point1, "
        					."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.ending_lat, ', '), asp.ending_long), '] ')) as Point2, "
        					."COUNT(t.airspace_id) " 
						."FROM se_Tracking as t "
						."INNER JOIN se_Flights as f "
							."ON f.flight_number = t.flight_number "
						."INNER JOIN se_Airspace as asp "
							."ON t.airspace_id = asp.airspace_id "
						."WHERE f.departure_date >= '" . $request->fields['startDate'] . "' "
							."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
						."GROUP BY t.airspace_id "
						."ORDER BY COUNT(t.airspace_id) DESC;";
        	return $command;
        }
        
        public function getAirspacesByFlights($request){
        	$command = "SELECT "
        			."t.airspace_id, "
        				."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.beginning_lat, ', '), asp.beginning_long), '] ')) as Point1, "
        				."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.ending_lat, ', '), asp.ending_long), '] ')) as Point2, "
        				."COUNT(f.flight_number) "
        			."FROM se_Tracking as t "
        			."INNER JOIN se_Flights as f "
        				."ON f.flight_number = t.flight_number "
        			."INNER JOIN se_Airspace as asp "
        				."ON t.airspace_id = asp.airspace_id "
        			."WHERE f.departure_date >= '" . $request->fields['startDate'] . "' "
        				."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
        			."GROUP BY t.airspace_id "
        			."ORDER BY COUNT(f.flight_number) DESC;";
        	return $command;
        }
        
        public function getAirspacesByDelays($request){
        	$command = "SELECT "
        					."t.airspace_id, "
        					."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.beginning_lat, ', '), asp.beginning_long), '] ')) as Point1, "
        					."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.ending_lat, ', '), asp.ending_long), '] ')) as Point2, "
        					."COUNT(t.airspace_id) " 
						."FROM se_Tracking as t "
						."INNER JOIN se_Airspace as asp "
							."ON t.airspace_id = asp.airspace_id "
						."INNER JOIN se_Flights f "
							."ON f.flight_number = t.flight_number "
						."INNER JOIN se_Departure d "
							."ON f.flight_number = d.flight_number " 
						."INNER JOIN se_Arrival a "
							."ON f.flight_number = a.flight_number "
						."WHERE (d.departure_time > f.departure_time OR a.arrival_time > f.arrival_time) "
							."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
							."AND f.departure_date <= '" . $request->fields['endDate'] . "' "
						."GROUP BY t.airspace_id "
						."ORDER BY COUNT(t.airspace_id) DESC;";
        	return $command;
        }
        
        public function getAirspacesByCancelations($request){
        	$command = "SELECT " 
							."asp.airspace_id, " 
							."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.beginning_lat, ', '), asp.beginning_long), '] ')) as Point1, "
							."CONCAT('[',CONCAT(CONCAT(CONCAT(asp.ending_lat, ', '), asp.ending_long), '] ')) as Point2,	" 
							."COUNT(c.cancelation_id) " 
						."FROM se_Airspace as asp " 
						."INNER JOIN se_Cancelation as c "
							."ON c.cancelation_location = asp.airspace_id "
						."WHERE c.cancelation_date >= '" . $request->fields['startDate'] . "' " 
						."AND c.cancelation_date <= '" . $request->fields['endDate'] . "' " 
						."GROUP BY c.cancelation_id "
						."ORDER BY COUNT(c.cancelation_id) DESC;";
        	return $command;
        }
        
        public function getMessages($request){
        	$command = 'SELECT * FROM (';
        	
        	$union = '';
        	
        	if($request->fields['getAmendments']){
        		$union = $union ."SELECT "
        							."c.flight_number, "
        							."c.cancelation_date as Date, "
        							."c.cancelation_time as Time, "
        							."CONCAT('Cancelation','') as MessageType, "
        							."a.amendment_description as MessageDescription "
								."FROM se_Flights as f "
								."INNER JOIN se_Cancelation as c "
									."ON c.flight_number = f.flight_number " 
								."INNER JOIN se_Amendment as a "
									."ON a.cancelation_id = c.cancelation_id "
								."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
									."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
									."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
									."AND f.departure_date <= '" . $request->fields['endDate'] . "' ";
        	}
        	
        	if($request->fields['getCrossing']){
        		if(strlen($union) > 0){
        			$union = $union . "UNION ";
        		}
        		$union = $union ."SELECT "
        							."c.flight_number, "
        							."c.crossing_date as Date, "
        							."c.crossing_time as Time, "
        							."CONCAT('Crossing','') as MessageType, "
        							."CONCAT('Flight ', CONCAT(f.flight_number ,CONCAT(CONCAT(CONCAT(' crossed from airspace ', c.exit_airspace_id), ' to '), c.entry_airspace_id)))as MessageDescription "
								."FROM se_Flights as f "
								."INNER JOIN se_Crossing as c "
									."ON c.flight_number = f.flight_number "
        						."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
        							."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
       								."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        							."AND f.departure_date <= '" . $request->fields['endDate'] . "' ";
        	}
        	
        	if($request->fields['getDeparture']){
        		if(strlen($union) > 0){
        			$union = $union . "UNION ";
        		}
        		$union = $union ."SELECT "
        							."d.flight_number, "
        							."d.departure_date as Date, "
        							."d.departure_time as Time, "
        							."CONCAT('Departure','') as MessageType, "
        							."CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT('Flight ', f.flight_number), ' arrived at '), f.departure_airport), ' on '), d.departure_date), ' at '), d.departure_time) as MessageDescription "
								."FROM se_Flights as f "
								."INNER JOIN se_Departure as d "
									."ON d.flight_number = f.flight_number "
        						."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
        							."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
        							."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        							."AND f.departure_date <= '" . $request->fields['endDate'] . "' ";
        	}
        	
        	if($request->fields['getArrival']){
        		if(strlen($union) > 0){
        			$union = $union . "UNION ";
        		}
        		$union = $union ."SELECT "
        							."a.flight_number, "
        							."a.arrival_date as Date, "
        							."a.arrival_time as Time, "
        							."CONCAT('Arrival','') as MessageType, "
        							."CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(CONCAT('Flight ', f.flight_number), ' arrived at '), f.departure_airport), ' on '), a.arrival_date), ' at '), a.arrival_time) as MessageDescription "
								."FROM se_Flights as f "
								."INNER JOIN se_Arrival as a "
									."ON a.flight_number = f.flight_number "
        						."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
        							."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
        							."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        							."AND f.departure_date <= '" . $request->fields['endDate'] . "' ";
        	}
        	
        	if($request->fields['getTracking']){
        		if(strlen($union) > 0){
        			$union = $union . "UNION ";
        		}
        		$union = $union ."SELECT "
        							."t.flight_number, "
        							."f.departure_date as Date, "
        							."t.tracking_time as Time, "
        							."CONCAT('Tracking','') as MessageType, "
        							."CONCAT(t.tracking_time, CONCAT(CONCAT(CONCAT(' - Flight ', f.flight_number), ' is in airspace '), t.airspace_id)) as MessageDescription "
								."FROM se_Flights as f "
								."INNER JOIN se_Tracking as t "
									."ON t.flight_number = f.flight_number "
        						."WHERE f.departure_airport = '" . $request->fields['depart_airport'] . "' "
        							."AND f.arrival_airport = '" . $request->fields['arrival_airport'] . "' "
        							."AND f.departure_date >= '" . $request->fields['startDate'] . "' "
        							."AND f.departure_date <= '" . $request->fields['endDate'] . "' ";
        	}
        	
        	/* Joing Command and Union */
        	$command = $command  . $union . ") as s ORDER BY " . $request->fields['orderBy'];
        	
        	return $command;
        }
    }
?>