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
$dbMan = new DatabaseManager ();
if (! $dbMan->establishConnection ()) {
	// database connection error
	return;
}
//if a request to delete a restriction is set
if(isset($_POST['delete'])){
	$dbMan = new DatabaseManager();

	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}

	$request = new Request('UPDATE status', 'se_Airline_Restrictions');
	$request->addParameter('user_id', $_POST['restriction_id']);
	$request->addParameter('status', '"PENDING_DELETE"');
	$request->transformCommand();

	$results = $dbMan->executeQuery($request);

	if($results != null){
	}

}

//View all restrictions
//if($_SESSION['user']->type == 'ADMINISTRATOR'){

	$dbMan = new DatabaseManager();

	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}

	$request = new Request('SELECT *', 'se_Airline_Restrictions');
	$request->addParameter('status', 'APPROVED');
	$request->transformCommand();

	$results = $dbMan->executeQuery($request);

	if($results == null){
		//request failed
	}

$rows = $results->num_rows;

?>	
		<h1>Request Restrictions</h1>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Restriction ID</th>
					<th>User ID </th>
					<th>Airline Name </th>
					<th>Delete Restriction</th>
				</tr>
			</thead>
			<tbody>
			<?php
			for($i = 0; $i < $rows; ++ $i) {
				$results->data_seek ( $i );
				$row = $results->fetch_array ( MYSQLI_NUM );
				
				$user_id = $row [0];
				$name = $row [1] . ' ' . $row [2];
				$restrictions = $row [3];
				?>
				<tr>
					<td><?php echo $restriction_id; ?></td>
					<td><?php echo $user_id; ?></td>
					<td><?php echo $airline_name; ?></td>
					<td><?php
				echo <<<_END
						<form action="manageUserRestrictions.php" method="POST">
							<button type="submit" class="btn btn-success">Request to Delete</button>
							<input type="hidden" name="delete" value='restriction_id'>
						</form>
_END
?>
					</td>
				</tr>
				<?php }//}?>
			</tbody>
		</table>
		<!-- Add a new restriction -->
		<form action="addRestriction.php" method="POST">
			<button type="submit" class="btn btn-success">Request a new
				Restriction</button>
		</form>
	</div>
</body>
</html>