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
			
		?>
		<?php
		//Get rid of before being used
		/*ini_set("display_errors", "true");
		ini_set("html_errors", "true");
		ini_set("error_reporting", "true");
		error_reporting(E_ALL|E_STRICT);*/
		
		
			 if($_SESSION['loggedin'] == 0){
				header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				exit;
			 }

		?>
		<div class="row">
			<div class="col-sm-2"></div>
				<ul id="contentBox" class="col-sm-8 list-group">
					<li class="list-group-item active purpleHeader">All Users
						<form method="POST" action="users.php">
							<input class="md-form mt-0 form-control" type="text" name="searchValue" placeholder="Search" aria-label="Search">
							<input class="btn btn-success purple" type="submit" class="btn btn-success" value="Search" name="search" >
					</li>
					<?php
					if(isset($_POST['search'])){
							$searchValue = $_POST['searchValue'];
							$searchValueClean = filter_var($searchValue, FILTER_SANITIZE_STRING);
							
							if(strlen($searchValueClean) < 3)
								returnAllUsers2();
							else if(strpos($searchValueClean, "*") > -1)
								returnAllUsers2();
							else if(strpos($searchValueClean, ")") > -1)
								returnAllUsers2();
							else if(strpos($searchValueClean, "(") > -1)
								returnAllUsers2();
							else if(strpos($searchValueClean, "=") > -1)
								returnAllUsers2();
							else if(strpos(strtolower($searchValueClean), "drop ") > -1)
								returnAllUsers();
							else if(strpos($searchValueClean, ";") > -1)
								returnAllUsers2();
							else
								searchUsers2($searchValueClean);
						}else
							returnAllUsers2();
					?>
				</ul>
		</div>
	</div>
	<script>
		function clicked(uid){
			console.log(uid);
			//createCookie("otherUID", uid, 1);
			window.location.href = "http://hive.csis.ul.ie/cs4116/group01/other_users.php?uid=" + uid;
		}
		function add_button_click(uid){
			console.log(uid);
			window.location.href = "http://hive.csis.ul.ie/cs4116/group01/matches_function.php?uid=" + uid;
			event.stopPropagation()
		}
	</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>