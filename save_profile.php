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
			$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$interestsListFull = parse_url($url, PHP_URL_QUERY);
			
			ini_set("display_errors", "true");
			ini_set("display_errors", "true");
			ini_set("html_errors", "true");
			ini_set("error_reporting", "true");
			error_reporting(E_ALL|E_STRICT);
			
			
			$interestsList = substr($interestsListFull, 0,(strpos($interestsListFull, "&")+1));
			$interestsListEquals = substr($interestsListFull, (strpos($interestsListFull, "=")+1));
			$interestsList = substr($interestsListEquals, 0,(strpos($interestsListEquals, "&")));
			//echo $interestsList;

			$occupationFull = substr($interestsListFull, strpos($interestsListFull, "occupation")+11);
			$occupation = substr($occupationFull, 0,(strpos($occupationFull, "&")));
			
			$bioTextFull = substr($occupationFull, (strpos($occupationFull, "=")+1));
			$bioTextFull = str_replace("-"," ",$bioTextFull);
			
			
			//echo substr($bioTextFull, 0,(strpos($bioTextFull, "&")));
			$bioText = substr($bioTextFull, 0,(strpos($bioTextFull, "&")));
			
			$smoker = substr($bioTextFull, (strpos($bioTextFull, "=")+1));
			//echo $smoker;
			
			
			updateBio($_SESSION['user_id'], $bioText);
			
			$uid = $_SESSION['user_id'];
			$smokerFull = $smoker;
			$smoker = substr($smokerFull, 0,(strpos($smokerFull, "&")));
			//echo $uid . " " . $smoker;
			setSmokes($uid, $smoker);
			$genderFull = substr($smokerFull, (strpos($smokerFull, "=")+1));
			$gender = substr($genderFull, 0,(strpos($genderFull, "&")));
			setGender($uid, $gender);
			
			
			$drinkerFull = substr($genderFull, (strpos($genderFull, "=")+1));
			$drink = substr($drinkerFull, 0,(strpos($drinkerFull, "&")));
			echo "[" . $drink . "]";
			setDrinks($uid, $drink);
			
			$ageFull = substr($drinkerFull, (strpos($drinkerFull, "=")+1));
			$age = substr($ageFull, 0,(strpos($ageFull, "&")));
			setAge($uid, $age);
			
			$countyFull = substr($ageFull, (strpos($ageFull, "=")+1));
			//$county = substr($countyFull, 0,(strpos($countyFull, "&")));
			
			echo "[" . $countyFull . "]";
			$county = $countyFull;
			
			updateCounty($uid, $county);
			
			$occupationID = returnOccupationID((string)$occupation);
			updateOccupation($uid, $occupationID);
			
			$arrayInterestsList = explode(',', $interestsList);
			//print_r($arrayInterestsList);
			
			foreach($arrayInterestsList as $value){
				if($value.trim() != "")
					saveInterests($_SESSION['user_id'], $value);
			}
		?>
		<script>window.location.href = "http://hive.csis.ul.ie/cs4116/group01/profile.php";</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>