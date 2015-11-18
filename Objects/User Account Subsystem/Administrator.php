<?php
    class Administrator extends User
    {
        //constuctor
        public function __construct($email){
            $this->email = $email;
        }
    }
    ?>