<html>
	<head>
		<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	    <?php
	        require_once 'UI/styleIncludes.php'; ?>
		<title>Export Report</title>
		<script type="text/javascript">
			var dataFromParent;    
		    $(document).ready(function(){
				$(".results").html(dataFromParent);
				$('.btn').remove();
				PrintWindow();
			});
		    function PrintWindow() {                    
		        window.print();            
		        CheckWindowState();
		     }
	
		     function CheckWindowState()    {           
		         if(document.readyState=="complete") {
		             window.close(); 
		         } else {           
		             setTimeout("CheckWindowState()", 1)
		         }
		     }
		</script>
		<style rel="stylesheet">
			.highcharts-button{
			  display:none;
			}
		</style>
	</head>
	<body>
	
		<div class="results"></div>
	
	</body>
</html>