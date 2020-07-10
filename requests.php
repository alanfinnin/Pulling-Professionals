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
		?>
		<div class="row">
			<div class="col-sm-2"></div>
				<ul id="contentBox" class="col-sm-8 list-group">
					<li class="list-group-item active purpleHeader">All Requests
						<form method="POST" action="requests.php">
					</li>
							
<?php

$recommendationsMap = getAllRequestsInMap($_SESSION['user_id']);
for($i = 0 ; $i < sizeof($recommendationsMap); $i++) {

    $firstName = $recommendationsMap[$i]['FirstName'];
    $lastName = $recommendationsMap[$i]['LastName'];
    $userId = $recommendationsMap[$i]['UserID'];

    $rejectBtnName = $userId ."reject";

    echo "<li class=\"list-group-item highlight\">
						<img class=\"picture\" src=\"profile_pictures/" . $userId . ".jpg". "\">
						<p onclick=\"clicked(" . $userId . ")\" class=\"larger\">" . $firstName . " " . $lastName . "</p>
						<form method=\"post\">
							<input type=\"submit\" class=\"btn btn-primary purple floatRight\" value=\"Reject\" name=\"$rejectBtnName\"/>
							<input type = \"submit\" class=\"btn btn-primary purple floatRight\" value=\"Accept\" name=\"$userId\"/>
						</form>		    
			        </li>";         
}

for($i = 0 ; $i < sizeof($recommendationsMap); $i++) {

    $userId = $recommendationsMap[$i]['UserID'];

    $rejectBtnName = $userId ."reject";

    if(isset($_POST[$userId])) {
        addMatches($_SESSION['user_id'], $userId);
	acceptMatches($userId, $_SESSION['user_id']);
	acceptMatches($_SESSION['user_id'], $userId);
        echo "<br>" .$userId . "has been accepted";
 	echo " <br> Accept has been clicked";
	echo "<meta http-equiv='refresh' content='0'>";
    }
    
    if(isset($_POST[$rejectBtnName])) {
        rejectMatches($userId, $_SESSION['user_id']);

        echo "<br>" .$userId . "has been rejected";
	echo "<meta http-equiv='refresh' content='0'>";
    }


}
			



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
</script>

		
			
	<?php include "resources/templates/footer.php" ?>
</body>
</html>