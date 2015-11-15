<?php
    class UserCommandConstructor extends CommandConstructor
    {
        
        //constuctor
        public function __construct(){ }
        
        public function transformCommand($request){
            $command = '';
            $numParameters = count($request->fields);
            
            //INSERT sql command
            if($request->type == 'INSERT'){
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
                $command = 'SELECT * FROM ' . $request->dbTable . ' WHERE ';
                
                //for each item in the array
                foreach($request->fields as $field => $value) {
                    $command = $command . $field . ' = "' . $value . '" ';
                    
                    //if not last element in array - add 'AND '
                    if($i++ != $numParameters) {
                        $command = $command . 'AND ';
                    }
                }
            }
            
            //return command string;
            return $command;
        }
        
    }
    ?>