<?php include ('nav.php')?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <?php
        require_once 'UI/styleIncludes.php' ?>
	<title>Super User Menu</title>
</head>
<body>
    <?php
        require_once 'UI/navBar.php'; ?>


<?php
    require_once 'requires.php';
?>
<?php
// connect to DB
$link = mysql_connect ( "earth.cs.utep.edu", "cs_aegomez2", "utep$234" );
mysql_select_db ( "cs_aegomez2", $link );

// query blog title and text
$query = "UPDATE se_Users SET status = 'ACTIVE' WHERE user_id=" . $_POST ["user_id"] . "';";
$result = mysql_query ( $query );

// print event title and text
echo "User with id ".$_POST["user_id"]." has been approved.";
?>

</body>
</html>
