<?php
	session_start();
	if(!isset($_SESSION['sess_userid']) || (trim($_SESSION['sess_userid']) == '')) {
		header("location: login.html");
		exit();
	}
	require("config.php");
	//error handler function
	function customError($errno, $errstr) {
		die("failed");
	}
				
	//set error handler
	//set_error_handler("customError");
	// Create connection
	$conn = new mysqli($GLOBALS["hostname"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["database"]);

	// Check connection
	if ($conn->connect_error) {
		die("Please try again later.");
	} 
	$sql = 'INSERT INTO senanayake_assignments (classes_id, due_date, summary, description, progress) VALUES';
	$row = 0;

	$classesid = mysqli_real_escape_string($conn, $_POST['clsid']);
	$dayOfWeek = mysqli_real_escape_string($conn,$_POST['day']);
	$wkOffset = mysqli_real_escape_string($conn,$_POST['wkOffset']);
	$summary = mysqli_real_escape_string($conn, $_POST['summary']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$progress = mysqli_real_escape_string($conn, $_POST['progress']);
	$duedate = date('Y-m-d',time()+( ($dayOfWeek+1 + 7*$wkOffset) - date('w'))*24*3600);
	$sql .= " ('$classesid', '$duedate', '$summary', '$description', '$progress');";	
	
	$result = mysqli_query($conn, $sql);
	echo mysqli_error($conn);
	if($result)
	{
		echo "success";
	}
	else {
		echo "failed";
	}			
?>