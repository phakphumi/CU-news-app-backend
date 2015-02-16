<?PHP
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
	$conn->set_charset("utf8");
if($_POST){
	//do below to check who is moderator COMMENT JOE//
	$tmp = $_POST["editor"];
	$sql = "SELECT m_id,username FROM moderator";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		if($tmp == $row["username"]) $tmp_mod = $row["m_id"];
	}



	//do below if user edits post COMMENT JOE//
	if(($_POST["newsTS"]!=""||$_POST["newsTS"]!=null)&&($_POST["postTS"]!=""||$_POST["postTS"]!=null)){
		if(empty($_FILES["fileToUpload"]["name"])){ //do this if file was not uploaded COMMENT JOE//
			$temp=$_POST;

			//below this do for delete object newsTS and postTS because it is not to be use COMMENT JOE//
			unset($temp['newsTS']);
			unset($temp['postTS']);

			$now = time()+(7*60*60); // get timestamp form 0.00 GMT and increase 7 hour to +7.00 GMT
			
			$data=json_encode($temp);

			$tmp_dateNews=$_POST["newsTS"];
			$tmp_datePost=$_POST["postTS"];

			/*
			//split timestamp to arrange date format that suitable for sql COMMENT JOE// 
			$dateNews=$_POST["newsTS"];
			list($yearNews, $monthNews, $dayNews, $hourNews, $minuteNews, $secondNews) = split('[/ :]', $dateNews); 
			//The variables should be arranged according to your date format and so the separators
			$tmp_dateNews = $yearNews . $monthNews . $dayNews . $hourNews . $minuteNews . $secondNews;
			$datePost=$_POST["postTS"];
			list($yearPost, $monthPost, $dayPost, $hourPost, $minutePost, $secondPost) = split('[/ :]', $datePost); 
			$tmp_datePost = $yearPost . $monthPost . $dayPost . $hourPost . $minutePost . $secondPost;*/


			$sql="INSERT INTO news (postTS,newsTS,data,m_id) VALUES ($now,$tmp_dateNews,'".$conn->real_escape_string($data)."',$tmp_mod)";
			if ($conn->query($sql) === TRUE) {
				//INSERT modID OldPostID newPostID to mod_log COMMENT KAME//
				$sql = "INSERT INTO mod_log (m_id,o_postTS,postTS) VALUES ($tmp_mod,$tmp_datePost,$now)";
				$conn->query($sql);
				$conn->query("UPDATE news SET del=1 WHERE postTS=$tmp_datePost"); //delete old post COMMENT JOE//
    			echo "New record created successfully<br>";
			} else {
			    echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else { //do this if file has upload COMMENT JOE//
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			// Check if image file is a actual image or fake image
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        $uploadOk = 1;
		        // Check if file already exists
				if (file_exists($target_file)) {
				    $uploadOk = 10;
				}
				// Check file size
				if ($_FILES["fileToUpload"]["size"] > 300000) {
				    $uploadOk = 20;
				}
				// Check file type
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
				    $uploadOk = 30;
				}
		    } else {
		        $uploadOk = 0;
		    }
			
			// Check if $uploadOk is set to 0,10,20,30 by an error
		 	switch($uploadOk){
		 		case 0:
		 			echo "File is not an image.<br>";
		 			echo "Your file and data were not uploaded.<br>";
		 			break;
		 		case 10:
		 			echo "Sorry, file's name already exists. Please rename your image file.<br>";
		 			echo "Your file and data were not uploaded.<br>";
		 			break;
	 			case 20:
		 			echo "Sorry, your image file is larger than 300kb.<br>";
		 			echo "Your file and data were not uploaded.<br>";
		 			break;
	 			case 30:
		 			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
		 			echo "Your file and data were not uploaded.<br>";
		 			break;
				// if everything is ok, try to upload file
				 default :
				    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
				        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
				    } else {
				    	$uploadOk==0;
				        echo "Sorry, there was an error uploading your file. Please send it again.<br>";
			    }
			}
			
			if($uploadOk==1){ // if image upload completed insert all data into database COMMENT JOE//
				$temp=$_POST;

				//below this do for delete object newsTS and postTS because it is not to be use COMMENT JOE//
				unset($temp['newsTS']);
				unset($temp['postTS']);

				$now = time()+(7*60*60); // get timestamp form 0.00 GMT and increase 7 hour to +7.00 GMT
				
				$temp["imageUrl"]=$target_file;
				$data=json_encode($temp);

				$tmp_dateNews=$_POST["newsTS"];
				$tmp_datePost=$_POST["postTS"];

				/*
				//split timestamp to arrange date format that suitable for sql COMMENT JOE// 
				$dateNews=$_POST["newsTS"];
				list($yearNews, $monthNews, $dayNews, $hourNews, $minuteNews, $secondNews) = split('[/ :]', $dateNews); 
				//The variables should be arranged according to your date format and so the separators
				$tmp_dateNews = $yearNews . $monthNews . $dayNews . $hourNews . $minuteNews . $secondNews;
				$datePost=$_POST["postTS"];
				list($yearPost, $monthPost, $dayPost, $hourPost, $minutePost, $secondPost) = split('[/ :]', $datePost); 
				$tmp_datePost = $yearPost . $monthPost . $dayPost . $hourPost . $minutePost . $secondPost;*/

				$sql="INSERT INTO news (postTS,newsTS,data,m_id) VALUES ($now,$tmp_dateNews,'".$conn->real_escape_string($data)."',$tmp_mod)";
				if ($conn->query($sql) === TRUE) {
					//INSERT modID OldPostID newPostID to mod_log COMMENT KAME
					$sql = "INSERT INTO mod_log (m_id,o_postTS,postTS) VALUES ($tmp_mod,$tmp_datePost,$now)";
					$conn->query($sql);
					$conn->query("UPDATE news SET del=1 WHERE postTS=$tmp_datePost"); //delete old post COMMENT JOE//
		    		echo "New record created successfully<br>";
				} else {
				    echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	} else { //do below if user posts new topic COMMENT JOE//
		if(empty($_FILES["fileToUpload"]["name"])){ //do this if file was not uploaded COMMENT JOE//
			$temp=$_POST;

			//below this do for delete object newsTS and postTS because it is not to be use COMMENT JOE//
			unset($temp['newsTS']);
			unset($temp['postTS']);

			$now = time()+(7*60*60); // get timestamp form 0.00 GMT and increase 7 hour to +7.00 GMT
			
			$data=json_encode($temp);
			$sql="INSERT INTO news (postTS,newsTS,data,m_id) VALUES ($now,$now,'".$conn->real_escape_string($data)."',$tmp_mod)";
			if ($conn->query($sql) === TRUE) {
				//INSERT modID OldPostID newPostID to mod_log COMMENT KAME//
				$sql = "INSERT INTO mod_log (m_id,o_postTS,postTS) VALUES ($tmp_mod,0,$now)";
				$conn->query($sql);
    			echo "New record created successfully<br>";
			} else {
			    echo "Error: " . $sql . "<br>" . $conn->error;
			}
		} else { //do this if file has upload COMMENT JOE//
			$target_dir = "uploads/";
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

			// Check if image file is a actual image or fake image
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        $uploadOk = 1;
		        // Check if file already exists
				if (file_exists($target_file)) {
				    $uploadOk = 10;
				}
				// Check file size
				if ($_FILES["fileToUpload"]["size"] > 1000000) {
				    $uploadOk = 20;
				}
				// Check file type
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
				&& $imageFileType != "gif" ) {
				    $uploadOk = 30;
				}
		    } else {
		        $uploadOk = 0;
		    }
			
			// Check if $uploadOk is set to 0,10,20,30 by an error
			if ($uploadOk == 0||$uploadOk == 10||$uploadOk == 20||$uploadOk == 30) {
			 	switch($uploadOk){
			 		case 0:
			 			echo "File is not an image.<br>";
			 			break;
			 		case 10:
			 			echo "Sorry, file's name already exists. Please rename your image file.<br>";
			 			break;
		 			case 20:
			 			echo "Sorry, your image file is too large.<br>";
			 			break;
		 			case 30:
			 			echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
			 			break;
			 	}
			 	echo "Your file and data were not uploaded.<br>";

			// if everything is ok, try to upload file
			} else {
			    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";
			    } else {
			    	$uploadOk==0;
			        echo "Sorry, there was an error uploading your file. Please send it again.<br>";
			    }
			}
			
			if($uploadOk==1){ // if image upload completed insert all data into database COMMENT JOE//
				$temp=$_POST;

				//below this do for delete object newsTS and postTS because it is not to be use COMMENT JOE//
				unset($temp['newsTS']);
				unset($temp['postTS']);

				$now = time()+(7*60*60); // get timestamp form 0.00 GMT and increase 7 hour to +7.00 GMT
				
				$temp["imageUrl"]=$target_file;
				$data=json_encode($temp);
				$sql="INSERT INTO news (postTS,newsTS,data,m_id) VALUES ($now,$now,'".$conn->real_escape_string($data)."',$tmp_mod)";
				if ($conn->query($sql) === TRUE) {
					//INSERT modID OldPostID newPostID to mod_log COMMENT KAME
					$sql = "INSERT INTO mod_log (m_id,o_postTS,postTS) VALUES ($tmp_mod,0,$now)";
					$conn->query($sql);
		    		echo "New record created successfully<br>";
				} else {
				    echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	}
}elseif($_GET){
	$dataDel = $_GET['postTS'];

	$sql="UPDATE news SET del=1 WHERE postTS = $dataDel";
		if ($conn->query($sql) === TRUE) {
			echo "Record deleted successfully<br>";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	

}

	$conn->close();
?>