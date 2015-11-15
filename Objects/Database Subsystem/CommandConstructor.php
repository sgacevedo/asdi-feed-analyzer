<?php
class CommandConstructor
{
	public $connection;
	
	//constuctor
	public function __construct(){ }
	
	public function transformCommand($request){
		$command = '';
		$numParameters = count($request->fields);
		
		//INSERT sql command
		if($request->type == 'INSERT'){
			$i = 1;
			$command = 'INSERT INTO ' . $request->dbTable . ' (';
			$values = 'VALUES (';
			
			foreach($request->fields as $field => $value) {
				$command = $command . $field;
				$values = $values . '"' . $value . '"';
				
				if($i++ == $numParameters) {
					$command = $command . ') ';
					$values = $values . ');';
				}
				else{
					$command = $command . ', ';
					$values = $values . ', ';
				}
			}
			$command = $command . ''. $values;
		}
		
		else if($request->type == 'SELECT *'){
			$i = 1;
			$command = 'SELECT * FROM ' . $request->dbTable . ' WHERE ';
			
			foreach($request->fields as $field => $value) {
				$command = $command . $field . ' = "' . $value . '" ';
				
				if($i++ != $numParameters) {
					$command = $command . 'AND ';
				}
			}
		}
		
		$request->command = $command;
	}

}
?>