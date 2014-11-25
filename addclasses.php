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
	$sql = 'INSERT INTO senanayake_classes (user_id, name, monday, tuesday, wednesday, thursday, friday) VALUES';
	$row = 0;

	while (isset($_POST[$row]))
	{
		$data = explode('|', $_POST[$row]);
		$userid = mysqli_real_escape_string($conn, $_SESSION['sess_userid']);
		$name = mysqli_real_escape_string($conn, $data[0]);
		$monday = mysqli_real_escape_string($conn, $data[1]);
		$tuesday = mysqli_real_escape_string($conn, $data[2]);
		$wednesday = mysqli_real_escape_string($conn, $data[3]);
		$thursday = mysqli_real_escape_string($conn, $data[4]);
		$friday = mysqli_real_escape_string($conn, $data[5]);
		$sql .= " ('$userid', '$name', '$monday', '$tuesday', '$wednesday', '$thursday', '$friday'),";	
		$row++;
	}
	$sql = rtrim($sql, ",");
	$sql .= ";";
	$result = mysqli_query($conn, $sql);
	
	if($result)
	{
		echo "success";
	}
	else {
		echo "failed";
	}			
?>