<html>
<head>
    <?php
        require_once 'UI/styleIncludes.php' ?>
	<title>Manage User Accounts</title>
</head>
<body>

    <?php
		require_once 'requires.php';
        require_once 'UI/navBar.php'; ?>

	<div class="contents">
		<?php
			
            //appove User button has been selected
			if(isset($_POST['approveUser'])){
                $userId = $_POST['approveUser'];
                $approved = $user->approveUser($userId, true);
			}
            
            //deny user button has been selected
			else if(isset($_POST['denyUser'])){
                $userId = $_POST['denyUser'];
                $approved = $user->approveUser($userId, false);
			}
			
            /*if the type of uer logged in is 'super user' -
             * display all accounts in the database that are pending approval */
			if($_SESSION['user']->type == 'SUPER_USER'){
				
				$dbMan = new DatabaseManager();
				
				if(!$dbMan->establishConnection()){
					//database connection error
					return;
				}
				
				$request = new Request('SELECT *', 'se_Users');
				$request->addParameter('status', 'PENDING_APPROVAL');
				$request->transformCommand();
				
				$results = $dbMan->executeQuery($request);
				
				if($results == null){
					//request failed
				}
				
				$rows = $results->num_rows;?>
		<h1>Pending Accounts</h1>
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Name</th>
					<th>E-mail</th>
					<th>Type</th>
					<th>Status</th>
					<th>Approve User?</th>
					<th>Deny User?</th>
				</tr>
			</thead>
			<tbody>
			<?php
				for ($i = 0 ; $i < $rows ; ++$i){
					$results->data_seek($i);
					$row = $results->fetch_array(MYSQLI_NUM);
					
					$user_id = $row[0];
					$name = $row[1] . ' ' . $row[2];
					$email = $row[3];
					$type = $row[5];
					$status = $row[6];
			?>
				<tr>
					<td><?php echo $name; ?></td>
					<td><?php echo $email; ?></td>
					<td><?php echo $type; ?></td>
					<td><?php echo $status ?></td>
					<td><?php
							echo <<<_END
						<form action="manageAccounts.php" method="POST">
							<button type="submit" class="btn btn-success">Approve</button>
							<input type="hidden" name="approveUser" value='$user_id'>
						</form>
_END
?>
					</td>
					<td><?php
							echo <<<_END
						<form action="manageAccounts.php" method="POST"?>
							<button type="submit" class="btn btn-danger">Deny</button>
							<input type="hidden" name="denyUser" value='$user_id'>
						</form>
_END
?>
					</td>
				</tr>
				<?php }}?>
			</tbody>
		</table>
	</div>
</body>
</html>