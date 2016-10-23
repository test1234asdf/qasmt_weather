<?php
	
	session_start();
	
	
	
	
	
	$db_table = "J03";
	
	$data_type = $_SESSION['data_type'];
	
	$data_type_formatted = $_SESSION['data_type_formatted'];
	$data_type_unit = $_SESSION['data_type_unit'];
	$table_variablename_value = $_SESSION['table_variablename_value'];
	
	$db_name = $_SESSION['db_name'];
	$db_host = $_SESSION['db_host'];
	$db_user = $_SESSION['db_user'];
	$db_pass = $_SESSION['db_pass'];
	
	$table_variablename_time = $_SESSION['table_variablename_value'];
			
	$startdate_default = $_SESSION['startdate_default'];
	
	$datapointslimit = $_SESSION['datapointslimit'];
	
	$t_frame_choices = $_SESSION['t_frame_choices'];	
	
	$_SESSION['data_type'] = $data_type;
	
	$_SESSION['data_type_formatted'] =$data_type_formatted;
	$_SESSION['data_type_unit'] = $data_type_unit;
	$_SESSION['table_variablename_value'] = $table_variablename_value;
	
	$_SESSION['db_name'] = $db_name;
	$_SESSION['db_host'] = $db_host;
	$_SESSION['db_user'] = $db_user;
	$_SESSION['db_pass'] = $db_pass;
	$_SESSION['db_table'] = $db_table;
	
	$_SESSION['table_variablename_value'] = $table_variablename_time;
			
	$_SESSION['startdate_default'] = $startdate_default;
	
	$_SESSION['datapointslimit'] = $datapointslimit;
	
	$_SESSION['t_frame_choices'] = $t_frame_choices;

?>

<!DOCTYPE html>
	<html>
		<head>
			<title> Weather @ QASMT </title>
			
			<link rel= "stylesheet" type="text/css" href = "../CSS/data_visual.css" />
			
			<?php
			
				$t_frame = 1;
				
				$startdate = $startdate_default;
	
				$userandomdata = false;

				if ($_POST) {
					
					$t_frame = $_POST['t_frame'];
					
					$startdate = $_POST['startdate'];
	
					if ( isset($_POST['userandomdata']) ) {
						
						$userandomdata = $_POST['userandomdata'];
					
					}
					
					else {
						
						$userandomdata = false;
						
					}
					
					if ( strlen($_POST['startdate']) <= 1 ) {
						
						$startdate = $startdate_default;
					
					}
					
					$_SESSION['t_frame'] = $t_frame;
					
					$_SESSION['startdate'] = $startdate;
					
					$_SESSION['userandomdata'] = $userandomdata;
					
				}
				
				for ($t_frame_counter = 0; $t_frame_counter < ( count($t_frame_choices) / 2 ); $t_frame_counter++) {
					
					if ( $t_frame == $t_frame_choices[ $t_frame_counter * 2 ] ) {
						
						$t_frame_formatted = $t_frame_choices[ $t_frame_counter * 2 + 1];
						
					}
					
				}

			?>
			
			<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
			
			<script type='text/javascript' src="http://code.highcharts.com/highcharts.js"></script>
			<script type='text/javascript' src="http://code.highcharts.com/modules/exporting.js"></script>
			
			<script type="text/javascript">

				$(window).load(function(){
					$(function () {
						$.getJSON('data.php', function (data) {
							$('#container').highcharts({
								chart: {
									zoomType: 'x'
								},
								title: {
									text: 'Weather @ QASMT - <?php echo $db_table; ?> - Line Graph - <?php echo $data_type_formatted; ?> (<?php echo $data_type_unit; ?>) - <?php echo $t_frame_formatted; ?> - Start date: <?php echo $startdate; ?>'
								},
								subtitle: {
									text: document.ontouchstart === undefined ?
											'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
								},
								xAxis: {
									type: 'datetime',
									labels: {
										format: '{value:%Y %b %d, %H-%M-%S}',
										rotation: 90,
										align: 'left'
									},
									title: {
										text: 'Time (Year Month Day, Hour-Minute-Second)'
									}
								},
								yAxis: {
									title: {
										text: ' <?php echo $data_type_formatted; ?> (<?php echo $data_type_unit; ?>)'
									}
								},
								legend: {
									enabled: false
								},
								plotOptions: {
									area: {
										fillColor: {
											linearGradient: {
												x1: 0,
												y1: 0,
												x2: 0,
												y2: 1
											},
											stops: [
												[0, Highcharts.getOptions().colors[0]],
												[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
											]
										},
										marker: {
											radius: 2
										},
										lineWidth: 1,
										states: {
											hover: {
												lineWidth: 1
											}
										},
										threshold: null
									}
								},

								series: [{
									type: 'area',
									name: ' <?php echo $data_type_formatted; ?> ',
									data: data
								}]
							});
						});
					});
				});

			</script>
			
			<script>
			
				function clickReset(settingsform) {
					
					settingsform.t_frame.value = 1;
					
					settingsform.startdate.value = <?php echo $startdate_default; ?>;
					
					settingsform.userandomdata.checked = false;
					
					settingsform.submit.click();
					
				}
				
			</script>
			
		</head>
		
		<body>
		
			<center>
		
				<div id = "wrapper">
				
					<br> <br>
				
					<div id = "topbar">
						<a href="index.html"> Weather @ QASMT - High Performance Computing </a>
					</div>
				
					<br> <br>
					
					<div id = "taskbar">
						<ul>						
							<li> <a href = "index.html"> About Us </a> </li>
							<li> <a href = "temp_heatmap.php"> Temperature </a> </li>
							<li> <a href = "humidity_heatmap.php"> Humidity </a> </li>
							<li> <a href = "sound_heatmap.php"> Sound </a> </li>
							<li> <a href = "light_heatmap.php"> Light  </a> </li>
							<li> <a href = "pressure_heatmap.php"> Pressure </a> </li>
						</ul>
					</div>
					
					<br> <br>
					
					<div id = "leftbar"> </div>
					
					<div id = "rightbar"> </div>
					
					<br> <br>
					
					<div id = "content" width="800">
					
						<div id="container" style="height: 800px; min-width: 640px; width: 1200px; margin: 0 auto"></div>

						<br> <br>
				
						<form name="settingsform" action="<?php echo $db_table; ?>_line.php" method="post" align="center">
							
							<label for="t_frame"> Please chose a time-frame for the line graph: </label>
								<select id="t_frame" name="t_frame">
								
									<option value="1" <?php if ($t_frame == 1) { echo "selected"; } ?> > Daily </option>
								
									<?php
										
										for ($t_frame_counter = 1; $t_frame_counter < ( count($t_frame_choices) / 2 ); $t_frame_counter++) {
									
											echo "<option value=\" " . $t_frame_choices[$t_frame_counter * 2] . "\" ";
											
											if ( $t_frame == $t_frame_choices[ $t_frame_counter * 2 ] ) {
												echo "selected";
											}
										
											echo ">" . $t_frame_choices[ $t_frame_counter * 2 + 1 ] . "</option>";
											
										}
										
									?>
									
								</select>

							<br> <br>
							
							<label for="startdate"> Please chose a specific starting date: </label>
								<input type="date" id="startdate" name="startdate" value="<?php if ($_POST) { echo $_POST['startdate']; } else { $startdate = $startdate_default; echo $startdate; } ?>">
								
							<br> <br>
							
							<label for="userandomdata"> Check this to use test (random) data instead of database <br> data (i.e. use this when database connection not avaliable) </label>
								
								<br> <br>
								
								<input type="checkbox" id="userandomdata" name="userandomdata" value="true" <?php if ( $userandomdata == true ) { echo "checked"; } ?> > Use test (random) data
								
							<br> <br>
							
							<input type="submit" id="submit" name="submit" value="Submit changes"/>
						
							<input type="reset" onClick='clickReset(settingsform)'/>
						
						</form>
					
					</div>
					
				</div>
				
			</center>
			
		</body>

	</html>