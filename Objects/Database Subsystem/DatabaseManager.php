<?php
class DatabaseManager
{
	public $connection;
	public $hn = 'earth.cs.utep.edu';
	public $db = 'cs_aegomez2';
	public $un = 'cs_aegomez2';
	public $pw = 'utep$234';
	
	//constuctor
	public function __construct(){ }

	public function establishConnection(){
		$this->connection = new mysqli($this->hn, $this->un, $this->pw, $this->db);
		
		//if an connection error occurs return false
		if($this->connection -> connect_error){
			die($this->connection -> connect_error); 
			return false;
		}
		
		//connection successful - return true
		return true;
	}
	
	public function executeQuery($request){	
		//execute query;
		$result = $this->connection->query($request->command);
		
		//query unsucessful - return false
		if(!$result){
			die($this->connection -> error);
			return null;
		}
		
		//query successful - return results
		return $result;
	}
}
?>