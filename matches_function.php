<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
	<?php require_once("resources/templates/navigation.php");?>
	<div class="container">
		<?php
			/*ini_set("display_errors", "true");
			ini_set("html_errors", "true");
			ini_set("error_reporting", "true");
			error_reporting(E_ALL|E_STRICT);*/
			include "resources/library/databaseFunctions.php";
		
		
			 if($_SESSION['loggedin'] == 0){
				header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				exit;
			 }

		?>
		<div id="content">
			<?php
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$other_uid_full = parse_url($url, PHP_URL_QUERY);
				$other_uid = substr($other_uid_full, (strpos($other_uid_full, "=")+1));



				 $message = "";

       				 if( isFriendOrRequestSent($_SESSION['user_id'], $other_uid) == "N" ) {
          				  addMatches($_SESSION['user_id'], $other_uid);
           				 $message = "Friend Request Being Sent";
        			 }
        			 else if(isFriendOrRequestSent($_SESSION['user_id'], $other_uid) == "R" ) {
          				  $message = "There Is Already A Request Pending";
       				 }
       				 else if  ( isFriendOrRequestSent($_SESSION['user_id'], $other_uid) == "F" ) {
         			   $message = "You Are Already Friends";
     				 }  


				header("Location: http://hive.csis.ul.ie/cs4116/group01/users.php");
				echo $message;
				exit;
			?>
		</div>
	</div>
	<script>
		
	</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>