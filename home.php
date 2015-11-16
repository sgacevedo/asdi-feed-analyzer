<!-- Amber's comment -->

<html>
<head>

	<link rel="stylesheet" href="style.css">
	
	<!-- Font Awesome (icons) -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	
	<!-- Latest compiled JavaScript -->
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>	
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Login</title>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="home.php"><i class="fa fa-plane"></i>ASDI Feed Analyzer</a>
        </div>
        <ul class="nav navbar-nav">
            <li id="account" ><a href="#about">Account</a></li>
            <li id="query" class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Query<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Create Report</a></li>
                <li><a href="#">Create Model</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li id="requestAccount"><button class="btn btn-info" type="submit"><a href="requestAccount.php">Request Account</a></button></li>
            <li id="signOut">
            	<form action="home.php" method="post">
            		<input type="hidden" name="LOGOUT" value="yes" />
            		<button class="btn btn-link" type="submit"><i class="fa fa-sign-out"></i>Sign Out</button>
            	</form>
            </li>
          </ul>
      </div>
    </nav>
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
require_once 'requires.php';

$dbMan = new DatabaseManager();
		
if(!$dbMan->establishConnection()){
	//database connection error
	return;
}

session_start();
if(isset($_POST['LOGOUT'])){
	destroy_session_and_data();
	echo <<<_END
		<script type="text/javascript">
			$(document).ready(function(){
				$('#loginForm').show();
				$('#requestAccount').show();
			});
		</script>
_END;
}

//if the AUTH_USERNAME and AUTH_PASSWORD have been posted sign in the user
else if (isset($_POST['AUTH_EMAIL']) && isset($_POST['AUTH_PASSWORD']))
{
	
	//create new user instance
	$user = new User($_POST['AUTH_EMAIL'], $_POST['AUTH_PASSWORD']);
	
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
				echo'success';
				$_SESSION['user'] = $user;
				echo <<<_END
				<script type="text/javascript">
					$(document).ready(function(){
              			$('#account').show();
              			$('#query').show();
						$('#signOut').show();
					});
				</script>
_END;
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

//already logged in
else if (isset($_SESSION['user']))
{
	$email = $_SESSION['user']->email;

	echo 'logged in as: '. $email;
	echo <<<_END
		<script type="text/javascript">
			$(document).ready(function(){
				$('#account').show();
				$('#query').show();
				$('#signOut').show();
			});
		</script>
_END;
}

//otherwise - not logged in
else
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
	setcookie(session_name(), '', time() - 2592000, '/');
	session_destroy();
}

?>
