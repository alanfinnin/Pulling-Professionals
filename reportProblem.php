<!DOCTYPE html>
<html>
<?php require_once("resources/templates/header.php");?>
<body class = "mainBlock">

<?php 
require_once("resources/templates/navigation.php");
?>
<div class="container">
<br>
	<h1>Report A Problem</h1>
</div>
<?php require_once("resources/templates/footer.php");?>
	<div>
		<form class="userInfoForm" action="mailto:alanfinnin@outlook.com" method="post" enctype="text/plain">
			Page:<br>
			<input type="text" name="name"><br>
			Issue:<br>
			<textarea type="text" name="Issue" col="50" rows="6"></textarea><br><br>
			<button class="btn btn-success purple" type="submit" value="Send">Send</button>
			<button class="btn btn-success purple" type="reset" value="Reset">Reset</button>
		</form>
	</div>
</body>
</html>