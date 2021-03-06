<?php
class CommandConstructor
{
	
	//constuctor
	public function __construct(){ }
	
	public function transformCommand($request){
        $command = '';
        
        //if request is from seUsers table
        if($request->dbTable == 'se_Users'){
            
            //create UserCommandConstructor instance
            $UserCC = new UserCommandConstructor();
            
            //transform command
            $command = $UserCC->transformCommand($request);
        }
        
        else if($request->dbTable == 'se_Flights' || 
				$request->dbTable == 'se_Airlines' ||
				$request->dbTable == 'se_Airports' ||
				$request->dbTable == 'se_Region_Restrictions' ||
        		$request->dbTable == 'se_Airline_Restrictions' ||
				$request->dbTable == 'se_Airspaces'){
        	
        	//create ASDICommandConstructor instance
        	$asdiCC = new ASDICommandConstructor();
        	
        	//transform command
        	$command = $asdiCC->transformCommand($request);
        }
        
        //assign the request's command field to new command string
		$request->command = $command;
	}

}
?>