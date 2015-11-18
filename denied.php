<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <?php
        require_once 'UI/styleIncludes.php' ?>
	<title>Super User Menu</title>
</head>
<body>
    <?php
		require_once 'requires.php';
        require_once 'UI/navBar.php'; ?>


<?php
// connect to DB
$link = mysql_connect ( "earth.cs.utep.edu", "cs_aegomez2", "utep$234" );
mysql_select_db ( "cs_aegomez2", $link );

// query blog title and text
$query = "DELETE FROM se_users WHERE user_id=" . $_POST ["userID"] . "';";
$result = mysql_query ( $query );

// print event title and text
echo "User with id ".$_POST["userID"]." has been denied access.";
?>

</body>
</html>
