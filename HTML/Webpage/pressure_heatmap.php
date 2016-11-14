<?php  

	session_start();
	
	
	
	

	//Defining the modifiable variables

	//Data type - temp, humidity, sound, pressure, light
	
	$data_type = 'pressure';
	
	switch ($data_type) {
		case 'temp':
			$data_type_formatted = 'Temperature';
			$data_type_unit = 'Degrees: Celcius';
			$table_variablename_value = 'AverageTemp';
		break;
		
		case 'humidity':
			$data_type_formatted = 'Humidity';
			$data_type_unit = 'Relative Humidity';
			$table_variablename_value = 'Humidity';
		break;
		
		case 'sound':
			$data_type_formatted = 'Sound';
			$data_type_unit = 'Sound Unit';
			$table_variablename_value = 'SoundPressure';
		break;
		
		case 'pressure':
			$data_type_formatted = 'Pressure';
			$data_type_unit = 'Pascal';
			$table_variablename_value = 'Pressure';
		break;
		
		case 'light':
			$data_type_formatted = 'Light';
			$data_type_unit = 'Lux';
			$table_variablename_value = 'Light';
		break;
	}

	$db_name = 'weatherdata';
	$db_host = '10.41.68.102';
	$db_user = 'cdeod1';
	$db_pass = 'cdeod1';
	$db_table = '';
		
	$table_variablename_time = 'CurrentTime';
	
	$usetestdatabase = false;
	
	if ( $usetestdatabase ) {
	
		$db_name = 'test';
		$db_host = 'localhost';
		$db_user = 'root';
		$db_pass = '';
			
	}
	
	$startdate_default = "2016-01-01";
	
	$datapointslimit = 25000;
	
	$t_frame_choices = Array(1, "Daily", 2, "Bi-Daily", 7, "Weekly", 14, "Fortnightly", 30, "Monthly", 60, "Bi-Monthly");
	
	$rand_min = 20;
	$rand_max = 40;
	
	$rand_change = 0.1;
	
	$rand_scale = 10;
	
	$userandomdata = false;
		
	$db_tablenames = array('Group_2_Staffroom', 'J01', 'J02', 'J03', 'J04', 'J05', 'J06', 'J07', 'J08', 'Group_13_Staffroom', 'SeniorStudy');
	$db_tablevalues = array();
	
	
	
	
	
	//PASS ON ALL VARIABLES INTO SESSION GLOBALLY

	$_SESSION['data_type'] = $data_type;

	$_SESSION['data_type_formatted'] = $data_type_formatted;
	$_SESSION['data_type_unit'] = $data_type_unit;
	$_SESSION['table_variablename_value'] = $table_variablename_value;

	$_SESSION['db_name'] = $db_name;
	$_SESSION['db_user'] = $db_user;
	$_SESSION['db_pass'] = $db_pass;
	$_SESSION['db_table'] = $db_table;
		
	$_SESSION['table_variablename_time'] = $table_variablename_time;
	
	$_SESSION['startdate_default'] = $startdate_default;
	
	$_SESSION['datapointslimit'] = $datapointslimit;
	
	$_SESSION['t_frame_choices'] = $t_frame_choices;
	
	$_SESSION['rand_min'] = $rand_min;
	$_SESSION['rand_max'] = $rand_max;
	
	$_SESSION['rand_change'] = $rand_change;
	
	$_SESSION['rand_scale'] = $rand_scale;

?>

<! DOCTYPE html>
	<html>
		<head>
		
			<title> Weather @ QASMT </title>
			
			<link rel= "stylesheet" type="text/css" href = "../CSS/data_visual.css" />
		
			<?php
	
				$userandomdata = false;

				if ($_POST) {
	
					if ( isset($_POST['userandomdata']) ) {
						
						$userandomdata = $_POST['userandomdata'];
					
					}
					
					else {
						
						$userandomdata = false;
						
					}
					
					$_SESSION['userandomdata'] = $userandomdata;
					
				}
	
				if ($userandomdata) {
					
					$counter = 0;
					
					for ( $counter = 0; $counter < count($db_tablenames); $counter++ ) {
						
						$db_tablevalues[$counter] = mt_rand($rand_min, $rand_max);
					
					}
					
				}

				else {
				
					$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

					if ( !$conn ) {
						
						die('Could not connect: ' . mysqli_error());
					
					}

					mysqli_select_db($conn, $db_name);
					
					$counter = 0;
					
					for ($counter = 0; $counter < count($db_tablenames); $counter++) {
						
						$db_tablenames_currenttable = $db_tablenames[$counter];
						
						$query = mysqli_query($conn ,"SELECT $table_variablename_value FROM $db_tablenames_currenttable ORDER BY $table_variablename_time DESC");
						
						while ( list($result) = mysqli_fetch_array($query) ) {	
						
							$db_tablevalues[$counter] = "$result";
							
						}
					
					}
					
				}
				
				$grp2 = $db_tablevalues[0];
				$j01 = $db_tablevalues[1];
				$j02 = $db_tablevalues[2];
				$j03 = $db_tablevalues[3];
				$j04 = $db_tablevalues[4];
				$j05 = $db_tablevalues[5];
				$j06 = $db_tablevalues[6];
				$j07 = $db_tablevalues[7];
				$j08 = $db_tablevalues[8];
				$grp13 = $db_tablevalues[9];
				$ss = $db_tablevalues[10];

			?>
			
			<script src="../js/jquery-3.1.0.js"></script>
			<script src="../js/highmaps-5.0.js"></script>
			<script src="../js/highmaps-exporting-5.0.js"></script>
			
			<script>

				$(function() {
					$('#container').highcharts('Map', {
						title: {
							"text": 'Weather @ QASMT - Heatmap - <?php echo $data_type_formatted; ?> (<?php echo $data_type_unit; ?>)'
						},
						subtitle: {
							"text": 'Click on a room to see the corresponding line graph'
						},
						colorAxis: {
							type: 'linear',
							stops: [
								[0, '#1434F7'],
								[0.08, '#1768C5'],
								[0.16, '#32CDFA'],
								[0.24, '#3FFFFD'],
								[0.32, '#42FFFD'],
								[0.4, '#3BFB42'],
								[0.48, '#3AFB40'],
								[0.56, '#3BFB42'],
								[0.64, '#9BC838'],
								[0.72, '#CDE243'],
								[0.8, '#FFFB4E'],
								[0.88, '#FB9738'],
								[0.96, '#F9632D']
							],
						},
						legend: {
							layout: 'vertical',
							align: 'right',
							verticalAlign: 'bottom'
						},
						series: [{
							dataLabels: {
								enabled: "true",
								color: '#000000',
								format: '{point.name}',
								style: {
									"textShadow": "0"
								},
							},
							name: "<?php echo $data_type_formatted; ?>",
							type: "map",
							states: {
								hover: {
									color: '#bbcfe3'
								}
							},
							tooltip: {
								valueSuffix: ' (<?php echo $data_type_unit; ?>)',
							},
							data: [{
									"name": "Group 2 Staff Room",
									"value": <?php echo $grp2 ?>,
									"path": "M6,-495L234,-495,234,-398,6,-398,6,-495",
									events: {
										click: function () {
											window.location = "group_2_staffroom_line.php";
										}
									}
								   }, {
									"name": "J08",
									"value": <?php echo $j08 ?>,
									"path": "M242,-487L490,-487,490,-398,242,-398,242,-487",
									events: {
										click: function () {
											window.location = "J08_line.php";
										}
									}
								   }, {
									"name": "J07",
									"value": <?php echo $j07 ?>,
									"path": "M498,-544L760,-544,760,-398,498,-398,498,-544",
									events: {
										click: function () {
											window.location = "J07_line.php";
										}
									}
								   }, {
									"name": "J06",
									"value": <?php echo $j06 ?>,
									"path": "M847,-784L996,-784,996,-604,847,-604,847,-784",
									events: {
										click: function () {
											window.location = "J06_line.php";
										}
									}
								   }, {
									"name": "J05",
									"value": <?php echo $j05 ?>,
									"path": "M855,-995L994,-995,994,-792,855,-792,855,-995",
									events: {
										click: function () {
											window.location = "J05_line.php";
										}
									}
								   }, {
									"name": "J04",
									"value": <?php echo $j04 ?>,
									"path": "M653,-995L849,-995,849,-793,653,-793,653,-995",
									events: {
										click: function () {
											window.location = "J04_line.php";
										}
									}
								   }, {
									"name": "J03",
									"value": <?php echo $j03 ?>,
									"path": "M477,-995L647,-995,647,-792,477,-792,477,-995",
									events: {
										click: function () {
											window.location = "J03_line.php";
										}
									}
								   }, {
									"name": "J02",
									"value": <?php echo $j02 ?>,
									"path": "M174,-997L343,-997,343,-795,174,-795,174,-997",
									events: {
										click: function () {
											window.location = "J02_line.php";
										}
									}
								   }, {
									"name": "J01",
									"value": <?php echo $j01 ?>,
									"path": "M0,-997L169,-997,169,-795,0,-795,0,-997",
									events: {
										click: function () {
											window.location = "J01_line.php";
										}
									}
								   }, {
									"name": "Group 1 & 3 Staffroom",
									"value": <?php echo $grp13 ?>,
									"path": "M391,-759L727,-759,727,-611,391,-611,391,-759",
									events: {
										click: function () {
											window.location = "group_13_staffroom_line.php";
										}
									}
								   }, {
									"name": "Senior Study",
									"value": <?php echo $ss ?>,
									"path": "M767,-596L1000,-596,1000,-398,767,-398,767,-596",
									events: {
										click: function () {
											window.location = "seniorstudy_line.php";
										}
									}
								}]
							}]
						});
					});

			</script>
			
			<script>
			
				function clickReset(settingsform) {
					
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
					
					<div id = "content">
					
						<div id="container" style="height: 800px; min-width: 640px; width: 1200px; margin: 0 auto"></div>
					
						<br> <br>
				
						<form name="settingsform" action="heatmap.php" method="post" align="center">
						
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