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
       	 	else if($request->type == 'getDelayedDeparturesByAirport'){
            	$command = $this->getDelayedDeparturesByAirport($request);
            }
            else if($request->type == 'getDelayedArrivalsByAirport'){
            	$command = $this->getDelayedArrivalsByAirport($request);
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
					."GROUP BY f.airline_name " 
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
					."LEFT JOIN se_Departure as d "
						."ON ap.airport_name = d.departure_airport "
					."LEFT JOIN se_Arrival as a "
						."ON ap.airport_name = a.arrival_airport "
					."WHERE d.departure_airport = ap.airport_name " 
						."OR a.arrival_airport = ap.airport_name "
					."GROUP BY airport_name;";
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
    }
?>