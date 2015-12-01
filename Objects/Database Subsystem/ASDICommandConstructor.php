<?php
    class ASDICommandConstructor extends CommandConstructor
    {
        
        //constuctor
        public function __construct(){ }
        
        public function transformCommand($request){
            $command = '';
            
            if($request->type == 'getDelaysByAirlines'){
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
            else if($request->type == 'getFlightCancelations'){
            	$command = $this->getFlightCancelations($request);
            }
            else if($request->type == 'getDelaysByRegions'){
            	$command = $this->getDelaysByRegions($request);
            }
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
    }
?>