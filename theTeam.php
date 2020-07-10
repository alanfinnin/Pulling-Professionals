<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
<?php 
require_once("resources/templates/navigation.php");
?>
<div class="container">
<br>
	<h1>The Team</h1>
	<!--<form action="http://hive.csis.ul.ie/cs4116/group01/home.php">
		<button class="btn btn-success purple" type="submit" value="Send">Home</button>
	</form>-->
</div>

	<div class="row">
		<div class = "col-sm-2"></div>
			<ul class="col-sm-8 list-group">
				<li class="list-group-item highlight">
						<img class="picture" src="img/avatars/alan.jpg">
						<p class="larger">Alan Finnin</p>
						<p class="userListBioText">One of the main programmers and builder of Pulling Professionals, 
													Alan brings years of experience and enthusiasm to the team. Alan also 
													playes a major part in site marketting. Bringing Pulling Professionals to 
													the world.
													</p>
				</li>
				<li class="list-group-item highlight">
						<img class="picture" src="img/avatars/dan.jpg">
						<p class="larger">Daniel Dalton</p>
						<p class="userListBioText">Head if Admin Operations, Daniel decides who can see what. Alongside this 
													Daniel has an eye for everything aesthetic gives him an edge for giving people
													the site they want.</p>
				</li>
				<li class="list-group-item highlight">
						<img class="picture" src="img/avatars/william.jpg">
						<p class="larger">William Cummins</p>
						<p class="userListBioText">A Tip man at heart but finding his way in the big world, William deals with login. William also brings a great
													enthusiasm to the team thats well needed at points.</p>
				</li>
				<li class="list-group-item highlight">
					<img class="picture" src="img/avatars/james.jpg">
						
					<p class="larger">James Brosnan</p><br>
					<p class="userListBioText">Dealing with the home screen and integral for team cohesion, James brings a number of unique 
												skills that bring Pulling Professionals to the next level</p>
				</li>
			</ul>
		</div>
	</div>
<?php
	include "resources/templates/footer.php"
?>
</body>
</html>