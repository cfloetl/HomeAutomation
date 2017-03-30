<?php
	error_reporting(E_ALL);

	//Connect to database
	try 
	{
		$conn = new PDO("mysql:host=localhost;dbname=HOME", "WebControl", "WebControler1234");
	}
	catch(PDOException $e) 
	{
		echo $e->getMessage();
	}

	if (isset($_GET['RelayId']) and isset($_GET['RelayValue']))
	{
		$RelayId = $_GET['RelayId'];
		$RelayValue = $_GET['RelayValue'];

		if (true)
		{
			$output = '{"Relay":[{"Id":"' . $RelayId . '", "State":' . $RelayValue . '}]}';

			$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die(":(");

			socket_connect($socket, 'localhost', '2453');

			socket_write($socket, $output, strlen($output)) or die("Server not responding.");

			$buf = '';
			if (false !== ($bytes = socket_recv($socket, $buf, 1024, MSG_WAITALL))) 
			{
			} 
			socket_close($socket);
		}
		else
		{
			$RelayValueBool = ($RelayValue == '1'? true: false);
			$Command = $conn->prepare("CALL HOME.Relay_LogState(:RelayId, :RelayValue);");
			$Command->bindParam(':RelayId', $RelayId);
			$Command->bindParam(':RelayValue', $RelayValueBool);
			$Command->execute();
		}
	}

    $data = $conn->query('CALL HOME.Relay_GetRelays();');

	$json = '{"Relay":[';
	
	$First = true;
    foreach($data as $row) 
	{
		$json .= ($First? '': ',') . '{"Id":"' . $row['RELAY_ID'] . '", "Name":"' . $row['NAME'] . '", "State":"' . $row['STATE'] . '"} ';
		$First = false;
    }
	$json .= ']}';
	echo $json;
?>