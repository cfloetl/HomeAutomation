.
<?php

	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	$f = popen("python /home/pi/sensors.py", "r");

	if ($f === false)
	{
		echo "popen failed";
	}


	$json = fgets($f);
	fclose($f);
	echo " - $json - ";


	$con=mysqli_connect("localhost","DataLogger","ILoveLog","HOUSE");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$sql = "SELECT LOG_DATE, TEMP, HUMIDITY FROM HOUSE.SENSOR_LOG;";

	$result = mysqli_query($con, $sql);

	echo 
		"<table border='1px'>".
		"<tr>".
		"<th>Time</th>".
		"<th>Temp F</th>".
		"<th>Humidity</th>".
		"</tr>";


	while($row = mysqli_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['LOG_DATE'] . "</td><td> " . $row['TEMP'] . "</td><td>" . $row['HUMIDITY'] . "</td>";
		echo "</tr>";
	}

	echo "</table>";

	mysqli_close($con);
?>

