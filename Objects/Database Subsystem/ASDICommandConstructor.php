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
					."GROUP BY f.airline_name;";
        	
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
        
    }
?>