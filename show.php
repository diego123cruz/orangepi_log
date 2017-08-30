<?php
    function getRealTime()
    {
	$link = mysqli_connect("localhost", "root", "root", "zurctechbr");

	// Check connection
        if($link === false){
        	die("ERROR: Could not connect. " . mysqli_connect_error());
    	}

	$sql = "SELECT * FROM log WHERE id = (SELECT MAX(id) FROM log)";

    	$result = mysqli_query($link, $sql);
        $fetch = mysqli_fetch_row($result); 

	mysqli_close($link);

        return $fetch;
     }

    function getGrafico()
    {
        $link = mysqli_connect("localhost", "root", "root", "zurctechbr");

        // Check connection
        if($link === false){
                die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        $sql = "SELECT CONCAT( EXTRACT(HOUR FROM data), ':', EXTRACT(MINUTE FROM data)) as hora, temperatura, humidade FROM log WHERE EXTRACT(DAY FROM data) = EXTRACT(DAY FROM now())";

        $result = mysqli_query($link, $sql);
        $arrObj = array();
	//$fetch = mysqli_fetch_row($result);
	while($obj = mysqli_fetch_object($result))
	{
		$arrObj[] = $obj;
	}

        mysqli_close($link);

        return $arrObj;
     }

	$rGrafico = getGrafico();

?>


<html>
  <head>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
   <script language="JavaScript" type="text/javascript" src="/js/jquery-3.2.1.min.js"></script> 

  <script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

	<?php
                $data = getRealTime();
          ?>

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Temp.', <?php echo $data[2]?>],
          ['Humi.', <?php echo $data[3]?>],
        ]);

        var options = {
          width: 400, height: 120,
          redFrom: 90, redTo: 100,
          yellowFrom:75, yellowTo: 90,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);

        setInterval(function() {
	  <?php
	 	$data = getRealTime();
	  ?>
          data.setValue(0, 1, <?php echo $data[2] ?>);
          data.setValue(1, 1, <?php echo $data[3]?>);
	  chart.draw(data, options);
        }, 600000);
      }


	setInterval(function(){
		<?php $rGrafico = getGrafico(); ?>
	}, 600000);

	//segundo grafico
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart2);

      function drawChart2() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Humidade', 'Temperatura'],
	<?php
		foreach($rGrafico as $r){
			echo "['".$r->hora."',".$r->humidade.",".$r->temperatura."],";
		}

	?>
        ]);

        var options = {
          title: 'Info do dia :)',
          hAxis: {title: 'Hora',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }

    </script>
  </head>
  <body>
	<center>
		<h3>Batcaverna - Real Time Sensors</h3> <br>
 
		<div id="chart_div" style="width: 100%; height: 120px;"></div>
		<br>
		<div id="chart_div2" style="width: 100%; height: 500px;"></div>
	</center>
 </body>
</html>
