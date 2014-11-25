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

	$classesid = mysqli_real_escape_string($conn, $_POST['classes_id']);
	$duedate = mysqli_real_escape_string($conn,$_POST['due_date']);
	$summary = mysqli_real_escape_string($conn, $_POST['summary']);
	$description = mysqli_real_escape_string($conn, $_POST['description']);
	$progress = mysqli_real_escape_string($conn, $_POST['progress']);
	$sql .= " ('$classesid', '$duedate', '$summary', '$description', '$progress');";	
	
	$result = mysqli_query($conn, $sql);
	
	if($result)
	{
		echo "success";
	}
	else {
		echo "failed";
	}			
?>