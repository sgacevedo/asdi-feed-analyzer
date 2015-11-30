<!-- Manage User Restrictions- Administrator
Requests to add a restriction on a user. 
Requests to remove a restriction on a user.
-->
<html>
<head>
    <?php require_once 'UI/styleIncludes.php'?>
	<title>Manage User Restrictions</title>
</head>
<body>
<?php
require_once 'requires.php';
require_once 'UI/navBar.php';
?>

<div class="contents">
<?php

//delete a restriction
if(isset($_POST['AdeleteUID']) &&isset($_POST['AdeleteRID'])){
	$dbMan = new DatabaseManager();
	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}
	$request = new Request('DELETE', 'se_Airline_Restrictions');
	$request->addParameter('user_id', $_POST['deleteUID']);
	$request->addParameter('restriction_id', $_POST['deleteRID']);
	$request->transformCommand();
	
	$results = $dbMan->executeQuery($request);
	
	if($results != null){
		//successfully removed			
	}
}
//see all pending requests
$dbMan = new DatabaseManager();

if(!$dbMan->establishConnection()){
	//database connection error
	return;
}

$request = new Request ( 'SELECT *', 'se_Airline_Restrictions' );
$request->addParameter ( 'status', 'PENDING_APPROVAL' );
$request->transformCommand ();

$results = $dbMan->executeQuery ( $request );

if ($results == null) {
	// request failed
}

$rows = $results->num_rows;
?>	
		<h1>Pending Restrictions</h1>
		<h3>Airline Restrictions</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Restriction ID</th>
					<th>User ID</th>
					<th>Airline Name</th>
					<th>Status</th>
					<th>Delete Restriction</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($rows == 0) {
				?>
				<tr>
					<td class="noResults" colspan="5">No results</td>
				</tr>
			<?php
			}
			for($i = 0; $i < $rows; ++ $i) {
				$results->data_seek ( $i );
				$row = $results->fetch_array ( MYSQLI_NUM );
				
				$restriction_id = $row [0];
				$userID = $row [1];
				$airline_name = $row [2];
				$status= $row[3]
				?>
				<tr>
					<td><?php echo $restriction_id; ?></td>
					<td><?php echo $user_id; ?></td>
					<td><?php echo $airline_name; ?></td>
					<td><?php echo $status; ?></td>
					<td><?php
				echo '<form action="manageUserRestrictions.php" method="POST">
							<button type="submit" class="btn btn-success">Request to Delete</button>
							<input type="hidden" name="AdeleteUID" value="user_id">
							<input type="hidden" name="AdeleteRID" value="restriction_id" >
						</form>';
			}
			
			?>
					</td>
				</tr>
			</tbody>
		</table>
<!-- 	*************************************************************************** 
Region Restrictions-->
		<?php

//delete a restriction
if(isset($_POST['RdeleteUID']) &&isset($_POST['RdeleteRID'])){
	$dbMan = new DatabaseManager();
	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}
	$request = new Request('DELETE', 'se_Region_Restrictions');
	$request->addParameter('user_id', $_POST['deleteUID']);
	$request->addParameter('restriction_id', $_POST['deleteRID']);
	$request->transformCommand();
	
	$results = $dbMan->executeQuery($request);
	
	if($results != null){
		//successfully removed			
	}
}
//see all pending requests
$dbMan = new DatabaseManager();

if(!$dbMan->establishConnection()){
	//database connection error
	return;
}

$request2 = new Request ( 'SELECT *', 'se_Region_Restrictions' );
$request2->addParameter ( 'status', 'PENDING_APPROVAL' );
$request2->transformCommand ();

$results2 = $dbMan->executeQuery ( $request2 );

if ($results2 == null) {
	// request failed
}

$rows2 = $results2->num_rows;
?>	
		<h3>Region Restrictions</h3>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Restriction ID</th>
					<th>User ID</th>
					<th>Region Name</th>
					<th>Status</th>
					<th>Delete Restriction</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if ($rows2 == 0) {
				?>
				<tr>
					<td class="noResults" colspan="5">No results</td>
				</tr>
			<?php
			}
			for($i = 0; $i < $rows; ++ $i) {
				$results2->data_seek ( $i );
				$row = $results2->fetch_array ( MYSQLI_NUM );
				
				$restriction_id = $row [0];
				$user_id = $row [1];
				$region=$row[2];
				$status = $row [3];
				?>
				<tr>
					<td><?php echo $restriction_id; ?></td>
					<td><?php echo $user_id; ?></td>
					<td><?php echo $region; ?></td>
					<td><?php echo $status; ?></td>
					<td><?php
				echo '<form action="manageUserRestrictions.php" method="POST">
							<button type="submit" class="btn btn-success">Request to Delete</button>
							<input type="hidden" name="RdeleteUID" value="user_id">
							<input type="hidden" name="RdeleteRID" value="restriction_id" >
						</form>';
			}
			
			?>
					</td>
				</tr>
			</tbody>
		</table>
		
		
<!-- 	************************************************************************** -->
		<!-- Add a new restriction -->
		<form action="addRestriction.php" method="POST">
			<button type="submit" class="btn btn-success">Request a new
				Restriction</button>
		</form>
	</div>
</body>
</html>