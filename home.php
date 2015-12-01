<html>
<head>
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <?php
        require_once 'UI/styleIncludes.php'; ?>
	<title>Login</title>
</head>
<body>
	<?php
        require_once 'requires.php';
        require_once 'UI/navBar.php'; ?>
	<form id="loginForm" class="form-signin" action="home.php" method="post">
		<h2 class="form-signin-heading">Log in</h2>
		<div id="alertLoginIncorrectCred" class="alert alert-danger">
		  <strong><i class="fa fa-warning"></i>Unsuccessful Login</strong> The email or password you entered is incorrect
		</div>
		<div id="alertPending" class="alert alert-warning">
		  <strong><i class="fa fa-warning"></i>Pending</strong> Your account is currently Pending Approval
		</div>
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="AUTH_EMAIL" required autofocus>
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" class="form-control" placeholder="Password" name="AUTH_PASSWORD" required>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
	</form>
</body>
</html>

<?php
if(isset($_SESSION['user']) && !isset($_POST['LOGOUT'])){
	displayWelcomePanel($user);
}


/* If the a session user variable is not set and AUTH_USERNAME and AUTH_PASSWORD have been posted sign in the user */
if (!isset($_SESSION['user']) && isset($_POST['AUTH_EMAIL']) && isset($_POST['AUTH_PASSWORD']))
{
	
	$dbMan = new DatabaseManager();
	
	if(!$dbMan->establishConnection()){
		//database connection error
		return;
	}
	
	//create new user instance
	$user = new User($_POST['AUTH_EMAIL']);
    $user->password = $_POST['AUTH_PASSWORD'];
	
	//check login credentials
	$email_temp = mysql_entities_fix_string($dbMan->connection, $_POST['AUTH_EMAIL']);
			
	$request = new Request('SELECT *', 'se_Users');
	$request->addParameter('email', $email_temp);
	$request->transformCommand();
		
	$loginResults = $dbMan->executeQuery($request);
	
	//server error
	if($loginResults == null){
		//request was unsuccessful
	}
	
	else if($loginResults -> num_rows){
		//user exsists
		$row = $loginResults -> fetch_array(MYSQLI_NUM);

		$loginResults -> close();
		
		$user->hashedPassword = hash('ripemd128', "g!cT$user->email$user->password");
		
		//password correct
		if($user->hashedPassword == $row[4])
		{
			//sucessful login
			if($row[6] == 'ACTIVE'){
				$user->id = $row[0];
				$user->firstName = $row[1];
				$user->lastName = $row[2];
				$user->email = $row[3];
                $user->type = $row[5];
                
				$_SESSION['user'] = $user;
				
				displayWelcomePanel($user);
				showNavBarItems($user);
				
			}
			else{
				echo <<<_END
				<script type="text/javascript">
					$(document).ready(function(){
	          			$('#loginForm').show();
						$('#alertPending').show();
	          			$('#requestAccount').show();
					});
				</script>
_END;
			}
		}
		else{
			//incorrect password
			echo <<<_END
			<script type="text/javascript">
				$(document).ready(function(){
          			$('#loginForm').show();
					$('#alertLoginIncorrectCred').show();
          			$('#requestAccount').show();	
				});
			</script>
_END;
		}
	}
	else{
		//user does not exsist
		echo <<<_END
		<script type="text/javascript">
			$(document).ready(function(){
              	$('#loginForm').show();
				$('#alertLoginIncorrectCred').show();
              	$('#requestAccount').show();	
			});
		</script>
_END;
	}
}

//user clicked logout button
else if(isset($_POST['LOGOUT'])){
    destroy_session_and_data();
    echo <<<_END
    <script type="text/javascript">
    $(document).ready(function(){
        $('#loginForm').show();
        $('#requestAccount').show();
        $('#signOut').hide();
        $('#userAccounts').hide();
		$('#myAccount').hide();
        $('#query').hide();
    });
    </script>
_END;
}

//otherwise - not logged in
else if(!isset($_SESSION['user']))
{
	echo <<<_END
		<script type="text/javascript">
			$(document).ready(function(){
				$('#loginForm').show();
				$('#requestAccount').show();
			});
		</script>
_END;
}

function mysql_entities_fix_string($connection, $string)
{
	return htmlentities(mysql_fix_string($connection, $string));
}
function mysql_fix_string($connection, $string)
{
	if(get_magic_quotes_gpc()) $string = stripslashes($string);
	return $connection -> real_escape_string($string);
}
function destroy_session_and_data()
{
    $_SESSION = array();
    session_destroy();
}

function displayWelcomePanel($user){
	if($user->type == 'SUPER_USER'){ $type = 'Super User'; $sidePadding = '150px';}
	else if($user->type == 'ADMINISTRATOR'){ $type = 'Administrator'; $sidePadding = '250px';}
	else{ $type = 'General User'; $sidePadding = '300px';}
	
	$width = 50;
	
echo <<<_END
			<div class="contents home">
				<h1>Welcome, $user->firstName $user->lastName </h1>
				<h4>$type</h4>
				<div class="table" id="actionTiles" style="padding: 0 $sidePadding">
					<div class="table-row">
_END;
		
	if($user->type == 'SUPER_USER'){
		$width = 25;
		$pendingAccounts = getNumberOfPendingAccounts();
		echo <<<_END
						<div class="table-cell" style="width: $width%">
							<a href="pendingAccounts.php">
								<div class="tile">
									<div class="table-row">
			    						<div class="top table-cell">
			    							<i class="fa fa-tasks"></i>
			    						</div>
		    						</div>
		    						<div class="table-row">
			    						<div class="bottom table-cell">
			    							<div>Pending Accounts</div>
_END;
		if($pendingAccounts > 0){
			echo '<div><span class="badge">' . $pendingAccounts . ' </span></div>';
		}
		echo <<<_END
			    						</div>
									</div>
		    					</div>
	    					</a>
						</div>
						<div class="table-cell" style="width: $width%">
    						<a href="manageUserRestrictions.php">
								<div class="tile">
									<div class="table-row">
			    						<div class="top table-cell">
			    							<i class="fa fa-warning"></i>
			    						</div>
		    						</div>
		    						<div class="table-row">
			    						<div class="bottom table-cell">
			    							<div>Pending Restrictions</div>
			    						</div>
									</div>
		    					</div>
    						</a>
						</div>
_END;
	}
	else if($user->type == 'ADMINISTRATOR'){
		$width = 33;
		
		echo <<<_END
						<div class="table-cell" style="width: $width%">
							<a href="manageUserRestrictions.php">
								<div class="tile">
									<div class="table-row">
			    						<div class="top table-cell">
			    							<i class="fa fa-ban"></i>
			    						</div>
		    						</div>
		    						<div class="table-row">
			    						<div class="bottom table-cell">
			    							<div>Manage Restrictions</div>
			    						</div>
									</div>
		    					</div>
							</a>
						</div>
_END;
	}
	
	echo <<<_END
						<div class="table-cell" style="width: $width%">
							<a href="manageAccount.php">
								<div class="tile">
									<div class="table-row">
			    						<div class="top table-cell">
			    							<i class="fa fa-user"></i>
			    						</div>
		    						</div>
		    						<div class="table-row">
			    						<div class="bottom table-cell">
			    							<div>Manage Account</div>
			    						</div>
									</div>
		    					</div>
	    					</a>
						</div>
						<div class="table-cell" style="width: $width%">
			    			<a href="query.php">
								<div class="tile">
									<div class="table-row">
			    						<div class="top table-cell">
			    							<i class="fa fa-search"></i>
			    						</div>
		    						</div>
		    						<div class="table-row">
			    						<div class="bottom table-cell">
			    							<div>Query</div>
			    						</div>
									</div>
		    					</div>
			    			</a>
						</div>
					</div>
				</div>
			</div>
_END;
}

function getNumberOfPendingAccounts(){
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
	
	return $rows = $results->num_rows;
}
?>
