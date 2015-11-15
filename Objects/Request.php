<?php
class Request
{
	public $type;
	public $dbTable;
	public $fields = array();
	public $command;
	
	//constuctor
	public function __construct($type, $table){ 
		$this->type = $type;
		$this->dbTable = $table;
	}
	
	public function addParameter($field, $value){
		//add parameter to associate array $fields 
		$this->fields[$field] = $value;
	}
	
	public function transformCommand(){
		$cc = new CommandConstructor();
		$cc->transformCommand($this);
	}

}
?>