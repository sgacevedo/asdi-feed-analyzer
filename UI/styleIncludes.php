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
