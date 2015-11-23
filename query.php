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
        <?php 
        	require_once 'runQuery.php';?>
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
	      				<div id="airlineDateRange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 400px">
						    <i class="fa fa-calendar"></i>&nbsp;
						    <span></span> <b class="caret"></b>
						</div>
						<input type="hidden" name="AIRLINE_STARTDATE" />
						<input type="hidden" name="AIRLINE_ENDDATE" />
						<div id="delaysByAirlines" class="checkbox">
							<label><input type="checkbox" name="AIRLINE_DELAYS" value="" checked>List in order the airlines with the most delays</label>
						</div>
						<div id="airlineRegion" class="disabled">
							<label for="region">Region: </label>
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
						    	<option>American Airlines</option>
						        <option>Delta Airlines</option>
						        <option>Southwest Airlines</option>
						        <option>United Airlines</option>
					        </select>
						</div>
						<div style="margin-top: 20px">
							<button type="submit" class="btn btn-success">Run</button>
						</div>
      				</form>
      				<div class="results"></div>
    			</div>
   				<div id="airports" class="tab-pane fade">
      				<h3>Menu 1</h3>
      				<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
    			</div>
    			<div id="airspace" class="tab-pane fade">
      				<h3>Menu 2</h3>
      				<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
    			</div>
    			<div id="flights" class="tab-pane fade">
      				<h3>Menu 3</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
    			<div id="messages" class="tab-pane fade">
      				<h3>Menu 3</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
    			<div id="regions" class="tab-pane fade">
      				<h3>Menu 3</h3>
      				<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
    			</div>
  			</div>
        <?php
            
            ?>

        </div>
    </body>
</html>