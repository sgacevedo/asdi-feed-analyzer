<?php
    class SuperUser extends User
    {
        //constuctor
        public function __construct($email){
            $this->email = $email;
        }	
    }
?>