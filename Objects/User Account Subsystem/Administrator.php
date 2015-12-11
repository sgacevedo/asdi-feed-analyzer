<?php
    class Administrator extends User
    {
        //constuctor
        public function __construct($email){
            $this->email = $email;
        }
        
        public function addRestrictionRequest($userId, $restrictionType, $restriction){
        
        	//create instance of Database Manager object
        	$dbMan = new DatabaseManager();
        
        	//establish connection
        	//if returns false, connection failed
        	if(!$dbMan->establishConnection()){
        		//database connection error
        		return false;
        	}
        
        	//if restriction type is region - add to se_Region_Restrictions
        	if($restrictionType == 'region'){
        		$request = new Request('INSERT', 'se_Region_Restrictions');
        		$request->addParameter('user_id', $userId);
        		$request->addParameter('region', $restriction);
        		$request->addParameter('status', 'PENDING_APPROVAL');
        	}
        	//otherwise, if restriction type is airline - add to se_Airline_Restrictions
        	else if($restrictionType == 'airline'){
        		$request = new Request('INSERT', 'se_Airline_Restrictions');
        		$request->addParameter('user_id', $userId);
        		$request->addParameter('airline_name', $restriction);
        		$request->addParameter('status', 'PENDING_APPROVAL');
        	}
        
        	//transform the command to sql statement
        	$request->transformCommand();
        	//echo $request->command;
        
        	//execute command
        	$results = $dbMan->executeQuery($request);
        
        	//if results is not null, command was successfully executed.
        	if($results != null){
        		//successfully approved
        		return true;
        	}
        
        	//command was not successfully executed.
        	return false;
        }
        
        public function removeRestrictionRequest($restrictionId, $restrictionTable){
        	
        	
        	//create instance of Database Manager object
        	$dbMan = new DatabaseManager();
        	
        	//establish connection
        	//if returns false, connection failed
        	if(!$dbMan->establishConnection()){
        		//database connection error
        		return false;
        	}
        	
        	/* Create new request to remove restriction*/
			$request = new Request('Delete Restriction', $restrictionTable);
			$request->addParameter('restriction_id', $restrictionId);
        	
        	//transform the command to sql statement
        	$request->transformCommand();
        	
        	//execute command
        	$results = $dbMan->executeQuery($request);
        	
        	//if results is not null, command was successfully executed.
        	if($results != null){
        		//successfully approved
        		return true;
        	}
        	
        	//command was not successfully executed.
        	return false;
        }
    }
    ?>