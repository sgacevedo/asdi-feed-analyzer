<?php
class User
{
	public $firstName;
	public $lastName;
	public $email;
	public $password;
	public $hashedPassword;
    public $type;
    public $id;
	
	//constuctor
	public function __construct($email){
		$this->email = $email;
	}	
}
?>