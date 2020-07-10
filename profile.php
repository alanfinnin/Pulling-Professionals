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
			 $userInfoMap = getUserProfileAttributes($_SESSION['user_id']);
			 //updateBio($_SESSION['user_id'], "Hello world");
			/*ini_set("display_errors", "true");
			ini_set("display_errors", "true");
			ini_set("html_errors", "true");
			ini_set("error_reporting", "true");
			error_reporting(E_ALL|E_STRICT);*/
			
			checkIfProfileExists($_SESSION['user_id']);
		?>
		<div class="coverPhoto">
			<!--<img src="img/pulling_professionals_logo3.png">-->
			<div class="profilePicture">
				<img class="profilePictureImg"src="profile_pictures/<?php  echo returnProfilePicture($_SESSION['user_id']) ?>"alt="profile picture" height="150" width="150">
			</div>
			<div>
				<div class="row">
					<div class="col-sm-3"></div>
					<div class="col-sm-3"><?php echo "<h4 class=\"profileText\">" . $_SESSION['user_name'] . "&nbsp;&nbsp;&nbsp;&nbsp;" . "</h4><h5 id=\"age\">" . getUserAge($_SESSION['user_id']) . "</h5>"; ?></div>
					<div class="col-sm-3"></div>
					<div class="col-sm-3"><?php echo "<h4 id=\"occupation\" class=\"profileText\">". returnOccupation($userInfoMap["occupationID"]) . "</h4>"; ?></div>
				</div>
			</div>
		</div>
		<div class="profilePageMainBody">
		<?php
			$availableInterests = returnInterests();
			$availableOccupations = returnOccupations();
			$availableCounties = returnCounties();
			
			$userInterestsNames = array();
			$userInterestsList = returnUserInterests($_SESSION['user_id']);
			
			foreach($userInterestsList as $interestID) {
				array_push($userInterestsNames, getInterestFromID($interestID));
			}

			$userInterestsListString = "";
			foreach($userInterestsNames as $interestName) {
				$userInterestsListString = $userInterestsListString . $interestName . ",";
			}
			$userInterestsListString = substr($userInterestsListString, 0, strlen($userInterestsListString)-1);
			
		?>
		<script>
			var uid = "";
			var saveButton = false;
			var count = 1;
			var	myOccupation = document.getElementById("occupation").innerHTML;
		</script>
			<div class="row topPadding">
				<div class="col-sm-3">
					<form id="profileUpload" class="invisible" action="upload_profile_picture.php" method="post" enctype="multipart/form-data">
		  <?php echo "<input type=\"text\" name=\"uid\" class=\"invisible\" value=\"" . $_SESSION['user_id'] . "\">" ?>
					  <input type="file" name="fileToUpload" id="fileToUpload">
					  <input class="btn btn-success purple" type="submit" value="Upload Image" name="submit">
					</form>
				</div>
				<div id="interestsDiv" class="col-sm-2 invisible">
					<label for="interests">Interests:</label>
					<select id="interests">
						<?php 
							foreach ($availableInterests as $description){ 
								echo "<option value=\"" . $description . "\">" . $description . "</option>"; 
							}
						?>
					</select>
					<input type="button" onclick="dropdownInterests()" class="btn btn-success purple" value="Add">
					<input name="clearInterestsButton" type="submit" class="btn btn-success purple" onclick="clearInterests()" value="Clear">
					<!--<p id="interestsList"></p>-->
					<?php
						echo "<p id=\"interestsList\">" . $userInterestsListString . "</php>";
					?>
				</div>
				<div id="occupationsDiv" class="col-sm-2 invisible">
					<label  for="occupations">Occupations:</label>
					<select onclick="occupationChange()" id="occupations">
						<?php 
							foreach ($availableOccupations as $description){ 
								echo "<option value=\"" . $description . "\">" . $description . "</option>"; 
							}
						?>
					</select>
				</div>
				<div class="col-sm-1">
					<label class="invisible" id="smokerDropdown" for="smoker">Do you smoke?</label>
						<select class="invisible" name="isSmoker" id="smoker">
						  <option value="y">Yes</option>
						  <option value="n">No</option>
						</select>
				</div>
				<div class="col-sm-1">
					<label class="invisible" id="genderDropdown" for="gender">What is your gender?</label>
						<select class="invisible" name="whichGender" id="gender">
						  <option value="m">Male</option>
						  <option value="f">Female</option>
						</select>
				
				</div>
				<div class="col-sm-1">
					<label class="invisible" id="drinkDropdown" for="drink">Do you drink?</label>
						<select class="invisible" name="doYouDrink" id="drink">
						  <option value="y">Yes</option>
						  <option value="n">No</option>
						</select>
				
				</div>
				<div class="col-sm-2">
					<?php echo "<button type=\"button\" name=\"edit\" id=\"editButton\" class=\"btn btn-success purple\"onclick=\"edit(" . $_SESSION['user_id'] . ")\">Edit</button>" ?>
				</div>
			</div>
				<div class="row">
					<div class="col-sm-1"></div>
					<div class="col-sm-2">
					<label class="invisible" id="countyLabel" for="county">Where are you from?</label>
						<select class="invisible" id="county">
						<?php 
							foreach ($availableCounties as $description){ 
								echo "<option value=\"" . $description . "\">" . $description . "</option>"; 
							}
						?>
					</select>
					</div>
					<div class=""><p id="bioText" class="bioText"><?php
																echo returnUserDescription($_SESSION['user_id']);
																?></p></div>
					
					
				</div>
				<div class="row">
				<div class="col-sm-4"></div>
				<input id="saveBioButton" type="submit" name="saveBio" onclick="saveButton()" class="userInfoForm btn btn-success purple invisible" value="Save">
				</div>
		</div>
	</div>
		<?php
			function saveBio(){

				updateBio($_SESSION['user_id'], $_POST['bioText']);
				$interests =  $_COOKIE["interests"];
				echo "<script>console.log(\"" . $interests . "\");</script>";
				$myArray = explode(',', $interests);
				clearInterestsDB($_SESSION['user_id']);

				foreach($myArray as $value){
					if($value.trim() != "")
						saveInterests($_SESSION['user_id'], $value);
				}
			}
			function clearInterestsDBUsers(){
				echo "<script>console.log(\"Clear before called\");</script>";
				clearInterestsDB($uid);
			}
		?>
	<script>
		var interests = "";
		function occupationChange(){
			document.getElementById("occupation").innerHTML = document.getElementById('occupations').value;
			myOccupation = document.getElementById('occupations').value;
		}
		function dropdownInterests(){
			//console.log(document.getElementById('interests').value);
			if(!(document.getElementById("interestsList").innerHTML.includes(document.getElementById('interests').value))){
				document.getElementById("interestsList").innerHTML += ","  + document.getElementById('interests').value;
				interests += document.getElementById('interests').value + ",";
			}
		}
		function saveButton(){
			var bioText = document.getElementById("bioText").value;
			var smoker = document.getElementById("smoker");
			var gender = document.getElementById("gender");
			var drink = document.getElementById("drink");
			var age = document.getElementById("age").value;
			var county = document.getElementById("county").value;
			
			console.log(county);
			
			var smokerResult = smoker.options[smoker.selectedIndex].value;
			var genderResult = gender.options[gender.selectedIndex].value;
			var drinkResult = drink.options[drink.selectedIndex].value;
			var countyResult = county;
			
			console.log(bioText);
			var replacedBio = bioText.split(' ').join('-');
			
			
			var InterestsList = document.getElementById("interestsList").innerHTML;
			window.location.href = "http://hive.csis.ul.ie/cs4116/group01/save_profile.php?InterestsList=" 
														+ InterestsList + "&occupation=" 
														+ myOccupation + "&bioText=" 
														+ replacedBio + "&smoker=" 
														+ smokerResult + "&gender=" 
														+ genderResult + "&drinker=" 
														+ drinkResult + "&age=" 
														+ age + "&county="
														+ countyResult;
		}
		function clearInterests(){
			document.getElementById("interestsList").innerHTML = "";
			window.location.href = "http://hive.csis.ul.ie/cs4116/group01/clear_profile_interests.php";
		}
		function getInterests(){
			interests = document.getElementById("interestsList").innerHTML;
			console.log("GetInterest List:" + interests);
		}
		function edit(uidPassed){
			uid = uidPassed;
			if(count % 2 == 0){
				saveButton = true;
				document.getElementById("countyLabel").classList.add('invisible');
				document.getElementById("county").classList.add('invisible');
				document.getElementById("gender").classList.add('invisible');
				document.getElementById("drink").classList.add('invisible');
				document.getElementById("drinkDropdown").classList.add('invisible');
				document.getElementById("genderDropdown").classList.add('invisible');
				document.getElementById("profileUpload").classList.add('invisible');
				document.getElementById("smoker").classList.add('invisible');
				document.getElementById("smokerDropdown").classList.add('invisible');
				document.getElementById("interestsDiv").classList.add('invisible');
				document.getElementById("occupationsDiv").classList.add('invisible');
				document.getElementById("saveBioButton").classList.add('invisible');
				
				//document.getElementById("interestsList").innerHTML = "";
				interests = "";
				
				document.getElementById("editButton").innerHTML = "Edit";
				
				var e = document.getElementById("bioText");

				var d = document.createElement('p');
			
				d.innerHTML = e.value;
				//console.log(e.value);
				d.classList.add("bioText");
				d.id = "bioText";

				e.parentNode.replaceChild(d, e);
				
				var textAreaAge = document.getElementById("age");
				var newPTag = document.createElement('h5');
				newPTag.innerHTML = textAreaAge.value;
				newPTag.id = "age";
				textAreaAge.parentNode.replaceChild(newPTag, textAreaAge);
				
				
			}else{
				document.getElementById("countyLabel").classList.remove('invisible');
				document.getElementById("county").classList.remove('invisible');
				document.getElementById("gender").classList.remove('invisible');
				document.getElementById("drink").classList.remove('invisible');
				document.getElementById("drinkDropdown").classList.remove('invisible');
				document.getElementById("genderDropdown").classList.remove('invisible');
				document.getElementById("profileUpload").classList.remove('invisible');
				document.getElementById("smoker").classList.remove('invisible');
				document.getElementById("smokerDropdown").classList.remove('invisible');
				document.getElementById("interestsDiv").classList.remove('invisible');
				document.getElementById("occupationsDiv").classList.remove('invisible');
				document.getElementById("editButton").innerHTML = "Close";
				document.getElementById("saveBioButton").classList.remove('invisible');
				
				var e = document.getElementById("bioText");
				var ageArea = document.getElementById("age");

				var d = document.createElement('textarea');
				d.innerHTML = e.innerHTML;
				//console.log(e.innerHTML);
				d.classList.add("bioText");
				
				d.id = "bioText";

				e.parentNode.replaceChild(d, e);
				
				document.getElementById("bioText").setAttribute("name","bioText");
				<!-- -->
				var newTextArea = document.createElement('textarea');
				newTextArea.innerHTML = ageArea.innerHTML;
				//console.log(e.innerHTML);
				
				
				newTextArea.id = "age";

				ageArea.parentNode.replaceChild(newTextArea, ageArea);
				document.getElementById("age").setAttribute("rows","1");
				document.getElementById("age").setAttribute("cols","2");
				
			}
			count++;
		}
	</script>
<?php include "resources/templates/footer.php" ?>
</body>
</html>