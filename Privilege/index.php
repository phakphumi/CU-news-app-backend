<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="./jquery.datetimepicker.css"/>
<script>
		function validateForm() {
		var xi = document.forms["myForm"]["fileToUpload"].value;
	    var xs = document.forms["myForm"]["shopName"].value;
	    var xm = document.forms["myForm"]["message"].value;
	    var xe = document.forms["myForm"]["editor"].value;
	    var xsv = document.forms["myForm"]["startPrivilege"].value;
	    var xev = document.forms["myForm"]["endPrivilege"].value;
	    if ((xi == null || xi=="")||(xs==null || xs=="")||(xm==null || xm=="")||(xe==null || xe=="")||(xsv==null || xsv=="")||(xev==null || xev=="")) {
	        alert("Image, Shop Name, Message, Start privilegem End privilege and Post by must be filled out");
	        return false;
	    }
	}
</script>
</head>
<body>
	<table id="CurrentNewsTable"></table>
	<br>
	<form name="myForm" action="input_privilege.php" onsubmit="return validateForm()" method="POST" enctype="multipart/form-data">
		<h2>This form use to add privilege</h2>
		Select image to upload:
	    <input type="file" name="fileToUpload" id="fileToUpload"><br>
		Shop name:<br>
		<input type="topic" name="shopName" size="50" id="sshopName">
		<br>
		Message:<br>
		<textarea name="message" id="mmessage" style="width: 500px; min-height: 50px; font-family: Arial, sans-serif; font-size: 13px;"></textarea><br>
		
		Start Privilege Date
		<input type="text" name="startPrivilege" id="datetimepicker_start"/><br><br>
		End Privilege Date
		<input type="text" name="endPrivilege" id="datetimepicker_end"/><br><br>
		Post By<br>
		<select name="editor" id="eeditor">
		<option></option>
		<option value="obj">อบจ</option>
		</select>
		<br><br>
		<input type="submit" value="Submit" class="submit">
		<button type="reset" value="Reset" class="clear">Clear</button>
	</form>
	<br>
</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="./jquery.datetimepicker.js"></script>
<script>
$('#datetimepicker_start').datetimepicker({});
$('#datetimepicker_end').datetimepicker({});
</script>
</html>
