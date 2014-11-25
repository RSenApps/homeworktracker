<?php
	session_start();
	require("config.php");
	//error handler function
	function customError($errno, $errstr) {
	  echo "Please try again later.";
	}

	//set error handler
	set_error_handler("customError");
	// Create connection
	$conn = new mysqli($GLOBALS["hostname"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["database"]);

	// Check connection
	if ($conn->connect_error) {
		die("Please try again later.");
	} 
	
	//check if user already exists
	$username = $_POST['username'];
	$password = $_POST['password'];
	$escapedusername = mysqli_real_escape_string($conn, $username);
	$escapedpassword = mysqli_real_escape_string($conn, $password);
	$sql = "SELECT * FROM senanayake_users WHERE username = '" . $escapedusername . "'"; 
	$result = mysqli_query($conn, $sql);
	$results = "";
	if (mysqli_num_rows($result) > 0) {
		die("Username already taken");
	}
	$result = mysqli_query($conn, "INSERT INTO senanayake_users (username, password) VALUES ('" . $escapedusername . "', '" . $escapedpassword . "')");
	if($result)
	{
		session_regenerate_id();
		$_SESSION['sess_userid'] = mysqli_insert_id($conn);
		session_write_close();
		echo "success";
	}
	else {
		echo "Please try again later.";
	}
?>