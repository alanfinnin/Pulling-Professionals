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
			 function gender($result){
				 if($result == "m"){
					 return "Male";
				 }else{
					 return "Female";
				 }
			 }

		?>
		<div id="content">
			<?php
				$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$other_uid_full = parse_url($url, PHP_URL_QUERY);
				$other_uid = substr($other_uid_full, (strpos($other_uid_full, "=")+1));
				$age = getUserAge($other_uid);
				//echo $other_uid;
				$userInfoMap = getUserProfileAttributes($other_uid);
				//print_r($userInfoMap); 
			?>
			<div class="coverPhoto">
				<!--<img src="img/pulling_professionals_logo3.png">-->
				<div class="profilePicture">
					<img class="profilePictureImg"src="profile_pictures/<?php  echo returnProfilePicture($other_uid) ?>"alt="profile picture" height="150" width="150">
				</div>
				<div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-4"><?php echo "<h3 class=\"profileText\">" . getUserFullname($other_uid) . "&nbsp;&nbsp;" . $age . "</h3>"; ?></div>
						<div class="col-sm-2"></div>
						<div class="col-sm-3"><?php echo "<h4 class=\"profileText\">" . gender($userInfoMap["sex"]) . "&nbsp" . returnOccupation($userInfoMap["occupationID"]) . "</h4>"; ?></div>
					</div>
				</div>
			</div>
				<div class="profilePageMainBody countyHeading">
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-3"><?php echo "<h4 class=\"\">" . returnCounty($userInfoMap["countryID"]) . "</h4>" ?></div>
						<div class="col-sm-3"></div>
						<div class="col-sm-3"></div>
					</div>
					<div class="row">
						<div class="col-sm-3"></div>
						<div class="col-sm-3"><?php echo "<p>" . returnBio($other_uid) . "</p>"; ?></div>
						<div class="col-sm-3"></div>
						<div class="col-sm-3"></div>
					</div>
				</div>
		</div>
	</div>
	<script>
		
	</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>