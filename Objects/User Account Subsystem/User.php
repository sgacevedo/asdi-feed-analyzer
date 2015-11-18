<?php
class User
{
	public $firstName;
	public $lastName;
	public $email;
	public $password;
	public $hashedPassword;
    public $type;
	
	//constuctor
	public function __construct($email){
		$this->email = $email;
	}	
}
?>