<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="home.php"><i class="fa fa-plane"></i>ASDI Feed Analyzer</a>
        </div>
        <ul class="nav navbar-nav">
            <li id="userAccounts" class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">User Accounts<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li class="pendingAccounts"><a href="pendingAccounts.php">Manage User Accounts</a></li>
                    <li class=""><a href="manageUserRestrictions.php">Manage User Restrictions</a></li>
                </ul>
            </li>
            <li id="myAccount">
            	<a href="manageAccount.php">My Account</a>
            </li>
            <li id="query">
            	<a href="query.php">Query</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li id="requestAccount">
                <button class="btn btn-info" type="submit">
                    <a href="requestAccount.php">Request Account</a>
                </button>
            </li>
            <li id="signOut">
                <form action="home.php" method="post">
                    <input type="hidden" name="LOGOUT" value="yes" />
                    <button class="btn btn-link" type="submit"><i class="fa fa-sign-out"></i>Sign Out</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<?php
    session_start();
	
	//user is already logged in
    if(isset($_SESSION['user'])){
        $user;
        $userEmail = $_SESSION['user']->email;

        if($_SESSION['user']->type == 'SUPER_USER'){
            $user = new SuperUser($userEmail);
            $user->type = 'SUPER_USER';
        }
        else if($_SESSION['user']->type == 'ADMINISTRATOR'){
            $user = new Administrator($userEmail);
            $user->type = 'ADMINISTRATOR';
        }
        else if($_SESSION['user']->type == 'GENERAL_USER'){
            $user = new GeneralUser($userEmail);
            $user->type = 'GENERAL_USER';
        }
        
        $user->id = $_SESSION['user']->id;
        $user->firstName = $_SESSION['user']->firstName;
        $user->lastName = $_SESSION['user']->lastName;
        
        showNavBarItems($user);
        
        $GLOBALS['user'] = $user;
        
    }
    
    function showNavBarItems($user){
		if($user->type == 'SUPER_USER'){
			echo <<<_END
			<script type="text/javascript">
				$(document).ready(function(){
          			$('#userAccounts').show();
					$('#query').show();
          			$('#myAccount').show();
					$('#signOut').show();
				});
			</script>
_END;
		}
		else if($user->type == 'ADMINISTRATOR'){
			echo <<<_END
			<script type="text/javascript">
				$(document).ready(function(){
          			$('#userAccounts').show();
					$('li.pendingAccounts').hide();
					$('#query').show();
          			$('#myAccount').show();
					$('#signOut').show();
				});
			</script>
_END;
		}
		else{
			echo <<<_END
					<script type="text/javascript">
						$(document).ready(function(){
							$('#query').show();
		          			$('#myAccount').show();
							$('#signOut').show();
						});
					</script>
_END;
		}
}
?>