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
if ($_SESSION ['user']->type == 'ADMINISTRATOR') {

// Airline_Restrictions
// $request = new Request ( 'SELECT U.user_id, U.firstName, U.lastName, AR.restriction_id, AR.airline_name', 'se_Users AS U JOIN se_Airline_Restrictions as AR' );
// $request->addParameter ( 'status', 'ACTIVE' );
// $request->addParameter ( 'U.user_id', 'AR.user_id' );
// $request->transformCommand ();
$query = "SELECT U.user_id, U.firstName, U.lastName, AR.restriction_id, AR.airline_name FROM se_Users AS U JOIN se_Airline_Restrictions as AR WHERE status='ACTIVE' and  U.user_id";
// $results = $dbMan->executeQuery ( $request );
$results = mysql_query ($query, $dbMan );

$rows = $results->num_rows;
}
?>	
		<h1>Request Restrictions</h1>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>UserID</th>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Restrictions</th>
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
					<td><?php echo $user_id; ?></td>
					<td><?php echo $name; ?></td>
					<td><?php echo $restrictions; ?></td>
					<td><?php
				echo <<<_END
						<form action="manageUserRestrictions.php" method="POST">
							<button type="submit" class="btn btn-success">Delete</button>
							<input type="hidden" name="delete" value='restriction_id'>
						</form>
_END
?>
					</td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<form action="addRestriction.php" method="POST">
			<button type="submit" class="btn btn-success">Request a new
				Restriction</button>
		</form>
	</div>
</body>
</html>