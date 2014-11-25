<?php
	session_start();
	if(!isset($_SESSION['sess_userid']) || (trim($_SESSION['sess_userid']) == '')) {
		header("location: login.html");
		exit();
	}
	function customError($errno, $errstr) {
	  echo "Please try again later.";
	}

	//set error handler
	//set_error_handler("customError");
	// Create connection
	require("config.php");
	$conn = new mysqli($GLOBALS["hostname"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["database"]);

	// Check connection
	if ($conn->connect_error) {
		die("Please try again later.");
	} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Homework Tracker</title>
	<link rel="shortcut icon" href="favicon.ico">
	<!-- 1. Load platform.js for polyfill support. -->
    <script type="text/javascript" src="bower_components/platform/platform.js"></script>

    <!-- 2. Use an HTML Import to bring in the element. -->
	 <link rel="import" href="bower_components/font-roboto/roboto.html"/>
    <link rel="import" href="bower_components/paper-elements/paper-elements.html"/>
		<link rel="import" href="bower_components/core-style/core-style.html"/>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="import" href="bower_components/core-scroll-header-panel/core-scroll-header-panel.html"/>
<link rel="import" href="bower_components/core-toolbar/core-toolbar.html"/>
<link rel="stylesheet" type="text/css" href="index.css" />
<link href="bower_components/paper-dialog/paper-dialog.html" rel="import">
  <link href="bower_components/paper-dialog/paper-action-dialog.html" rel="import">

</head>
<body fullbleed layout vertical unresolved>
		<core-scroll-header-panel mode="waterfall-tall" condenses keepCondensedHeader flex>
		<core-toolbar class="medium-tall">
			<core-icon class="profileIcon" class="top" icon="account-circle"></core-icon>
			<?php
				$sql = "SELECT username FROM senanayake_users WHERE id = '" . mysqli_real_escape_string ($conn, $_SESSION['sess_userid']) . "' limit 1"; 
				$result = mysqli_query($conn, $sql);
				$username = mysqli_fetch_object($result)->username;
				echo "<div flex=\"\"><h1>" . htmlspecialchars($username) . " Homework</h1></div>";
			?>
			<a href="classes.php"><paper-button borderless class="top" sink>Add Classes</paper-button></a>
			<paper-button borderless class="top" sink>Edit Classes</paper-button>

			<a href="logout.php"><paper-fab mini class="top" icon="lock"></paper-fab></a>
			
			<div class="bottom fit">
			<?php
				$curDayOfWeek = date('w')-1;//remove sunday
				echo "<paper-tabs selected=\"$curDayOfWeek\">";
			?>
			  
				<paper-tab role="tab" width="20%">Mon</paper-tab>
				<paper-tab role="tab" width="20%">Tue</paper-tab>
				<paper-tab role="tab" width="20%">Wed</paper-tab>
				<paper-tab role="tab" width="20%">Thurs</paper-tab>
				<paper-tab role="tab" width="20%">Fri</paper-tab>
			  </paper-tabs>
			</div>
			
		  </core-toolbar>
		  
  
		
			<div class="content">
				<?php
					$sql = "SELECT * FROM senanayake_classes WHERE user_id = '{$_SESSION['sess_userid']}'"; //contains query
					$classes = mysqli_query($conn, $sql);
					function printCardsForDay ($classes, $day)
					{
						printEmptyClassesForDay($classes, $day);
						mysqli_data_seek($classes, 0);
					}
					function printEmptyClassesForDay($classes, $day)
					{
						$days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday');
						while($row = mysqli_fetch_assoc($classes)) {
							if ($row[$days[$day]])
							{
								echo '<paper-shadow onclick="newAssignment(\'' . $row['name'] . '\','.$day. ',' . $row['id'] . ')" class="classStub" z="2">';
								echo $row['name'];
								echo '</paper-shadow>';
							}
						}
					}
				?>
				<div style="float:left; width:20%">
					<?php
						printCardsForDay($classes, 0);
					?>
				</div>
				<div style="float:left; width:20%">
				<?php
						printCardsForDay($classes, 1);
					?>
				</div>
				<div style="float:left; width:20%">
				<?php
						printCardsForDay($classes, 2);
					?>
				</div>
				<div style="float:left; width:20%">
				<?php
						printCardsForDay($classes, 3);
					?>
				</div>
				<div style="float:left; width:20%">
				<?php
						printCardsForDay($classes, 4);
					?>
				</div>
			</div>
			
		 </core-scroll-header-panel>
		  <paper-action-dialog backdrop id="newAssignmentDialog" style="width:50%" transition="core-transition-center">
			
		</paper-action-dialog>
		
</body>
<script>
		
			function newAssignment(cls, day, clsid) {
				var days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"];
				var dayText = days[day];
				var heading = cls + " Assignment for " + dayText;
				$("#newAssignmentDialog").attr("heading", heading);
				var html = '<paper-input-decorator id="summary_wrapper" label="Summary" error = "Summary cannot be empty" floatingLabel><input id="summary" is="core-input"/></paper-input-decorator><paper-input-decorator id="description_wrapper" label="Description" floatingLabel><textarea rows="4" id="description" is="core-input"/></paper-input-decorator><div><p style="display:inline-block;vertical-align:middle">Status: </p><paper-radio-group id="status" style="display:inline-block; vertical-align:middle;" selected="blue"><paper-radio-button class="blue" name="blue"></paper-radio-button><paper-radio-button class="green" name="green"></paper-radio-button><paper-radio-button class="yellow" name="yellow"></paper-radio-button><paper-radio-button class="orange" name="orange"></paper-radio-button><paper-radio-button class="red" name="red"></paper-radio-button></paper-radio-group></div><paper-button dismissive>Cancel</paper-button><paper-button onclick="create(\'' + clsid + '\',' + day + ')" affirmative autofocus>Create</paper-button>';
				$("#newAssignmentDialog").html(html);
				document.querySelector('paper-action-dialog').toggle();
			}
			
			function create(clsid, day) {
				var summary = document.getElementById('summary');
				var description = document.getElementById('description');
				var status = 
				 $.post("newassignment.php",
				  {
					summary:summary.value,
					description: description.value
					
				  },
				  function(data,status){
						if (data == "success")
						{
							document.location.href = "index.php";
						}
						else {
							passwordWrapper.error = data;
							passwordWrapper.isInvalid = 1;
						}
				  });		
			}
		</script>