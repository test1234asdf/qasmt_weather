<?php

	session_start();
	
	ini_set('memory_limit', '256M');
	
	
	
	
	
	//Accepting the modifiable global SESSION variables from original file
	
	$db_name = $_SESSION['db_name'];
	$db_host = $_SESSION['db_host'];
	$db_user = $_SESSION['db_user'];
	$db_pass = $_SESSION['db_pass'];
	$db_table = $_SESSION['db_table'];
	
	$table_variablename_time = $_SESSION['table_variablename_time'];
	$table_variablename_value = $_SESSION['table_variablename_value'];
	
	$datapointslimit = $_SESSION['datapointslimit'];
	
	
	
	
	
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

	if ( !$conn ) {
		
		die('Could not connect: ' . mysqli_error());
	
	}

	mysqli_select_db($conn, $db_name);
	
	$t_frame = $_SESSION['t_frame'];
	$startdate = $_SESSION['startdate'];
	
	$startdate = $startdate . " 00:00:00";

	$query = mysqli_query($conn ,"SELECT * FROM $db_table WHERE ($table_variablename_time >= '$startdate') ");
	
	$counter = 0;
	$counter_2 = 0;
	$counter_3 = 0;
	
	$values = array();
	
	$rows = array();
	
	while ( $r = mysqli_fetch_array($query) ) {
		
		////////////////////////////////////////////////////////////////////////////////////////////////////////////// TIME VALUES NOT LINING UP RIGHT WITH AXIS
		////////////////////////////////////////////////////////////////////////////////////////////////////////////// TIME VALUES NOT LINING UP RIGHT WITH AXIS
		////////////////////////////////////////////////////////////////////////////////////////////////////////////// TIME VALUES NOT LINING UP RIGHT WITH AXIS
		////////////////////////////////////////////////////////////////////////////////////////////////////////////// TIME VALUES NOT LINING UP RIGHT WITH AXIS
		////////////////////////////////////////////////////////////////////////////////////////////////////////////// TIME VALUES NOT LINING UP RIGHT WITH AXIS

		$rawdatetime = $r[$table_variablename_time];
			$year = substr($rawdatetime, -19, 4);
			$month = substr($rawdatetime, -14, 2);
			$day = substr($rawdatetime, -11, 2);
			$hour = substr($rawdatetime, -8, 2);
			$minute = substr($rawdatetime, -5, 2);
			$second = substr($rawdatetime, -2, 2);
			
		$values[$counter][] = gmmktime((float)$hour, (float)$minute, (float)$second, (float)$month, (float)$day, (float)$year) * 1000;
		
		$values[$counter][] = (float)$r[$table_variablename_value];
		
		if ( ($counter > 0 || $t_frame == 1) && ( ($counter % $t_frame) == 0) ) {
			
			$sum_rawdatetime = 0;
			
			$sum_valuenumber = 0;
		
			for ($counter_3 = 0; $counter_3 < $t_frame; $counter_3++) {
				
				$sum_rawdatetime = $sum_rawdatetime + $values[$counter_2 * $t_frame + $counter_3][0];
				
				$sum_valuenumber = $sum_valuenumber + $values[$counter_2 * $t_frame + $counter_3][1];
				
			}
			
			$rows[$counter_2][] = (float)($sum_rawdatetime / $t_frame);
			$rows[$counter_2][] = sprintf( "%.2f", (float) ( $sum_valuenumber / $t_frame ) );
		
			$counter_2 = $counter_2 + 1;
			
		}
			
		if ( $counter_2 > $datapointslimit ) {
			break;
		}
		
		$counter = $counter + 1;
		
	}
	
	$echo_string = json_encode($rows, JSON_NUMERIC_CHECK);
		
	echo $echo_string;
	
?>