<?php

	session_start();
	
	ini_set('memory_limit', '1024M');
	
	
	
	
	
	$db_name = $_SESSION['db_name'];
	$db_host = $_SESSION['db_host'];
	$db_user = $_SESSION['db_user'];
	$db_pass = $_SESSION['db_pass'];
	$db_table = $_SESSION['db_table'];
	
	$table_variablename_time = $_SESSION['table_variablename_time'];
	$table_variablename_value = $_SESSION['table_variablename_value'];
	
	$datapointslimit = $_SESSION['datapointslimit'];
	
	$userandomdata = $_SESSION['userandomdata'];
		
	$t_frame = $_SESSION['t_frame'];
	$startdate = $_SESSION['startdate'];
	
	$startdate = $startdate . " 00:00:00";
	
	$rand_min = $_SESSION['rand_min'];
	$rand_max = $_SESSION['rand_max'];
	
	$rand_change = $_SESSION['rand_change'];
	
	$rand_scale = $_SESSION['rand_scale'];
	
	
	
	$counter = 0;
	$counter_2 = 0;
	$counter_3 = 0;
	
	$values = array();
	
	$rows = array();
	
	if ( $userandomdata ) {
			
		$year = substr($startdate, -19, 4);
		$month = substr($startdate, -14, 2);
		$day = substr($startdate, -11, 2);
		$hour = substr($startdate, -8, 2);
		$minute = substr($startdate, -5, 2);
		$second = substr($startdate, -2, 2);
	
		$converteddatetime = gmmktime((float)$hour, (float)$minute, (float)$second, (float)$month, (float)$day, (float)$year) * 1000;
		
		$value = rand($rand_min * $rand_scale, $rand_max * $rand_scale);
		
		while ( $counter_2 < $datapointslimit ) {
			
			$values[$counter][] = $converteddatetime;
				
			$rawdatetime = gmdate("Y-m-d H:i:s", $converteddatetime / 1000);
				$year = substr($rawdatetime, -19, 4);
				$month = substr($rawdatetime, -14, 2);
				$day = substr($rawdatetime, -11, 2);
				$hour = substr($rawdatetime, -8, 2);
				$minute = substr($rawdatetime, -5, 2);
				$second = substr($rawdatetime, -2, 2);
				
			$converteddatetime = gmmktime((float)$hour, (float)$minute, (float)$second + 5, (float)$month, (float)$day, (float)$year) * 1000;
			
			$rawrandomvalue = mt_rand(0, 100);
			
			if ( $rawrandomvalue < 20 ) {
				
				$value = $value - $rand_change * $rand_scale;
				
			}
			
			else if ( $rawrandomvalue > 80 ) {
				
				$value = $value + $rand_change * $rand_scale;
			
			}
			
			if ( $value > ( $rand_max * $rand_scale ) ) {
				$value = $rand_max * $rand_scale;
			}
			
			if ( $value < ( $rand_min * $rand_scale ) ) {
				$value = $rand_min * $rand_scale;
			}
			
			$values[$counter][] = $value / $rand_scale;
			
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
			
			$counter = $counter + 1;
			
		}
		
	}
	
	else {

		$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

		if ( !$conn ) {
			
			die('Could not connect: ' . mysqli_error());
		
		}

		mysqli_select_db($conn, $db_name);

		$query = mysqli_query($conn ,"SELECT * FROM $db_table WHERE ($table_variablename_time >= '$startdate') ");

		while ( $r = mysqli_fetch_array($query) ) {

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
	
	}
	
	$echo_string = json_encode($rows, JSON_NUMERIC_CHECK);
		
	echo $echo_string;
	
?>