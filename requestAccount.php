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
	<title>Request Account</title>
</head>
<body>
	<nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a class="navbar-brand" href="home.php"><i class="fa fa-plane"></i>ASDI Feed Analyzer</a>
        </div>
        <ul class="nav navbar-nav">
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li id="requestAccount"><button class="btn btn-info" type="submit"><a href="requestAccount.php">Request Account</a></button></li>
            <li id="signOut"><a href="#"><i class="fa fa-sign-out"></i>Log Out</a></li>
          </ul>
      </div>
    </nav>
	<form id="requestForm" class="form-requestAccount" action="requestAccount.php" method="post">
		<h2 class="form-signin-heading">Request Account</h2>
		<div id="alertRequestSuccess" class="alert alert-success">
		  <strong><i class="fa fa-warning"></i>Success</strong> Your request has been submitted.
		</div>
		<label for="inputFirstName" class="sr-only">First Name</label>
		<input type="text" id="inputFirstName" class="form-control" placeholder="First Name" name="REQ_FIRSTNAME" required autofocus>
		<label for="inputLastName" class="sr-only">Last Name</label>
		<input type="text" id="inputLastName" class="form-control" placeholder="Last Name" name="REQ_LASTNAME" required >
		<label for="inputEmail" class="sr-only">Email address</label>
		<input type="email" id="inputEmail" class="form-control" placeholder="Email address" name="REQ_EMAIL" required >
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" id="inputPassword" class="form-control" placeholder="Password" name="REQ_PASSWORD" required>
		<label for="inputVerifyPassword" class="sr-only">Verify Password</label>
		<input type="password" id="inputVerifyPassword" class="form-control" placeholder="Verify Password" name="REQ_VERIFY_PASSWORD" required>
		<button class="btn btn-lg btn-success btn-block" type="submit">Submit</button>
	</form>
</body>
</html>

<?php
	require_once 'requires.php';

	if(isset($_POST['REQ_FIRSTNAME']) && isset($_POST['REQ_LASTNAME']) && isset($_POST['REQ_EMAIL']) && isset($_POST['REQ_PASSWORD']) && isset($_POST['REQ_VERIFY_PASSWORD'])){
		$dbMan = new DatabaseManager();

		if(!$dbMan->establishConnection()){
			//database connection error
			return;
		}

		$firstName = $_POST['REQ_FIRSTNAME'];
		$lastName = $_POST['REQ_LASTNAME'];
		$email = $_POST['REQ_EMAIL'];
		$password = $_POST['REQ_PASSWORD'];
		$verifyPassword = $_POST['REQ_VERIFY_PASSWORD'];

		//if any field is blank
		if($firstName == '' || $lastName == '' || $email == '' || $password == ''|| $verifyPassword == ''){
			echo 'one or more field is blank';
			return;
		}
		if($password != $verifyPassword){
			echo 'passwords do not match';
			return;
		}

		$user = new User($email, $password);
		$user->firstName = $firstName;
		$user->lastName = $lastName;
		$user->password = $password;
		$user->hashedPassword = hash('ripemd128', "g!cT$user->email$user->password");
		$user->type = 'GENERAL_USER';
		$user->status = 'PENDING_APPROVAL';

		$request = new Request('INSERT', 'se_Users');

		$request->addParameter('firstName', $user->firstName);
		$request->addParameter('lastName', $user->lastName);
		$request->addParameter('email', $user->email);
		$request->addParameter('password', $user->hashedPassword);
		$request->addParameter('type', $user->type);
		$request->addParameter('status', $user->status);

		$request->transformCommand();


		$result = $dbMan->executeQuery($request);

		if($result == null){
			//request was unsuccessful
		}
		else{
			//request was successful
			echo <<<_END
			<script type="text/javascript">
				$(document).ready(function(){
					$('#alertRequestSuccess').show();
				});
			</script>
_END;
		}
	}
?>
