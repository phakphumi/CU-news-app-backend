<html>
<head>
<meta charset="UTF-8">
<style>
table {
    border-collapse: collapse;
    table-layout: fixed;
    width: 100%;
}

table, td, th {
    border: 1px solid black;
}
td {
	text-align: center;
	word-wrap: break-word;
}
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$.get("/thinc/output.php?LastSyncTime=0",function(data,status){
			if(status=="success"){
				tableData = JSON.parse(data);
				$("#CurrentNewsTable").html( function(){
					result = "";
					function row(input){
						return "<tr>"+input+"</tr>";
					}

					function column_hidden(classname,input){
						return "<td class=\""+classname+"\" hidden>"+input+"</td>";
					}
					function column_date(input){
						return "<td>"+timeConverter(input)+"</td>";
					}
					function column(classname,input){
						return "<td class=\""+classname+"\">"+input+"</td>";
					}
					function edit(){
						return "<td class='edit'><button type='button'>edit</button></td>"
					}
					function del(){
						return "<td class='del'><button type='button'>delete</button></td>"
					}
					function timeConverter(UNIX_timestamp){
					  var a = new Date(UNIX_timestamp*1000-(7*60*60*1000));
					  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
					  var year = a.getFullYear();
					  var month = months[a.getMonth()];
					  var date = a.getDate();
					  var hour = a.getHours() < 10 ? hour = "0" + a.getHours() : hour = a.getHours();
					  var min = a.getMinutes() < 10 ? min = "0" + a.getMinutes() : min = a.getMinutes();
					  var sec = a.getSeconds() < 10 ? sec = "0" + a.getSeconds() : sec = a.getSeconds();
					  
					  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
					  return time;
					}
					result += "<thead>"+column("hcol","Create Time")+column("hcol","Last Updated")+column("hcol","title")
							+column("hcol","message")+column("hcol","editor")+column("hcol","edit")+column("hcol","delete")+
							"</thead>";
					result += "<tbody>";
					console.log(tableData);
					for(var c=0;c<tableData["toAdd"].length;c++){

						result += row(column_date(tableData["toAdd"][c]["newsTS"])
							+column_date(tableData["toAdd"][c]["postTS"])+column("dataTitle",tableData["toAdd"][c]["title"])
							+column("dataMessage",tableData["toAdd"][c]["message"])
							+column("dataEditor",tableData["toAdd"][c]["editor"])+edit()+del()
							+column_hidden("dataNews",tableData["toAdd"][c]["newsTS"])
							+column_hidden("dataPost",tableData["toAdd"][c]["postTS"]));
					}
					result += "</tbody>";
					return result;
				});
			}
  		});
		$("#CurrentNewsTable").on('click','tbody tr td.edit',function(){
			console.log($(this).parent().children(".dataEditor").html());
			//$("#modx").attr("value",$(this).parent().children(".dataEditor").html());
			$("#TS").css("display", "block");
			$("#nnewsTS").attr("value",$(this).parent().children(".dataNews").html());
			$("#ppostTS").attr("value",$(this).parent().children(".dataPost").html());
			$("#ttitle").attr("value",$(this).parent().children(".dataTitle").html());
			$("#mmessage").val($(this).parent().children(".dataMessage").html());
			//console.log($(this).parent().children(".dataEditor").html());
			$("#eeditor").val($(this).parent().children(".dataEditor").html());
			//$("#eeeditor").removeAttr("hidden");
			//$("#eeeditor").removeAttr("disabled");
			//$("#eeeditor").prop('readonly', 'readonly');
			//$("#eeeditor").attr("disabled");
			$('#eeditor').attr('disabled', true);

		});
		$("#CurrentNewsTable").on('click','tbody tr td.del',function(){
		    if (confirm("Are you sure to delete topic "+$(this).parent().children(".dataTitle").html()) == true) {
		        $dataDel = $(this).parent().children(".dataPost").html();
		        function newDoc() {
				    window.location.assign("input_to_db.php?postTS="+$dataDel);
				}
				newDoc();
		    } else {
		    }
	   
	
			//$postDel = $(this).parent().children(".dataPost").html();
			//$TitleDel = $(this).parent().children(".dataTitle").html();

		});
		$(".clear").click(function(){
			$("#TS").css("display","none");
			$("#nnewsTS").attr("value","");
			$("#ppostTS").attr("value","");
			$("#ttitle").attr("value","");
			$("#mmessage").attr("value","");
			//console.log($(this).parent().children(".dataEditor").html());
			$("#eeditor").val("");
			$('#eeditor').attr('disabled', false);
		});
		$(".submit").click(function(){
		    $('#eeditor').attr('disabled', false);
		});
		
	}); 
	function validateForm() {
	    var xt = document.forms["myForm"]["title"].value;
	    var xm = document.forms["myForm"]["message"].value;
	    var xe = document.forms["myForm"]["editor"].value;
	    if ((xt==null || xt=="")&&(xm==null || xm=="")&&(xe==null || xe=="")) {
	        alert("Title, Message and Post by must be filled out");
	        return false;
	    }else if ((xm==null || xm=="")&&(xe==null || xe=="")) {
	        alert("Message and Post by must be filled out");
	        return false;
        }else if ((xt==null || xt=="")&&(xe==null || xe=="")) {
	        alert("Title and Post by must be filled out");
	        return false;
        }else if ((xt==null || xt=="")&&(xm==null || xm=="")) {
	        alert("Title and Message must be filled out");
	        return false;
        }else if ((xt==null || xt=="")) {
	        alert("Title must be filled out");
	        return false;
        }else if ((xm==null || xm=="")) {
	        alert("Message must be filled out");
	        return false;
        }else if ((xe==null || xe=="")) {
	        alert("Post by must be filled out");
	        return false;
        }
	}
	
</script>
</head>
<body>
	<table id="CurrentNewsTable"></table>
	<br>
	<form name="myForm" action="input_to_db.php" onsubmit="return validateForm()" method="POST" enctype="multipart/form-data">
		<h2>This form use to add news</h2>
		<div id="TS" style="display: none;"><h3 style="color:red;">Use to edit only. if you don't want to do this please click reset button</h3><br>First time posted
		<input type="text" name="newsTS" id="nnewsTS" readonly><br>
		Last edit
		<input type="text" name="postTS" id="ppostTS" readonly><br><br></div>
		Select image to upload:
	    <input type="file" name="fileToUpload" id="fileToUpload"><br>
		Title:<br>
		<input type="topic" name="title" size="50" id="ttitle">
		<br>
		Message:<br>
		<textarea name="message" id="mmessage" style="width: 500px; min-height: 50px; font-family: Arial, sans-serif; font-size: 13px;"></textarea><br>
		Post By<br>
		<select name="editor" id="eeditor">
		<option></option>
		<option value="obj">อบจ</option>
		<option value="mod1">mod1</option>
		<option value="#">coming soon</option>
		<option value="#">coming soon</option>
		</select>
		<br><br>
		<input type="submit" value="Submit" class="submit">
		<button type="reset" value="Reset" class="clear">Clear</button>
	</form>
	<br>

</body>
</html>