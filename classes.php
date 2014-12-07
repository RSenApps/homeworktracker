<?php
	session_start();
	if(!isset($_SESSION['sess_userid']) || (trim($_SESSION['sess_userid']) == '')) {
		header("location: login.html");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Enter Classes - Homework Tracker</title>
	<!-- 1. Load platform.js for polyfill support. -->
    <script type="text/javascript" src="bower_components/platform/platform.js"></script>
<link rel="icon" 
      type="image/png" 
      href="favicon.png">
    <!-- 2. Use an HTML Import to bring in the element. -->
	 <link rel="import" href="bower_components/font-roboto/roboto.html"/>
    <link rel="import" href="bower_components/paper-elements/paper-elements.html"/>
		<link rel="import" href="bower_components/core-style/core-style.html"/>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="path/to/core-icons/core-icons.html" rel="import">
		<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
<link rel="stylesheet" type="text/css" href="classes.css" />

</head>
<body unresolved>
		<paper-shadow class="classes_card" z="5">
			<h1>What classes do you have homework for?</h1>
		
			<table id="classesTable">
				<tr>
					<th>Name</th>
					<th>M</th>
					<th>T</th>
					<th>W</th>
					<th>T</th>
					<th>F</th>
				</tr>
				
				<?php
				
					//error handler function
					function customError($errno, $errstr) {
						printDefaultLayout();
					}
					function printDefaultLayout() {
						for ($class = 1 ; $class < 9; $class++)
						{
							printRow($class, "", True, True, True, True, True);
						}
					}
					function printRow($class, $name, $monday, $tuesday, $wednesday, $thursday, $friday)
					{
						echo "<tr>";
						echo "<td>";
						echo "<paper-input-decorator label = \"Class {$class}\" floatingLabel>";
						echo "<input id=\"name{$class}\" is=\"core_input\" value=\"{$name}\">{$name}</input>";
						echo "</paper-input-decorator>";
						echo "</td>";
						printCheckbox($class, 0, $monday);
						printCheckbox($class, 1, $tuesday);
						printCheckbox($class, 2, $wednesday);
						printCheckbox($class, 3, $thursday);
						printCheckbox($class, 4, $friday);
						
					}
					function printCheckbox($class, $col, $checked) {
						echo "<td>";
						$str = "<paper-checkbox id=\"class{$class}col{$col}\" ";
						if ($checked)
						{
							$str = $str . " checked";
						}
						echo "{$str}></paper-checkbox>";
						echo "</td>";
					}
					//set error handler
					set_error_handler("customError");
					printDefaultLayout();
					
				?>
			</table>
		
		<div id="buttons">
			<paper-button onclick="doneButton()" raised>Done</paper-button>
			<paper-button onclick="addRow()" raised>Add Class</paper-button>
		</div>
		</paper-shadow>
		<paper-toast id="errorToast" text="An error occurred. Please try again..."></paper-toast>
</body>
<script>
	var table = document.getElementById("classesTable");
	function addRow()
	{
		var rowCount = table.rows.length; //this also includes 1 extra for the header
		//http://stackoverflow.com/questions/171027/add-table-row-in-jquery
		$('#classesTable > tbody:last').append('<tr><td><paper-input-decorator label="Class ' + rowCount + '" floatingLabel><input is="core-input" id="name' + rowCount + '"></input></paper-input-decorator></td><td><paper-checkbox id="class' + rowCount + 'col0" checked></paper-checkbox></td><td><paper-checkbox id="class' + rowCount + 'col1" checked></paper-checkbox></td><td><paper-checkbox id="class' + rowCount + 'col2" checked></paper-checkbox></td><td><paper-checkbox id="class' + rowCount + 'col3" checked></paper-checkbox></td><td><paper-checkbox id="class' + rowCount + 'col4" checked></paper-checkbox></td></tr>');
	}
	function doneButton()
	{
		var rowCount = table.rows.length - 1;//subtract header row
		var data = {};
		var index = 0;
		for (i = 1; i<=rowCount; i++)
		{	
			var rowData = $("#name" + i).val();
			if (rowData.trim().length > 0)
			{
				rowData.replace('|', '');
				
				for (col = 0; col<5; col++)
				{
					rowData += "|";
					if (document.getElementById("class" + i + "col" + col).checked == true)
					{
						rowData += "1";
					}
					else {
						rowData += "0";
					}
				}
				data[index] = rowData;
				index++;
			}
		}
		$.ajax({
			url: "addclasses.php", 
			data: data,
			type: 'post',
			error: function(XMLHttpRequest, textStatus, errorThrown){
				document.getElementById("errorToast").show();
			},
			success: function(returnData){
				if (returnData == "success")
					{
						document.location.href = "index.php";
					}
					else {
						document.getElementById("errorToast").show();
					}
			}
		});
		
	}
</script>