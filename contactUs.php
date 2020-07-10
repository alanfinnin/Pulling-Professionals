<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
<title>Pulling Professionals</title>
<link rel="stylesheet" href="/cs4116/group01/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/cs4116/group01/css/Navigation-Clean.css">
<link rel="stylesheet" href="/cs4116/group01/css/styles.css">
<link rel="stylesheet" type="text/css" href="/cs4116/group01/css/main.css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body class = "mainBlock">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<?php 
		require_once("resources/templates/navigation.php");
	?>
	<div class="container">
	<br>
		<h1>Contact Us</h1>
	</div>
	<?php include "resources/templates/footer.php" ?>
	<div>
		<form class="userInfoForm" action="mailto:alanfinnin@outlook.com" method="post" enctype="text/plain">
			Name:<br>
			<input type="text" name="name"><br>
			E-mail:<br>
			<input type="text" name="mail"><br>
			Comment:<br>
			<textarea type="text" name="comment" col="50" rows="6"></textarea><br><br>
			<button class="btn btn-success purple" type="submit" value="Send">Send</button>
			<button class="btn btn-success purple" type="reset" value="Reset">Reset</button>
		</form>
	</div>
</body>
</html>