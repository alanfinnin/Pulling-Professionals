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
			 if($_SESSION['loggedin'] == 0){
				header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				exit;
			 }
			 
			echo "export SENDGRID_API_KEY=API KEY" > sendgrid.env
			echo "sendgrid.env" >> .gitignore
			source ./sendgrid.env
		?>
		<div class="row">
			<div class="col-sm-2"></div>
				<ul id="contentBox" class="col-sm-8 list-group">
					<li class="list-group-item active purple">All Users
						<form method="POST" action="users.php">
							<input class="md-form mt-0 form-control" type="text" name="searchValue" placeholder="Search" aria-label="Search">
							<input type="submit" class="btn btn-success" value="Search" name="search" >
					</li>
					<?php
					if(isset($_POST['search'])){
							$searchValue = $_POST['searchValue'];
							$searchValueClean = filter_var($searchValue, FILTER_SANITIZE_STRING);
							
							if(strlen($searchValueClean) < 3)
								returnAllUsers();
							else if(strpos($searchValueClean, "*") > -1)
								returnAllUsers();
							else if(strpos($searchValueClean, ")") > -1)
								returnAllUsers();
							else if(strpos($searchValueClean, "(") > -1)
								returnAllUsers();
							else if(strpos($searchValueClean, "=") > -1)
								returnAllUsers();
							else if(strpos(strtolower($searchValueClean), "drop ") > -1)
								returnAllUsers();
							else if(strpos($searchValueClean, ";") > -1)
								returnAllUsers();
							else
								searchUsers($searchValueClean);
						}else
							returnAllUsers();
					?>
				</ul>
		</div>
	</div>
<?php include "resources/templates/footer.php" ?>
</body>
</html>
