<?php
	$servername = "localhost";
	$username = "tanwebsi_thinc";
	$password = "hahaha";
	$dbname = "tanwebsi_thinc";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$LastSyncTime = $_GET["LastSyncTime"];
	//list($year, $month, $day, $hour, $minute, $second) = split('[/ :]', $LastSyncTime); 
	//$LastSyncTime = $year . $month . $day . $hour . $minute . $second;

	if($LastSyncTime==0){
	$sql = "SELECT newsTS,postTS,data FROM privilege WHERE del = 0 ORDER BY u_id desc";
	$result = $conn->query($sql);

	} else {
		//list($year, $month, $day, $hour, $minute, $second) = split('[/ :]', $LastSyncTime); 
		//$LastSyncTime = $year . $month . $day . $hour . $minute . $second;
		/*$time = strtotime($LastSyncTime);
		echo $time;
		$time = strtotime("2015-01-22 21:00:00");
		echo $time;
		$time = strtotime("2015-01-24 21:41:06");
		echo $time;
		echo gmdate("Y-m-d H:i:s", $time);
		echo date("Y-m-d H:i:s");
		$time = time();
		echo $time;
		echo gmdate("Y-m-d H:i:s", time()+(7*60*60));
		*/
		//$sql = "SELECT postTS FROM news";
		//$result = $conn->query($sql);
		//echo $result;

		$sql = "SELECT newsTS,postTS,data FROM privilege WHERE postTS > $LastSyncTime,del=0 ORDER BY u_id desc";
		$result = $conn->query($sql);
	}
    //$stmt = $conn->prepare("SELECT newsTS,postTS,data FROM news WHERE `delete` = 0 ORDER BY u_id desc");
    //$stmt -> execute();
    //$stmt->bind_result($result);

	if ($result->num_rows > 0) {
	$toSend = array();
	$c = 0;
    while($temp = $result->fetch_assoc()){
      	$toSend[$c] = json_decode($temp["data"],true);
      	$dateTemp = $temp['postTS'];
      	$toSend[$c]['postTS'] = $dateTemp;
      	$dateTemp = $temp['newsTS'];
      	$toSend[$c]['newsTS'] = $dateTemp;
      	/*$dateTemp = date_create($temp["postTS"]);
    	$toSend[$c]["postTS"] = array();
    	$toSend[$c]["postTS"]["date"] =  date_format($dateTemp,"Y/m/d");
    	$toSend[$c]["postTS"]["time"] =  date_format($dateTemp,"H:i:s");
    	$dateTemp = date_create($temp["newsTS"]);
    	$toSend[$c]["newsTS"] = array();
    	$toSend[$c]["newsTS"]["date"] =  date_format($dateTemp,"Y/m/d");
    	$toSend[$c]["newsTS"]["time"] =  date_format($dateTemp,"H:i:s");*/
    	$c++;
    }
    $toEncode["toAdd"] = $toSend;
    //json_encode -> obj -> string
    //json_decodde -> string -> obj

    echo json_encode($toEncode);
} else {
    echo "0 results";
}

	$conn->close();
?>