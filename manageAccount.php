<html>
    <head>
        <?php
            require_once 'UI/styleIncludes.php' ?>
        <title>My Account</title>
        
        <script type="text/javascript">
			$(function(){
				$('#editButton').click(function(){
					$('#viewOnly').hide();
					$('#editable').show();
					$('#updateSuccess').hide();
				});
				$('#changePasswordButton').click(function(){
					$('#viewOnly').hide();
					$('#changePassword').show();
					$('#updateSuccess').hide();
				});	
				$('button.btn.cancel').click(function(){
					$(this).parents('form').parent().hide();
					$('#viewOnly').show();
				});	
			});
        </script>
    </head>
    <body>
        <?php
            require_once 'requires.php';
            require_once 'UI/navBar.php'; ?>
            
            <?php 
				if(isset($_POST['MY_ACCOUNT_FIRSTNAME']) &&
					isset($_POST['MY_ACCOUNT_LASTNAME'])  &&
					isset($_POST['MY_ACCOUNT_EMAIL'])){
					updateUserInformation($user->id);
				}
				
				else if(isset($_POST['MY_ACCOUNT_PASSWORD']) &&
						isset($_POST['MY_ACCOUNT_VERIFY_PASSWORD'])){
					updateUserPassword($user->id);
				}
            ?>
            
            <div class="contents">
            	<div id="updateSuccess" class="alert alert-success">
					<strong><i class="fa fa-check"></i>Success</strong> Your information has been successfully updated.
				</div>
				<div id="unmatchedPasswords" class="alert alert-danger">
					<strong><i class="fa fa-times"></i>Unsuccessful</strong> Passwords do not match.
				</div>
            	<h1>My Account</h1>
            	<?php $userInformation = getCurrentUserInformation($user); ?>
            	<div id="viewOnly">
	            	<table id="currentUserInformation">
	            		<tr>
	            			<th>First Name: </th>
	            			<td><?php echo $userInformation[1]?>
	            		</tr>
	            		<tr>
	            			<th>Last Name: </th>
	            			<td><?php echo $userInformation[2]?>
	            		</tr>
	            		<tr>
	            			<th>Email: </th>
	            			<td><?php echo $userInformation[3]?>
	            		</tr>
	            		<tr>
	            			<td colspan="2">
            					<button id="editButton" class="btn btn-md btn-primary btn-block" type="submit"><i class="fa fa-pencil" ></i>Edit</button>
	            			</td>
	            		</tr>
	            		<tr>
	            			<td colspan="2">
            					<button id="changePasswordButton" class="btn btn-md btn-warning btn-block" type="submit"><i class="fa fa-lock" ></i>Change Password</button>
	            			</td>
	            		</tr>
	            	</table>
            	</div>
            	<div id="editable">
            		<form method="post" action="manageAccount.php">
            			<table style="width: 500px">
            				<tr>
            					<th>First Name: </th>
            					<td><input type="text" name="MY_ACCOUNT_FIRSTNAME" class="form-control" value="<?php echo $userInformation[1]?>" required/></td>
            				</tr>
            				<tr>
            					<th>Last Name: </th>
            					<td><input type="text" name="MY_ACCOUNT_LASTNAME" class="form-control" value="<?php echo $userInformation[2]?>" required/></td>
            				</tr>
            				<tr>
            					<th>Email: </th>
            					<td><input type="text" name="MY_ACCOUNT_EMAIL" class="form-control" value="<?php echo $userInformation[3]?>" required/></td>
            				</tr>
            				<tr>
            					<td colspan="2">
            						<button class="btn btn-md btn-success btn-block" type="submit">Submit</button>
            					</td>
            				</tr>
            				<tr>
		            			<td colspan="2">
	            					<button class="btn btn-md btn-danger btn-block cancel" type="button"><i class="fa fa-times" ></i>Cancel</button>
		            			</td>
		            		</tr>
            			</table>
            		</form>
            	</div>
            	<div id="changePassword">
            		<form method="post" action="manageAccount.php">
            			<table style="width: 500px">
            				<tr>
            					<th>Password: </th>
            					<td><input type="password" name="MY_ACCOUNT_PASSWORD" class="form-control" /></td>
            				</tr>
            				<tr>
            					<th>Verify Password: </th>
            					<td><input type="password" name="MY_ACCOUNT_VERIFY_PASSWORD" class="form-control" /></td>
            				</tr>
            				<tr>
            					<td colspan="2">
            						<button class="btn btn-md btn-success btn-block" type="submit">Submit</button>
            					</td>
            				</tr>
            				<tr>
		            			<td colspan="2">
	            					<button class="btn btn-md btn-danger btn-block cancel" type="button"><i class="fa fa-times" ></i>Cancel</button>
		            			</td>
		            		</tr>
            			</table>
            		</form>
            	</div>
            </div>
	</body>
</html>

<?php 
function getCurrentUserInformation($user){
		$dbMan = new DatabaseManager();
	            	
    	if(!$dbMan->establishConnection()){
    		//database connection error
     		return;
       	}
	            	
	 	$request = new Request ('SELECT *', 'se_Users');
	 	$request->addParameter('user_id', $user->id);
	 	$request->transformCommand();
	            	
		$userInformation = $dbMan->executeQuery($request);
	            	
	  	//server error
		if($userInformation == null){
			//request was unsuccessful
		}
	    else if($userInformation->num_rows){
	            		
			/* Get number of rows returned */
			$rows = $userInformation->num_rows;
						
			$userInformation->data_seek(0);
			$row = $userInformation->fetch_array(MYSQLI_NUM);
			return $row;
		}
}

function updateUserInformation($userId){
	/* Create new instance of database manager */
	$dbMan = new DatabaseManager();
	
	
	/* Establish connection with server */
	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}
	
	/* Create new request to update user information */
	$request = new Request ('UPDATE', 'se_Users');
	
	/* pass in user information as parameters*/
	$request->addParameter('user_id', $userId);
	$request->addParameter('firstName', $_POST['MY_ACCOUNT_FIRSTNAME']);
	$request->addParameter('lastName', $_POST['MY_ACCOUNT_LASTNAME']);
	$request->addParameter('email', $_POST['MY_ACCOUNT_EMAIL']);
	
	$request->transformCommand();
	
	$userInformation = $dbMan->executeQuery($request);
	
	//server error
	if($userInformation == null){
		//request was unsuccessful
	}
	else{
		$_SESSION['user']->firstName = $_POST['MY_ACCOUNT_FIRSTNAME'];
		$_SESSION['user']->lastName = $_POST['MY_ACCOUNT_LASTNAME'];
		$_SESSION['user']->email = $_POST['MY_ACCOUNT_EMAIL'];
		accountUpdateSuccess();
	}
}

function updateUserPassword($userId){

	/* Create new instance of database manager */
	$dbMan = new DatabaseManager();
	
	
	/* Establish connection with server */
	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}
	
	/* Create new request to update user password */
	$request = new Request ('UPDATE', 'se_Users');
	$request->addParameter('user_id', $userId);

	/* If the new passwords entered by the user match */
	if($_POST['MY_ACCOUNT_PASSWORD'] == $_POST['MY_ACCOUNT_VERIFY_PASSWORD']){
		$email = $_SESSION['user']->email;
		$password = $_POST['MY_ACCOUNT_PASSWORD'];
		$hashedPassword = hash('ripemd128', "g!cT$email$password");
		$request->addParameter('password', $hashedPassword);
	}
	/* Otherwise, Passwords do not match */
	else{
		unmatchedPasswords();
		return;
	}
	
	/* Transform request into SQL command */
	$request->transformCommand();
	
	/* Results returned from server */
	$results = $dbMan->executeQuery($request);
	
	//server error
	if($results == null){
		//request was unsuccessful
	}
	else{
		accountUpdateSuccess();
	}
}

function accountUpdateSuccess(){
	echo <<<_END
	<script type="text/javascript">
		$(document).ready(function(){
			$('#updateSuccess').show();
		});
	</script>
_END;
}

function unmatchedPasswords(){
	echo <<<_END
	<script type="text/javascript">
		$(document).ready(function(){
			$('#viewOnly').hide();
			$('#changePassword').show();
			$('#unmatchedPasswords').show();
		});
	</script>
_END;
}
?>
