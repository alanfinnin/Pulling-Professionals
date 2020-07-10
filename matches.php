<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
	<?php require_once("resources/templates/navigation.php");?>
		<?php
			include "resources/library/databaseFunctions.php";
			
		?>
		<?php
			 if($_SESSION['loggedin'] == 0){
				header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				exit;
			 }
		?>
		<div class="row">
			<div class="col-sm-2"></div>
				<ul id="contentBox" class="col-sm-8 list-group">
					<li class="list-group-item active purpleHeader">All Matches
					</li>
			<?php
    				getAllMatches($_SESSION['user_id']);
			?>
			</ul>
		</div>
	</div>
	<?php include "resources/templates/footer.php" ?>
</body>
<script>
		function clicked_name(uid){
			console.log(uid);
			//createCookie("otherUID", uid, 1);
			window.location.href = "http://hive.csis.ul.ie/cs4116/group01/other_users.php?uid=" + uid;
		}
</script>

</html>