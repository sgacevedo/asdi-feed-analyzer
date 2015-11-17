<!-- Method Signature: 
approveUser(User u, boolean approval) returns boolean success

Description: Approves user u if approval boolean parameter is true by changing the user status to ‘active’. Denies user u if the approval boolean parameter is false by removing the user from the database.

Pre-Condition: 
//@requires u != null && !(userLog.contains(u))

Post-Condition: 
//@ensures userLog.contains(u)
 -->

<html>
<head>
    <?php
        require_once 'UI/styleIncludes.php' ?>
	<title>Manage User Accounts</title>
</head>
<body>
    <?php
        require_once 'UI/navBar.php'; ?>
   <?php 
$link = mysql_connect("earth.cs.utep.edu", "cs_aegomez2", "utep$234");
mysql_select_db("cs_aegomez2",$link);

$query="SELECT user_id, firstName, lastName, email, type, status FROM se_Users WHERE status='PENDING_APPROVAL'";
$result=mysql_query($query);
$num=mysql_numrows($result);mysql_close();
?>
<table border="1px solid black" cellspacing="2" cellpadding="2">
<tr>
<td>
<font face="Arial, Helvetica, sans-serif">User ID</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">First Name</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Last Name</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">E-mail</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Type</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Status</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Approve User?</font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif">Deny User?</font>
</td>
</tr>
<?php $i=0;while ($i < $num) {
$f1=mysql_result($result,$i,"user_id");
$f2=mysql_result($result,$i,"firstName");
$f3=mysql_result($result,$i,"lastName");
$f4=mysql_result($result,$i,"email");
$f5=mysql_result($result,$i,"type");
$f6=mysql_result($result,$i,"status");

?>
<tr>
<td>
<?php echo $f1; ?>
</td>
<td>
<?php echo $f2; ?>
</td>
<td>
<?php echo $f3; ?>
</td>
<td>
<?php echo $f4; ?>
</td>
<td>
<?php echo $f5; ?>
</td>
<td>
<?php echo $f6?>
</td>
<td>
<?php 
echo '<form action="approved.php" method="POST"?>
    	<input type="submit" value="Approve">
    		<input type="hidden" name="userID" value='.$f1.'>
		</form>'; 
?>
</td>
<td>
<?php 
echo '<form action="denied.php" method="POST"?>
    	<input type="submit" value="Deny">
    		<input type="hidden" name="userID" value='.$f1.'>
		</form>'; 
?>
</td>
</tr>
<?php $i++;}?>
        
        
</body>
</html>

<?php
    require_once 'requires.php';
    
    
    
?>
