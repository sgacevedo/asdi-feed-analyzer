<html>
<head>
    <?php
				require_once 'UI/styleIncludes.php'?>
	<title>Request Restriction</title>
</head>
<body>
    <?php		require_once 'requires.php';
				require_once 'UI/navBar.php'; ?>

	<div class="contents">
	<h1>Request a User Restriction</h1>
<?php 
	  if ($_POST){
	  	$dbMan = new DatabaseManager ();
	  	if (! $dbMan->establishConnection ()) {
	  		// database connection error
	  		return;
	  	}

	  	if(isset($_POST['AirRestriction'])){ 	
	  		
	  	  //insert into Airline Restrictions
	  	  $command="INSERT INTO se_Airline_Restrictions (restriction_id, user_id, airline_name, status) "
			." VALUES ('IIII,'UUUU','NNNN', 'PENDING_APPROVAL');";
		  $command = str_replace('IIII',$_POST['restriction_id'],$command);
		  $command = str_replace('UUUU',$_POST['user_id'],$command);
		  $command = str_replace('NNNN',$_POST['airline_name'],$command);

		  echo "COMMAND:<br>" . $command . "<br><br>";
		  //$results = $dbMan->executeQuery($request);
		  //need to execute request
		  $result = mysql_query($command, $dbMan);
		  
		  if ($result){
			echo "SUCCESS!";
		  }else{
			echo "FAILED, error: " . mysql_error();
		  }
	  	}
	  	else if(isset ($_POST['RegRestriction'])){
		  //insert into Region Restrictions
		  $command2="INSERT INTO se_Region_Restrictions (restriction_id, user_id, region, status) "
		  		." VALUES ('AAAA','BBBB','CCCC', 'PENDING_APPROVAL');";
		  $command2 = str_replace('AAAA',$_POST['restriction_id'],$command);
		  $command2 = str_replace('BBBB',$_POST['user_id'],$command);
		  $command2 = str_replace('CCCC',$_POST['region'],$command);
		  
		  echo "COMMAND:<br>" . $command2 . "<br><br>";
		  $result2 = mysql_query($command2, $dbMan);
		  
		  if ($result2){
		  	echo "SUCCESS!";
		  }else{
		  	echo "FAILED, error: " . mysql_error();
		  }
	  	}
		  mysql_close($dbMan);
		}
	?>
	  <form action='addRestriction.php' method='POST'>
	  	<br>Restriction ID:
	  	<br><input type='text' name='restriction_id' placeholder= '1234'/>
	    <br>User ID:
	    <br><input type='text' name='user_id' placeholder='1234' />
		<br>Airline Name:
		<br><input type='text' name='airline_name' placeholder='Airline Name'/>
	  	<br><br><input type='submit' name= "AirRestriction" value= 'Request Airline Restriction'/>
	  </form>
	  
	   <form action='addRestriction.php' method='POST'>
	  	<br>Restriction ID:
	  	<br><input type='text' name='restriction_id' placeholder= '1234'/>
	    <br>User ID:
	    <br><input type='text' name='user_id' placeholder='1234' />
		<br>Region:
		<br><input type='text' name='airspace_id' placeholder='North'/>
	  	<br><br><input type='submit' name='RegRestriction' value ='Request Region Restriction'/>
	  </form>
	
	</div>
</body>
</html>