<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
	<?php require_once("resources/templates/navigation.php");?>
	<div class="container">
		<?php
			include "resources/library/databaseFunctions.php";
			
			 if($_SESSION['loggedin'] == 0){
				header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				exit;
			 }
			$uid = $_SESSION['user_id'];
			clearInterestsDB($uid);
		?>
		<script>window.location.href = "http://hive.csis.ul.ie/cs4116/group01/profile.php";</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>