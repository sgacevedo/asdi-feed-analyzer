<html>
    <head>
        <?php
            require_once 'UI/styleIncludes.php' ?>
        <title>ASDI Query</title>
    </head>
    <body>
        <?php
            require_once 'requires.php';
            require_once 'UI/navBar.php'; ?>
		<script type="text/javascript" src="queryScripts.js" ></script>
        <div class="contents">
        	<h1>Analyze</h1>
        	<ul class="nav nav-tabs">
    			<li class="active"><a data-toggle="tab" href="#airlines">Airlines</a></li>
    			<li><a data-toggle="tab" href="#airports">Airports</a></li>
    			<li><a data-toggle="tab" href="#airspace">Airspace</a></li>
    			<li><a data-toggle="tab" href="#flights">Flights</a></li>
    			<li><a data-toggle="tab" href="#messages">Messages</a></li>
    			<li><a data-toggle="tab" href="#regions">Regions</a></li>
  			</ul>

  			<div class="tab-content">
    			<div id="airlines" class="tab-pane fade in active">
      				<h3>Airlines</h3>
      				<form method="post" action="query.php">
	      				<div id="airlineDateRange"  class="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 400px">
						    <i class="fa fa-calendar"></i>&nbsp;
						    <span></span> <b class="caret"></b>
						</div>
						<input type="hidden" name="AIRLINE_STARTDATE" class="startDate"/>
						<input type="hidden" name="AIRLINE_ENDDATE" class="endDate" />
						<div id="delaysByAirlines" class="checkbox">
							<label><input type="checkbox" name="AIRLINE_DELAYS" value="" checked>List in order the airlines with the most delays</label>
						</div>
						<div id="airlineRegion" class="disabled">
							<label for="region">Departure region: </label>
						    <select name="AIRLINE_REGION" class="form-control" id="region" style="width: 400px" disabled>
						    	<option value="west">West</option>
						        <option value="south">South</option>
						        <option value="midwest">Midwest</option>
						        <option value="northeast">Northeast</option>
					        </select>
						</div>
						<div id="airlineSelect" class="disabled" style="margin-top: 10px">
							<label for="airline">Airline: </label>
						    <select name="AIRLINE_NAME" class="form-control" id="airline" style="width: 400px" disabled>
						        <?php 
						        	if($user->type == 'GENERAL_USER'){
										$airlines = $user->getNonRestrictedAirlines();
										
										for($i = 0; $i < count($airlines); $i++){
											echo '<option>' .$airlines[$i] . '</option>';
										}
									}
									else{ ?>
										<option>American Airlines</option>
						        		<option>Delta Airlines</option>
						        		<option>Southwest Airlines</option>
						       			<option>United Airlines</option>
								<?php } ?>
					        </select>
						</div>
						<div style="margin-top: 20px">
							<button type="submit" class="btn btn-success">Run</button>
						</div>
      				</form>
      				<div class="results"><?php require_once 'Queries/AirlineQuery.php';?></div>
    			</div>
   				<div id="airports" class="tab-pane fade">
      				<h3>Airports</h3>
      				<form method="post" action="query.php?tab=airports">
	      				<div id="airportDateRange"  class="dateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 400px">
						    <i class="fa fa-calendar"></i>&nbsp;
						    <span></span> <b class="caret"></b>
						</div>
						<input type="hidden" name="AIRPORT_STARTDATE" class="startDate"/>
						<input type="hidden" name="AIRPORT_ENDDATE" class="endDate" />
						<div id="airportSelect" class="" style="margin-top: 10px">
							<label for="airport">Airport: </label>
						    <div class="radio">
								<label><input type="radio" name="AIRPORT_RADIO" value="all_airports" checked>All Airports</label>
							</div>
						    <div class="radio">
						    	<label><input type="radio" name="AIRPORT_RADIO" value="one_airport">Choose Airport: <select name="AIRPORT_NAME" class="form-control" id="airport" style="width: 400px" disabled><?php getValidAirports(); ?></select></label>
						    </div>
						</div>
						<div id="delaySelect">
							<label for="airport">Delays: </label>
							<div class="radio">
								<label><input type="radio" name="AIRPORT_DELAY_RADIO" value="delayed_departures" checked>Show delayed departures</label>
							</div>
							<div class="radio">
							  	<label><input type="radio" name="AIRPORT_DELAY_RADIO" value="delayed_arrivals" disabled>Show delayed arrivals</label>
							</div>
						</div>
						<div style="margin-top: 20px">
							<button type="submit" class="btn btn-success">Run</button>
						</div>
      				</form>
      				<div class="results"><?php require_once 'Queries/AirportQuery.php';?></div>
    			</div>
    			<div id="airspace" class="tab-pane fade">
      				<h3>Airspace</h3>
      				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    			</div>
    			<div id="flights" class="tab-pane fade">
      				<h3>Flights</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
    			<div id="messages" class="tab-pane fade">
      				<h3>Messages</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
    			<div id="regions" class="tab-pane fade">
      				<h3>Regions</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
  			</div>
        <?php
            function getValidAirports(){
				$dbMan = new DatabaseManager();
				
				if(!$dbMan->establishConnection()){
					//database connection error
					return;
				}
				
				$request = new Request ('getAirports', 'se_Airports');
				$request->transformCommand();	

				$airports = $dbMan->executeQuery($request);
				
				//server error
				if($airports == null){
					//request was unsuccessful
				}
				
				else if($airports -> num_rows){

					/* Get number of rows returned */
					$rows = $airports->num_rows;
						
					/* For each row - push the airline name
					 * onto the $airlines array */
					for ($i = 0 ; $i < $rows ; ++$i){
						$airports->data_seek($i);
						$row = $airports->fetch_array(MYSQLI_NUM);
							
						echo "<option>" . $row[0] . "</option>";
						
					}
				}
			}
            ?>

        </div>
    </body>
</html>