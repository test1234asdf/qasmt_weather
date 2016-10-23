
<html>
    <head>
				<!-- dependent on Internet
				<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
        <script src="http://code.highcharts.com/maps/highmaps.js"></script>
				-->
				<script src="js/jquery-3.1.0.js"></script>
        <script src="js/highmaps-5.0.js"></script>
				<script src="js/highmaps-exporting-5.0.js"></script>
    </head>
    <body>
    <?php  
      //$dbhost = '10.41.68.102';
      //$dbuser = 'cdeod1';
			//$dbpass = 'cdeod1';
	$dbhost = 'localhost';
			$dbuser = 'root';
			$dbpass= '';
		
			$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
      if (mysqli_connect_errno()) {
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	  }
			mysqli_select_db($conn, 'weatherdata');
			
      //j01
			$sql = "SELECT AverageTemp FROM `J01` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j01 = "$AverageTemp";
			};
			
      //j02
			$sql = "SELECT AverageTemp FROM `J02` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j02 = "$AverageTemp";
			};
			
      //j03
			$sql = "SELECT AverageTemp FROM `J03` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j03 = "$AverageTemp";
			};
			
      //j04
			$sql = "SELECT AverageTemp FROM `J04` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j04 = "$AverageTemp";
			};
			
      //j05
			$sql = "SELECT AverageTemp FROM `J05` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j05 = "$AverageTemp";
			};
			
      //j06
			$sql = "SELECT AverageTemp FROM `J06` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j06 = "$AverageTemp";
			};
			
      //j07
			$sql = "SELECT AverageTemp FROM `J07` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j07 = "$AverageTemp";
			};
			
      //j08
			$sql = "SELECT AverageTemp FROM `J08` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$j08 = "$AverageTemp";
			};
			
      //Senior Study
			$sql = "SELECT AverageTemp FROM `SeniorStudy` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$ss = "$AverageTemp";
			};
			
      //Grp 2
			$sql = "SELECT AverageTemp FROM `Group_2_Staffroom` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$grp2 = "$AverageTemp";
			};
			
      //Grp 1 & 3
			$sql = "SELECT AverageTemp FROM `Group_1_&_3_Staffroom` ORDER BY CurrentTime ASC"; //order by currenttime asc
			$result = mysqli_query($conn, $sql);
			if(!$result) {
				die('Could not get data ' . mysql_error());
			}
			while( list($AverageTemp) = mysqli_fetch_row($result) ) {
				$grp13 = "$AverageTemp";
			};

		?>
		
		<div id="container" style="height: 640px; min-width: 640px; max-width: 960px; margin: 0 auto"></div>

<script>

$(function() {

  // Initiate the chart
  $('#container').highcharts('Map', {
    title: {
      "text": "J Block Temperatures (℃)"
    },
    mapNavigation: {
      enabled: true
    },
    navigation: {
      buttonOptions: {
        enabled: true
      }
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
		
		plotOptions: {
			columnrange: {
				dataLabels: {
					enabled: true,
					color: 'orange',
					style: {
					                      textShadow: false 
					                    },
				}
			}
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
      name: "Temperature",
      type: "map",
      states: {
          hover: {
              color: '#bbcfe3'
          }
      },
			tooltip: {
				valueSuffix: '℃',
			},
      data: [{
        "name": "Group 2<br>Staff Room",
        "value": <?php echo $grp2 ?>,
				"path": "M6,-495L234,-495,234,-398,6,-398,6,-495"
      }, {
        "name": "J08",
        "value": <?php echo $j08 ?>,
				"path": "M242,-487L490,-487,490,-398,242,-398,242,-487"
      }, {
       	"name": "J07",
				"value": <?php echo $j07 ?>,
				"path": "M498,-544L760,-544,760,-398,498,-398,498,-544"
      }, {
        "name": "J06",
        "value": <?php echo $j06 ?>,
				"path": "M847,-784L996,-784,996,-604,847,-604,847,-784"

      }, {
        "name": "J05",
        "value": <?php echo $j05 ?>,
				"path": "M855,-995L994,-995,994,-792,855,-792,855,-995"

      }, {
        "name": "J04",
	      "value": <?php echo $j04 ?>,
				"path": "M653,-995L849,-995,849,-793,653,-793,653,-995"
	     
      }, {
        "name": "J03",
        "value": <?php echo $j03 ?>,
				"path": "M477,-995L647,-995,647,-792,477,-792,477,-995"
        
      }, {
        "name": "J02",
				"value": <?php echo $j02 ?>,
				"path": "M174,-997L343,-997,343,-795,174,-795,174,-997"
			
      }, {
        "name": "J01",
        "value": <?php echo $j01 ?>,
				"path": "M0,-997L169,-997,169,-795,0,-795,0,-997"
     
      }, {
				"name": "Group 1 & 3 Staffroom",
				"value": <?php echo $grp13 ?>,
				"path": "M391,-759L727,-759,727,-611,391,-611,391,-759"
			},{
        "name": "Senior Study",
        "value": <?php echo $ss ?>,
				"path": "M767,-596L1000,-596,1000,-398,767,-398,767,-596"
       
      }]
    }]
  });
});

</script>


</body>
</html>