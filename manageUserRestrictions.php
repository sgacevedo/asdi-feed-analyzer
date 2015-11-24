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
if ($_SESSION ['user']->type == 'ADMINISTRATOR') {
	
	$dbMan = new DatabaseManager();
	
	if (! $dbMan->establishConnection ()) {
		// database connection error
		return;
	}

	$request = new Request('selectActiveUsers', 'se_Users');
	$request->addParameter('status', 'ACTIVE');
	$request->transformCommand();
	
	$results = $dbMan->executeQuery($request);
	
	//server error
	if($results == null){
		//request was unsuccessful
		return;
	}
	
		$rows = $results->num_rows;
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
			if($rows == 0){?>
				<tr><td class="noResults" colspan="5">No results</td></tr>
			<?php
			}
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
				<?php }}?>
			</tbody>
		</table>
		<form action="addRestriction.php" method="POST">
			<button type="submit" class="btn btn-success">Request a new Restriction</button>
		</form>
	</div>
</body>
</html>