<?php
class User
{
	public $firstName;
	public $lastName;
	public $email;
	public $password;
	public $hashedPassword;
	
	//constuctor
	public function __construct($email, $password){
		$this->email = $email;
		$this->password = $password;
	}	
}
?>