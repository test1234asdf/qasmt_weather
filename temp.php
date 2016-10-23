<?php
	
	session_start();
	
	
	
	
	
	//Defining the modifiable variables

	//Data type - temp, humidity, sound, pressure, light
	
	$data_type = 'temp';
	
	//Formatting the data type variable for display
	
	switch ($data_type) {
		case 'temp':
			$data_type_formatted = 'Temperature';
			$data_type_unit = 'Degrees: Celcius';
		break;
		
		case 'humidity':
			$data_type_formatted = 'Humidity';
			$data_type_unit = 'Relative Humidity';
		break;
		
		case 'sound':
			$data_type_formatted = 'Sound';
			$data_type_unit = 'Sound Unit';
		break;
		
		case 'pressure':
			$data_type_formatted = 'Pressure';
			$data_type_unit = 'Pascal';
		break;
		
		case 'light':
			$data_type_formatted = 'Light';
			$data_type_unit = 'Lux';
		break;
		
		default:
			$data_type_formatted = 'Test';
			$data_type_unit = 'Integer';
	}

	$db_name = 'weatherdata';
	$db_host = '10.41.68.102';
	$db_user = 'cdeod1';
	$db_pass = 'cdeod1';
	$db_table = 'J03';
		
	$table_variablename_time = 'CurrentTime';
	$table_variablename_value = 'AverageTemp';
	
	$usetestdata = false;
	
	if ( $usetestdata ) {
	
		$db_name = 'test';
		$db_host = 'localhost';
		$db_user = 'root';
		$db_pass = '';
		$db_table = 'testtable';
		
		$table_variablename_time = 'dateandtime';
		$table_variablename_value = 'valuenumber';
			
	}
	
	$startdate_default = "2016-01-01";
	
	//Good value to have is 25000, reliable but slow to load
	
	$datapointslimit = 25000;
	
	$t_frame_choices = Array(1, "Daily", 2, "Bi-Daily", 7, "Weekly", 14, "Fortnightly", 30, "Monthly", 90, "Quarterly", 365, "Annually");
	
	
	
	
	
	//Copying variables over to global SESSION variable to transfer to data php file
	
	$_SESSION['db_name'] = $db_name;
	$_SESSION['db_host'] = $db_host;
	$_SESSION['db_user'] = $db_user;
	$_SESSION['db_pass'] = $db_pass;
	$_SESSION['db_table'] = $db_table;
	
	$_SESSION['table_variablename_time'] = $table_variablename_time;
	$_SESSION['table_variablename_value'] = $table_variablename_value;
	
	$_SESSION['datapointslimit'] = $datapointslimit;

?>

<!DOCTYPE html>
	<html>
		<head>
			<title> Weather @ QASMT </title>
			
			<?php
			
				$t_frame = 1;
				
				$startdate = $startdate_default;

				if ($_POST) {
					
					$t_frame = $_POST['timeframe'];
					
					$startdate = $_POST['startdate'];
					
					if ( strlen($startdate) <= 1 ) {
						
						$startdate = $startdate_default;
						
					}
					
					$_SESSION['t_frame'] = $t_frame;
					
					$_SESSION['startdate'] = $startdate;
					
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
			
				function clickReset() {
					
					document.settingsform.timeframe.value = 1;
					
					document.settingsform.startdate.value = <?php echo $startdate_default ?>;
					
					document.settingsform.submit.click();
					
				}
				
			</script>
			
		</head>
		
		<body>
		
			<div id="container" style="min-width: 310px; height: 750px; margin: 0 auto">
			
			</div>

			<div>
			
				<br> <br>
				
				<!-- change form php action link to $dbtable_$datatype.php                                                  -->
			
				<form name="settingsform" action=""<?php echo $data_type ?>" + '.php'" method="post" align="center">
					
					<label for="timeframe"> Please chose a time-frame for the line graph: </label>
						<select id="timeframe" name="timeframe">
						
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
					
					<input type="submit" id="submit" name="submit" value="Submit changes" />
				
					<input type="reset" onClick='clickReset(settingsform)'/>
				
				</form>
			
			</div>
			
		</body>
	</html>