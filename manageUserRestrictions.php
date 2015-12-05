<html>
	<head>
    	<?php require_once 'UI/styleIncludes.php'?>
		<title>Manage User Restrictions</title>
		
		<script type="text/javascript">
		$(document).ready(function(){
			var url = window.location.toString();
			url = url.split('?');
			if(url[1] != undefined){
				tabParameter = url[1].split('=')[1];
				tabParameter = '#' + tabParameter;
				
				//remove active 
				$('.nav.nav-tabs .active').removeClass('active');
				$('.tab-content .active').removeClass('in active');
				
				$('.nav.nav-tabs li > a[href="' + tabParameter + '"]').parents('li').addClass('active');
				$('.tab-content ' + tabParameter).addClass('in active');
			}
		});
		</script>
	</head>
	<body>
		<?php require_once 'requires.php';
				require_once 'UI/navBar.php'; ?>
				
		<?php 

			if(isset($_POST['DELETE_RESTRICTION_ID']) && $user->type == 'ADMINISTRATOR'){
				$result = $user->removeRestrictionRequest($_POST['DELETE_RESTRICTION_ID'], $_POST['RESTRICTION_TABLE']);
				
				if($result){ showBanner('#restrictionDeleted');}
				else{ showBanner('#error');}
			}
			else if (isset($_POST['REJECT_RESTRICTION_ID']) && $user->type == 'SUPER_USER'){
				$result = $user->approveRestriction($_POST['REJECT_RESTRICTION_ID'], $_POST['RESTRICTION_TABLE'], false); 
				
				if($result){ showBanner('#restrictionRejected');}
				else{ showBanner('#error');}
			}
			else if(isset($_POST['APPROVE_RESTRICTION_ID']) && $user->type == 'SUPER_USER'){
				$result = $user->approveRestriction($_POST['APPROVE_RESTRICTION_ID'], $_POST['RESTRICTION_TABLE'], true);
		
				if($result){ showBanner('#restrictionApproved');}
				else{ showBanner('#error');}
			}
		?>
		<div class="contents">
			<div id="restrictionApproved" class="alert alert-success" style="display: none;">
				<strong><i class="fa fa-check"></i>Restriction Approved.</strong>
			</div>
			<div id="restrictionRejected" class="alert alert-danger" style="display: none;">
				<strong><i class="fa fa-times"></i>Restriction Rejected.</strong>
			</div>
			<div id="restrictionDeleted" class="alert alert-warning" style="display: none;">
				<strong><i class="fa fa-trash"></i>Restriction Deleted.</strong>
			</div>
			<div id="error" class="alert alert-warning" style="display: none;">
				<strong><i class="fa fa-times"></i>Error Occured.</strong>
			</div>
			<h1>Pending Restrictions</h1>
			<?php 
			if($user->type == 'ADMINISTRATOR' || $user->type == 'SUPER_USER'){
			?>
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#airline">Airline Restrictions</a></li>
				<li><a data-toggle="tab" href="#region">Region Restrictions</a></li>
			</ul>
			<div class="tab-content">
				<div id="airline" class="tab-pane fade in active">
					<h3>Airline Restrictions</h3>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>User ID</th>
								<th>Name</th>
								<th>Restricted Airline</th>
								<th>Status</th>
								<th></th>
								<?php if($user->type == 'SUPER_USER'){ echo "<th></th>";}?>
							</tr>
						</thead>	
						<tbody>
						<?php 
							/* Create new instance of database manager */
							$dbMan = new DatabaseManager();
							
							/* Establish Connection with the database */
							if(!$dbMan->establishConnection()){
								//database connection error
								return;
							}
							
							/* Create new request to get all pending airline restrictions */
							$request = new Request('getPendingAirlineRestrictions', 'se_Airline_Restrictions');
							$request->transformCommand();
							
							/* Execute query */
							$results = $dbMan->executeQuery($request);
							
							
							if($results == null){
								//request failed
							}
							else{
								
								$rows = $results->num_rows;
									
								for ($i = 0 ; $i < $rows ; ++$i){
									$results->data_seek($i);
									$row = $results->fetch_array(MYSQLI_NUM);
									
									$userId = $row[1];
									$restrictionId = $row[0];
									$name = "$row[2] $row[3]";
									$airline = $row[4];
									$status = $row[5];
									
									echo "<tr>";
									echo "<td>$userId</td>";
									echo "<td>$name</td>";
									echo "<td>$airline</td>";
									echo "<td>$status</td>";
									echo "<td>";
									displayRestrictionButtons($user->type, $restrictionId, 'se_Airline_Restrictions');
									echo "</td>";
									echo "</tr>";
								}
								
								if($rows <= 0){
									echo "<tr><td>No items</td></tr>";
								}
							}
						?>
						</tbody>
					</table>
  				</div>
  				<div id="region" class="tab-pane fade">
    				<h3>Region Restrictions</h3>
    				<table class="table table-hover">
						<thead>
							<tr>
								<th>User ID</th>
								<th>Name</th>
								<th>Restricted Region</th>
								<th>Status</th>
								<th></th>
								<?php if($user->type == 'SUPER_USER'){ echo "<th></th>";}?>
							</tr>
						</thead>	
						<tbody>
						<?php 
							/* Create new instance of database manager */
							$dbMan = new DatabaseManager();
							
							/* Establish Connection with the database */
							if(!$dbMan->establishConnection()){
								//database connection error
								return;
							}
							
							/* Create new request to get all pending airline restrictions */
							$request = new Request('getPendingRegionRestrictions', 'se_Region_Restrictions');
							$request->transformCommand();
							
							/* Execute query */
							$results = $dbMan->executeQuery($request);
							
							
							if($results == null){
								//request failed
							}
							else{
								
								$rows = $results->num_rows;
									
								for ($i = 0 ; $i < $rows ; ++$i){
									$results->data_seek($i);
									$row = $results->fetch_array(MYSQLI_NUM);
									
									$userId = $row[1];
									$restrictionId = $row[0];
									$name = "$row[2] $row[3]";
									$region = $row[4];
									$status = $row[5];
									
									echo "<tr>";
									echo "<td>$userId</td>";
									echo "<td>$name</td>";
									echo "<td>$region</td>";
									echo "<td>$status</td>";
									echo "<td>";
									displayRestrictionButtons($user->type, $restrictionId, 'se_Region_Restrictions');
									echo "</td>";
									echo "</tr>";
								}
								
								if($rows <= 0){
									echo "<tr><td>No items</td></tr>";
								}
							}
						?>
						</tbody>
					</table>
  				</div>
			</div>
			<?php 
				if($user->type == 'ADMINISTRATOR'){
					?>
						<a href="addRestriction.php" class="btn btn-success">Request a new Restriction</a>
					<?php 
				}
			}?>
		</div>
	</body>
</html>

<?php 
function displayRestrictionButtons($userType, $restrictionId, $restrictionTable){
	$param = '';
	if($restrictionTable == 'se_Region_Restrictions'){ $param = 'region';}
	else{ $param = 'airline';}
	
	if($userType == 'SUPER_USER'){
		echo "<form method='post' action='manageUserRestrictions.php?tab=$param'>";
		echo "<input type='hidden' value='$restrictionId' name='REJECT_RESTRICTION_ID' />";
		echo "<input type='hidden' value='$restrictionTable' name='RESTRICTION_TABLE' />";
		echo "<button type='submit' class='btn btn-danger'>Reject</button>";
		echo "</form></td><td>";
		echo "<form method='post' action='manageUserRestrictions.php?tab=$param'>";
		echo "<input type='hidden' value='$restrictionId' name='APPROVE_RESTRICTION_ID' />";
		echo "<input type='hidden' value='$restrictionTable' name='RESTRICTION_TABLE' />";
		echo "<button type='submit' class='btn btn-success'>Approve</button>";
		echo "</form></td>";
	}
	if($userType == 'ADMINISTRATOR'){
		echo "<form method='post' action='manageUserRestrictions.php?tab=$param'>";
		echo "<input type='hidden' value='$restrictionId' name='DELETE_RESTRICTION_ID' />";
		echo "<input type='hidden' value='$restrictionTable' name='RESTRICTION_TABLE' />";
		echo "<button type='submit' class='btn btn-danger'>Delete</button>";
		echo "</form>";
		}
}

function showBanner($selector){
	echo <<<_END
		<script type="text/javascript">
			$(document).ready(function(){
				$('$selector').show();
			});					
		</script>
_END;
}
?>