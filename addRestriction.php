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
	<div id="insertSuccess" class="alert alert-success" style="display: none">
		<strong><i class="fa fa-check"></i>Success</strong> Your request was successfully submitted.
	</div>
	<div id="insertFail" class="alert alert-danger" style="display: none">
		<strong><i class="fa fa-times"></i>Unsuccessful</strong> Your request was not submitted.
	</div>
	<h1>Request a User Restriction</h1>
<?php 
	if(isset($_POST['REGION_USER']) || isset($_POST['AIRLINE_USER'])){
		$result;
		
		if (isset($_POST['REGION_USER']) && isset($_POST['REGION'])){
			$userId = $_POST['REGION_USER'];
			$restriction = $_POST['REGION'];
			$result = $user->addRestrictionRequest($userId, 'region', $restriction);
	  	}
	  	else if (isset($_POST['AIRLINE_USER']) && isset($_POST['AIRLINE'])){
	  		$userId = $_POST['AIRLINE_USER'];
	  		$restriction = $_POST['AIRLINE'];
	  		$result = $user->addRestrictionRequest($userId, 'airline', $restriction);
	  	}
	  	
	  	if($result){
	  		echo '<script type="text/javascript">$(document).ready(function(){success();});</script>';
	  	}
	  	else{
	  		echo '<script type="text/javascript">$(document).ready(function(){fail();});</script>';
	  	}
	} 

		function getUsers(){
			$dbMan = new DatabaseManager();

			if(!$dbMan->establishConnection()){
				//database connection error
				return;
			}
			
			$request = new Request ('SELECT *', 'se_Users');
			$request->transformCommand();
			
			$users = $dbMan->executeQuery($request);
			
			//server error
			if($users == null){
				//request was unsuccessful
			}
			
			else if($users -> num_rows){
			
				/* Get number of rows returned */
				$rows = $users->num_rows;
			
				/* For each row - push the airline name
				 * onto the $airlines array */
				for ($i = 0 ; $i < $rows ; ++$i){
					$users->data_seek($i);
					$row = $users->fetch_array(MYSQLI_NUM);
						
					echo "<option value='" . $row[0] ."'>" . $row[1] . " " . $row[2] . "</option>";
			
				}
			}
		}
		
		function getAirlines(){
			$dbMan = new DatabaseManager();

			if(!$dbMan->establishConnection()){
				//database connection error
				return;
			}
				
			$request = new Request ('SELECT *', 'se_Airlines');
			$request->transformCommand();
				
			$users = $dbMan->executeQuery($request);
				
			//server error
			if($users == null){
				//request was unsuccessful
			}
				
			else if($users -> num_rows){
					
				/* Get number of rows returned */
				$rows = $users->num_rows;
					
				/* For each row - push the airline name
				 * onto the $airlines array */
				for ($i = 0 ; $i < $rows ; ++$i){
					$users->data_seek($i);
					$row = $users->fetch_array(MYSQLI_NUM);
			
					echo "<option>" . $row[0] . "</option>";
						
				}
			}
		}
	?>
		<form>
			<label for="restrictionType">Select Restriction Type:</label>
			<select id="restrictionType" class="form-control" style="width: 400px">
				<option>Region</option>
				<option>Airline</option>
			</select>
		</form>
		
		<form action='addRestriction.php' method='POST' id="airlineRestriction" style="display: none">
		    <label for="user">User: </label>
			<select id="user" class="form-control user" style="width: 400px">
				<?php getUsers();?>
			</select>
			<input type="hidden" name="AIRLINE_USER" value="2"/>
			<label for="airline">Restricted Airline: </label>
			<select id="airline" class="form-control" style="width: 400px" name="AIRLINE">
				<?php getAirlines();?>
			</select>
			<div style="margin-top: 20px">
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
	  	</form>
	  
	  	<form action='addRestriction.php' method='POST' id="regionRestriction" >
		  	<label for="user">User:</label>
			<select id="user" class="form-control user" style="width: 400px">
				<?php getUsers();?>
			</select>
			<input type="hidden" name="REGION_USER" value="2"/>
			<label for="region">Restricted Region: </label>
			<select id="region" class="form-control" style="width: 400px" name="REGION">
				<option>West</option>
				<option>South</option>
				<option>Midwest</option>
				<option>Northeast</option>
			</select>
			<div style="margin-top: 20px">
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
	  	</form>
	</div>
	
	<script type="text/javascript">
		$("#restrictionType").change(function(){
			if($(this).val() == 'Region'){
				$('#airlineRestriction').hide();
				$('#regionRestriction').show();
			}
			else if($(this).val() == 'Airline'){
				$('#airlineRestriction').show();
				$('#regionRestriction').hide();
			}
		});

		$('.user').change(function(){
			user_id = $(this).find("option:selected").prop("value");
			hiddenField = $(this).siblings('input[type="text"]');
			hiddenField.val(user_id);
		});

		function success(){
			$('#insertSuccess').show();
		}
		function fail(){
			$('#insertFail').show();
		}
	</script>
</body>
</html>