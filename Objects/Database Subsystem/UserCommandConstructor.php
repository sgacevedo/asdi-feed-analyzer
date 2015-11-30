<?php
    class UserCommandConstructor extends CommandConstructor
    {
        
        //constuctor
        public function __construct(){ }
        
        public function transformCommand($request){
            $command = '';
            $numParameters = count($request->fields);
            
            //UPDATE status sql command
            if($request->type == 'UPDATE status'){
                
                $command = 'UPDATE ' . $request->dbTable . ' SET status = ' . $request->fields['status'] . ' WHERE user_id = ' . $request->fields['user_id'];
                
            }
            
            //DELETE sql command
            else if($request->type == 'DELETE'){
                //counter
                $i = 1;
                
                //command - sql sting
                $command = 'DELETE FROM ' . $request->dbTable;
                
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
            }
            
            //UPDATE sql command
            else if($request->type == 'UPDATE'){
            	//counter
            	$i = 1;
            
            	//comamand - sql string
            	$command = 'UPDATE ' . $request->dbTable . ' SET ';
            
            
            	//for each item in the array
            	foreach($request->fields as $field => $value) {
            		
            		if($field != 'user_id'){
	            		$command = $command . $field . " = '" . $value . "'";
	            
	            		//if not last element in array - add comma
	            		if($i != $numParameters){
	            			$command = $command . ', ';
	            		}
            		}
            		
            		//if last element in array - close paraenthesis
            		if($i == $numParameters) {
            			$command = $command . ' WHERE user_id = ' . $request->fields['user_id'] .';';
            		}
            		$i++;
            	}
            	$command = $command;
            }
            
        	//INSERT sql command
            else if($request->type == 'INSERT'){
                //counter
                $i = 1;
                
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
                
                $command = $command . ''. $values;
            }
            
            //SELECT * sql command
            else if($request->type == 'SELECT *'){
                //counter
                $i = 1;
                
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
            }

            else if($request->type == 'selectActiveUsers'){
            	 $command = "SELECT U.user_id, U.firstName, U.lastName, AR.restriction_id, AR.airline_name "
            	 			."FROM se_Users AS U "
            	 			."INNER JOIN se_Airline_Restrictions as AR "
            	 				."ON AR.user_id = U.user_id "
            	 			."WHERE U.status = '" . $request->fields['status'] . "';";
            }
            
            //return command string;
            return $command;
        }
        
    }
    ?>