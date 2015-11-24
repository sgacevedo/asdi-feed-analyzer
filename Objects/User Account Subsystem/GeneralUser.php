<?php
    class GeneralUser extends User
    {
        //constuctor
        public function __construct($email){
            $this->email = $email;
        }
        
        //returns airlines that the current user is not restricted to
        public function getNonRestrictedAirlines(){
        	
        	$airlines = array();
        	
	        $dbMan = new DatabaseManager();
			
	        /* Establish connection with database
	         * if the establishConnection function 
	         * returns false, a connection error occured*/
			if(!$dbMan->establishConnection()){
				//database connection error
				return;
			}
        	
			/* Create request to get valid airlines for user_id provided */
			$request = new Request('getValidAirlines', 'se_Airlines');
			$request->addParameter('user_id', $this->id);
			
			/* Transform the Request into an MySQL command*/
			$request->transformCommand();
			
			/* Execute command to get valid Airlines */
			$validAirlines = $dbMan->executeQuery($request);
			
			//server error
			if($validAirlines == null){
				//request was unsuccessful
			}
			
			else if($validAirlines -> num_rows){
				
				/* Get number of rows returned */
				$rows = $validAirlines->num_rows;
					
				/* For each row - push the airline name
				 * onto the $airlines array */
				for ($i = 0 ; $i < $rows ; ++$i){
					$validAirlines->data_seek($i);
					$row = $validAirlines->fetch_array(MYSQLI_NUM);
					
					/* Push value onto array */
					array_push($airlines,$row[0]);
				}
			}
			
			/* Return Valid airlines */
			return $airlines;
        }
    }
    ?>