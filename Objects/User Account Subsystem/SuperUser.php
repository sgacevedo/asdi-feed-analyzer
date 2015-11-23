<?php
    class SuperUser extends User
    {
        //constuctor
        public function __construct($email){
            $this->email = $email;
        }
        
        public function approveUser($userId, $approval){
            
            //create instance of Database Manager object
            $dbMan = new DatabaseManager();
            
            //establish connection
            //if returns false, connection failed
            if(!$dbMan->establishConnection()){
                //database connection error
                return false;
            }
            
            //if approval is true - change user account status to active
            if($approval){
                $request = new Request('UPDATE status', 'se_Users');
                $request->addParameter('user_id', $userId);
                $request->addParameter('status', '"ACTIVE"');
            }
            //otherwise, if approval is false - delete user account
            else if(!$approval){
                $request = new Request('DELETE', 'se_Users');
                $request->addParameter('user_id', $userId);
            }
            
            //transform the command to sql statement
            $request->transformCommand();
            
            //execute command
            $results = $dbMan->executeQuery($request);
            
            //if results is not null, command was successfully executed.
            if($results != null){
                //successfully approved
                return true;
            }
            
            //command was not successfully executed.
            return false;
        }
    }
?>