<?php
ini_set("session.cookie_httponly","1");
session_start();

//get rid of before submission
/*ini_set("display_errors", "true");
ini_set("html_errors", "true");
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRICT);*/




function connectDatabase(){
    //$config = require_once('resources/database_config.php');
    
    $encoded_string = "WGtNdmJYb3plekpsU2taTVlTcFFhQT09";

    //Since both the MySQL instance and the webserver are on hive, we can use localhost to connect
    $db_host = "localhost";
    $db_user = "group01";
    $db_pass = base64_decode(base64_decode($encoded_string));
    $db_name = "dbgroup01";
    $db_port = "3306";

    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port) or die("Unable to connect to DBMS.");

    return $conn;
}
function returnCountyID($name){
	$countyID = 0;
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT CountyID FROM Counties_IE WHERE Description=?");
	$stmt->bind_param("s", $name);
    $stmt->bind_result($countyID);
	
    if($stmt->execute()){
        $stmt->fetch();
	}else{
		echo "Error";
	}
    
	return $countyID;
    $stmt->close();
    $conn->close();
}
function updateCounty($uid, $county){
	$countyID = returnCountyID($county);
	$conn = connectDatabase();
    $stmt = $conn->prepare("UPDATE Profile SET CountyID = ? Where UserID=?");
	$stmt->bind_param("ii", $countyID, $uid);
	
    if($stmt->execute()){
		$stmt->fetch();
        //echo "Success_county";
	}else{
		echo "Error_county";
	}
    $stmt->close();
    $conn->close();
}
function returnCounties(){
	$descriptionList = array();
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Description FROM Counties_IE");
    $stmt->bind_result($description);
	
    if($stmt->execute()){
        $counter = 0;
		while ($stmt->fetch()) {
			array_push($descriptionList, $description);
		}
	}else{
		echo "Error";
	}
    
	return $descriptionList;
    $stmt->close();
    $conn->close();
}
function setAge($uid, $age){
	$conn = connectDatabase();
	$stmt = $conn->prepare("UPDATE Profile SET Age=? WHERE UserID=?");
	$stmt->bind_param("si", $age, $uid);
	 if ($stmt->execute()) {
		$stmt->fetch();
		//echo "Success_Age";
    } else {
        echo "Error: <br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
function setDrinks($uid, $drinker){
	$conn = connectDatabase();
	$stmt = $conn->prepare("UPDATE Profile SET Drinker=? WHERE UserID=?");
	$stmt->bind_param("si", $drinker, $uid);
	 if ($stmt->execute()) {
		$stmt->fetch();
		//echo "Success_Drinks";
    } else {
        echo "Error: <br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
function setSmokes($uid, $smokes){
	$conn = connectDatabase();
	$stmt = $conn->prepare("UPDATE Profile SET Smoker=? WHERE UserID=?");
	$stmt->bind_param("si", $smokes, $uid);
	 if ($stmt->execute()) {
		$stmt->fetch();
		//echo "Success";
    } else {
        echo "Error: <br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
function setGender($uid, $gender){
	$conn = connectDatabase();
	$stmt = $conn->prepare("UPDATE Profile SET Sex=? WHERE UserID=?");
	$stmt->bind_param("si", $gender, $uid);
	 if ($stmt->execute()) {
		$stmt->fetch();
		//echo "Success_Gender";
    } else {
        echo "Error: <br>" . $conn->error;
    }
	if($gender == "m"){
		setSeeking($uid, "f");
	}else{
		setSeeking($uid, "m");
	}

    $stmt->close();
    $conn->close();
}
function setSeeking($uid, $seeking){
	$conn = connectDatabase();
	$stmt = $conn->prepare("UPDATE Profile SET Seeking=? WHERE UserID=?");
	$stmt->bind_param("si", $seeking, $uid);
	 if ($stmt->execute()) {
		$stmt->fetch();
		//echo "Success_Gender";
    } else {
        echo "Error: <br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}function checkIfProfileExists($uid){
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID FROM Profile WHERE UserID=?");
	$stmt->bind_param("i", $uid);
    $stmt->bind_result($otherID);
	
	if($stmt->execute()){
		$stmt->fetch();
	}else{
		echo "Error creating profile";
	}
	if($otherID != $uid){
		createProfile($uid);
	}
}
function createProfile($uid){
	$conn = connectDatabase();
	$age = 18;
	$sex = "m";
	$countyID = 25;
	$occupation = 5;
	$smoker = "n";
	$drinker = "n";
	$seeking = "f";
    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO Profile (UserID, Age, Sex, CountyID, Occupation, Smoker, Drinker, Seeking) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisiisss",$uid, $age, $sex, $countyID, $occupation,$smoker, $drinker, $seeking);

    if ($stmt->execute() === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

	$stmt->close();
    $conn->close();
}
function returnAllUsers(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User");
    $stmt->bind_result($userID, $email, $firstName, $lastName);
    if($stmt->execute()){
		while ($stmt->fetch()) {
			if($userID != $_SESSION['user_id']){
				if(checkUserBanned($userID)){
					$banned = "Yes";
				}else{
					$banned = "No";
				}
				if($banned == "No"){
					echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button onclick=\"add_button_click(" . $userID . ");\" class=\"" . $userID . " btn btn-primary purple floatRight\" type=\"button\">Add</button>
							</li>";
				}
			}
		}
	}
    
    $stmt->close();
    $conn->close();
}

function returnAllUsers2(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User");
    $stmt->bind_result($userID, $email, $firstName, $lastName);
    if($stmt->execute()){
        while ($stmt->fetch()) {
            if($userID != $_SESSION['user_id']){
                if(checkUserBanned($userID)){
                    $banned = "Yes";
                }else{
                    $banned = "No";
                }
                if($banned == "No"){

                    // Users Are Not Friends
                    if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "N" )
                    {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button onclick=\"add_button_click(" . $userID . ");\" class=\"" . $userID . " btn btn-primary purple floatRight\" type=\"button\">Add</button>
							</li>";

                    }
                    else if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "R") {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button  class=\"" . $userID . " btn btn-primary disabled purple floatRight\" type=\"button\">Request Pending</button>
							</li>";
                    }
                    else if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "F") {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button class=\"" . $userID . " btn btn-primary disabled purple floatRight\" type=\"button\">Already Friends</button>
							</li>";
                    }
                }
            }
        }
    }

    $stmt->close();
    $conn->close();
}




function returnProfilePicture($uid){
	$filePath = "profile_pictures/";
	$fileName = $uid . ".jpg";
	if(file_exists($filePath . $fileName)){
		return $fileName;
	}else{
		return "default.jpg";
	}	
}
function searchUsers($name){
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User WHERE CONCAT(firstName, ' ', lastName) LIKE CONCAT('%',?,'%')");
	$stmt->bind_param("s", $name);
    $stmt->bind_result($userID, $email, $firstName, $lastName);

    if($stmt->execute()){
		while ($stmt->fetch()) {
			if($userID != $_SESSION['user_id']){
				if(checkUserBanned($userID)){
					$banned = "Yes";
				}else{
					$banned = "No";
				}
				if($banned == "No"){
					echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button class=\"" . $userID . " btn btn-primary purple floatRight\" type=\"button\">Add</button>
							</li>";
				}
			}
		}
	}else{
		echo "Error";
	}
    
    $stmt->close();
    $conn->close();
}

function searchUsers2($name){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User WHERE CONCAT(firstName, ' ', lastName) LIKE CONCAT('%',?,'%')");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($userID, $email, $firstName, $lastName);

    if($stmt->execute()){
        while ($stmt->fetch()) {
            if($userID != $_SESSION['user_id']){
                if(checkUserBanned($userID)){
                    $banned = "Yes";
                }else{
                    $banned = "No";
                }
                if($banned == "No"){
                    // Users Are Not Friends
                    if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "N" )
                    {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button onclick=\"add_button_click(" . $userID . ");\" class=\"" . $userID . " btn btn-primary purple floatRight\" type=\"button\">Add</button>
							</li>";

                    }
                    else if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "R") {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button  class=\"" . $userID . " btn btn-primary disabled purple floatRight\" type=\"button\">Request Pending</button>
							</li>";
                    }
                    else if( isFriendOrRequestSent($_SESSION['user_id'], $userID) == "F") {
                        echo "<li onclick=\"clicked(" . $userID . ")\" class=\"" . $userID . " list-group-item highlight\">
							<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
							<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
							<button class=\"" . $userID . " btn btn-primary disabled purple floatRight\" type=\"button\">Already Friends</button>
							</li>";
                    }
                }
            }
        }
    }else{
        echo "Error";
    }

    $stmt->close();
    $conn->close();
}


function getUserID($email){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT UserID FROM User WHERE Email=?");
    $stmt->bind_param("s", $email);
	$stmt->bind_result($userID);

    if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}

    $conn->close();

    return $userID;
}

function getUserFullname($userID){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT FirstName, LastName FROM User WHERE UserID=?");
    $stmt->bind_param("i", $userID);
	$stmt->bind_result($firstName, $lastName);

    if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}

    $conn->close();

    return $firstName . " " . $lastName;
}

function createUser($email, $firstName, $lastName, $passwordHash){
    $conn = connectDatabase();
    $defaultAdminValue = 0;

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO User (Email, FirstName, LastName, PasswordHash, Admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $email,$firstName, $lastName, $passwordHash, $defaultAdminValue);

    if ($stmt->execute() === TRUE) {
        //echo "New record created successfully";
    } else {
        echo "Error: "  . "<br>" . $conn->error;
    }

    $conn->close();
}

//Returns false if email exists, returns true if it doesn't
function checkUniqueEmail($email){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT * FROM User WHERE Email=?");
    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    if (!($result = $stmt->get_result())) {
        echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $conn->close();

    if ($result->num_rows > 0) {
        return false;
    } else {
		return true;
    }
}

function checkUserBanned($userID){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT * FROM Banned_Users WHERE UserID=?");
    $stmt->bind_param("i", $userID);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    if (!($result = $stmt->get_result())) {
        echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $conn->close();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function checkUserAdmin($userID){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT * FROM User WHERE UserID=? AND Admin > 0");
    $stmt->bind_param("i", $userID);

    if (!$stmt->execute()) {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    if (!($result = $stmt->get_result())) {
        echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $conn->close();

    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function getUserPasswordHash($email){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT PasswordHash FROM User WHERE Email=?");
    $stmt->bind_param("s", $email);
	$stmt->bind_result($hash);

    if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}

    $conn->close();

    return $hash;
}
function returnInterests(){
	$interestsList = array();
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Description FROM Available_Interests");
    $stmt->bind_result($description);
	
    if($stmt->execute()){
        $counter = 0;
		while ($stmt->fetch()) {
			// Start new row
			array_push($interestsList, $description);
			$counter++;
		}
	}else{
		echo "Error";
	}
    
	return $interestsList;
    $stmt->close();
    $conn->close();
}
function returnUserInterests($uid){
	$interestsList = array();
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT InterestID FROM Interests WHERE UserID=?");
	$stmt->bind_param("i", $uid);
    $stmt->bind_result($interestID);
	
    if($stmt->execute()){
        $counter = 0;
		while ($stmt->fetch()) {
			array_push($interestsList, $interestID);
		}
	}else{
		echo "Error";
	}
    
	$stmt->close();
    $conn->close();
	
	return $interestsList;
}
function returnOccupations(){
	$occupationsList = array();
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Description FROM Available_Occupations");
    $stmt->bind_result($description);
	
    if($stmt->execute()){
        $counter = 0;
		while ($stmt->fetch()) {
			// Start new row
			array_push($occupationsList, $description);
			$counter++;
		}
	}else{
		echo "Error";
	}
    
	return $occupationsList;
    $stmt->close();
    $conn->close();
}
function returnOccupation($jobID){
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Description FROM Available_Occupations WHERE JobID=?");
	$stmt->bind_param("i", $jobID);
    $stmt->bind_result($description);
	
    if($stmt->execute()){
        $stmt->fetch();
	}else{
		echo "Error";
	}
    
	return $description;
    $stmt->close();
    $conn->close();
}
function returnOccupationID($name){
	$jobID = 0;
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT JobID FROM Available_Occupations WHERE Description=?");
	$stmt->bind_param("s", $name);
    $stmt->bind_result($jobID);
	
    if($stmt->execute()){
        $stmt->fetch();
	}else{
		echo "Error";
	}
    
	return $jobID;
    $stmt->close();
    $conn->close();
}
function updateOccupation($uid, $jobID){
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("UPDATE Profile SET Occupation = ? Where UserID=?");
	$stmt->bind_param("ii", $jobID, $uid);
	
    if($stmt->execute()){
		$stmt->fetch();
        echo "Success";
	}else{
		echo "Error";
	}
    $stmt->close();
    $conn->close();
}
function returnCounty($countyID){
	$description = "";
	$conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Description FROM Counties_IE WHERE CountyID=?");
	$stmt->bind_param("i", $countyID);
    $stmt->bind_result($description);
	
    if($stmt->execute()){
        $counter = 0;
		while ($stmt->fetch()) {
		}
	}else{
		echo "Error";
	}
    
	return $description;
    $stmt->close();
    $conn->close();
}

function returnUserDescription($userID){
	$conn = connectDatabase();

    $stmt = $conn->prepare("SELECT Description FROM Profile WHERE UserID=?");
    $stmt->bind_param("i", $userID);
	$stmt->bind_result($description);
	
	if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}

    $conn->close();
	
	return $description;
}
function addOccupation($name){
	$conn = connectDatabase();

    $stmt = $conn->prepare("INSERT INTO Available_Occupations (Description) VALUES (?)");
	$stmt->bind_param("s", $name);

    if ($stmt->execute() === TRUE) {
        //echo "New record created successfully";
		return true;
    } else {
        //echo "Error: " . $stmt . "<br>" . $conn->error;
		return false;
    }

    $conn->close();
}

function addInterest($name){
	$conn = connectDatabase();

    $stmt = $conn->prepare("INSERT INTO Available_Interests (Description) VALUES (?)");
	$stmt->bind_param("s", $name);

    if ($stmt->execute() === TRUE) {
        //echo "New record created successfully";
		return true;
    } else {
        //echo "Error: " . $stmt . "<br>" . $conn->error;
		return false;
    }

    $conn->close();
}
function updateBio($userID, $bio){
	$conn = connectDatabase();

    $stmt = $conn->prepare("UPDATE Profile SET Description = ?  Where UserID = ?");
    $stmt->bind_param("si", $bio, $userID);
	
	if ($stmt->execute() === TRUE) {
        //echo "<script>console.log(\"New record created successfully\");</script>";
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }
}
function returnBio($uid){
	$conn = connectDatabase();
	$bio = "";
	
    $stmt = $conn->prepare("SELECT Description FROM Profile Where UserID = ?");
    $stmt->bind_param("i", $uid);
	$stmt->bind_result($bio);
	
	if ($stmt->execute() === TRUE) {
        $stmt->fetch();
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }
	return $bio;
	$stmt->close();
    $conn->close();
	
}
function getInterestFromName($interestDescription){
	if($interestDescription.trim() != ""){
		$conn = connectDatabase();
		$stmt = $conn->prepare("SELECT InterestID FROM Available_Interests WHERE Description=?");
		$stmt->bind_param("s", $interestDescription);
		$stmt->bind_result($InterestID);
		if($stmt->execute()) {
			$stmt->fetch();
		}else{
			echo "Error";
		}
		return $InterestID;
	}
}
function getInterestFromID($interestID){
	$conn = connectDatabase();
	$stmt = $conn->prepare("SELECT Description FROM Available_Interests WHERE InterestID=?");
	$stmt->bind_param("i", $interestID);
	$stmt->bind_result($InterestName);
	if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}
	return $InterestName;
}
function getUserAge($uid){
	$age = 18;
	$conn = connectDatabase();
	$stmt = $conn->prepare("SELECT Age FROM Profile WHERE UserID=?");
	$stmt->bind_param("i", $uid);
	$stmt->bind_result($age);
	if($stmt->execute()) {
		$stmt->fetch();
	}else{
		echo "Error";
	}
	return $age;
}
function saveInterests($userID, $interest){
	$conn = connectDatabase();
	
	$interestID = getInterestFromName($interest);
	if($interestID != null){
	
		$stmt = $conn->prepare("INSERT INTO Interests (UserID, InterestID) VALUES (?, ?)");
		$stmt->bind_param("ii", $userID, $interestID);

		if ($stmt->execute() === TRUE) {
			//echo "New record created successfully";
		} else {
			//echo "Error: " . "stmt" . "<br>" . $conn->error;
		}
		$stmt->close();
		$conn->close();
	}

    $conn->close();
}
function clearInterestsDB($uid){
	//echo "clear called";
	$conn = connectDatabase();
	
	$stmt = $conn->prepare("DELETE FROM Interests WHERE UserID=?");
	$stmt->bind_param("i", $uid);
	if ($stmt->execute() === TRUE) {
		//echo "Record(s) deleted successfully";
	}else{
		echo "Error: " . "stmt" . "<br>" . $conn->error;
	}
	$stmt->close();
    $conn->close();
}

function editUserEmail($userID, $email){
    $conn = connectDatabase();

    $stmt = $conn->prepare("UPDATE User SET Email = ? Where UserID=?");
    $stmt->bind_param("ss", $email, $userID);

    if ($stmt->execute() === TRUE) {
        //echo "Email been changed";
        //echo " db values. $userID . $email";
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

function editUserPassword($userID, $hashPassword){
    $conn = connectDatabase();

    $stmt = $conn->prepare("UPDATE User SET PasswordHash = ? Where UserID=?");
    $stmt->bind_param("ss", $hashPassword, $userID);

    if ($stmt->execute() === TRUE) {
        echo "Password has been changed";
        //echo " db values. $userID . $hashPassword";
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

function getUserProfileAttributes($userID){
    $conn = connectDatabase();
    $userAttribMap = array();
    $query = "SELECT CountyID, Occupation, Smoker, Drinker, Sex FROM Profile WHERE UserID='$userID'";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $userAttribMap['sex'] = $row["Sex"];
            $userAttribMap['countryID'] = $row["CountyID"];
            $userAttribMap['occupationID'] = $row["Occupation"];
            $userAttribMap['smoker'] = $row["Smoker"];
            $userAttribMap['drinker'] = $row["Drinker"];
        }
    } else {

    }

    $query = "SELECT InterestID FROM interests WHERE UserID = $userID";

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $interestId = $row["InterestID"];
            if($interestId != null) {
                $userAttribMap['InterestID'] = $interestId;
            }
        }
    }

    $conn->close();

    return $userAttribMap;


}

function getMapOfSimilarUsers($userAttribMap) {
    $conn = connectDatabase();

    // Attributes of site user
    $sex = $userAttribMap['sex'];
    $countryID = $userAttribMap['countryID'];
    $occupationID = $userAttribMap['occupationID'];
    $smoker = $userAttribMap['smoker'];
    $drinker = $userAttribMap['drinker'];
    $interestID = $userAttribMap['InterestID'];
    $query = "SELECT UserID, CountyID, Occupation, Smoker, Drinker FROM Profile WHERE Sex != '$sex'";

    // Array of matchesAttribMap
    $matchingUsers = array();
    // Matching users attributes
    $matchesAttribMap = array();
    // Execute query
    $result = $conn->query($query);

    $count = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {


             if(checkUserBanned($row["UserID"])){
                $banned = "Yes";
            }else{
                $banned = "No";
                if( isFriendOrRequestSent($_SESSION['user_id'],$row["UserID"]) == "N" ) {
                    $matchesAttribMap['UserID'] = $row["UserID"];
                    $matchesAttribMap['CountyID'] = $row["CountyID"];
                    $matchesAttribMap['OccupationID'] = $row["Occupation"];
                    $matchesAttribMap['Smoker'] = $row["Smoker"];
                    $matchesAttribMap['Drinker'] = $row["Drinker"];

                    $matchingUsers[$count] = $matchesAttribMap;
                    $count++;
                }
            }




        }
    } else {
        echo "................ 0 RESULTS ..............";
    }

    for($i = 0 ; $i < sizeof($matchingUsers); $i++) {
        $userId = $matchingUsers[$i]['UserID'];
        $query = "SELECT InterestID FROM interests WHERE UserID = $userId";

        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $interestIdOtherUser = $row["InterestID"];
                $matchingUsers[$i]['InterestID'] = $interestIdOtherUser;

            }
        }
    }

    // Assigning weight for each user
    for($i=0; $i < sizeof($matchingUsers); $i++) {
        // weight variable is used to record how similar users are for purposes of recommendation
        $weight= 0;
        if($countryID == $matchingUsers[$i]['CountyID']) {
            $weight++;
        }
        if($occupationID == $matchingUsers[$i]['OccupationID']) {
            $weight++;
        }
        if($smoker == $matchingUsers[$i]['Smoker']) {
            $weight++;
        }
        if($drinker == $matchingUsers[$i]['Drinker']) {
            $weight++;
        }
        if($interestID != null) {
            if ($interestID == $matchingUsers[$i]['InterestID']) {
                $weight++;
            }
        }
        $matchingUsers[$i]['Weight'] = $weight;
    }
    $conn->close();

    return $matchingUsers;
}



// Randomly recommend users based on their weight
function recommendMatchesFromMap($matchingUsers) {

    $pickedUsers = array();
    for($i=0; $i < sizeof($matchingUsers); $i++) {
        if($matchingUsers[$i]['Weight'] == 1)  {
            // 10% chance
            if(rand(0,1) <= 0.1 ) {
                //  echo "\"\r\n\" 10%";
                //  echo "Picked <br>";
                $pickedUsers[] = $matchingUsers[$i];
            }
        }
        else if($matchingUsers[$i]['Weight'] == 2)  {
            // 20% chance
            if(rand(0,1) <= 0.2 ) {
                //echo "\"\r\n\" 20%";
                // echo "picked <br>";
                $pickedUsers[] = $matchingUsers[$i];
            }
        }
        else if($matchingUsers[$i]['Weight'] == 3)  {
            // 30% chance
            if(rand(0,1) <= 0.3 ) {
                // echo "\"\r\n\" 30%";
                //  echo "picked <br>";
                $pickedUsers[] = $matchingUsers[$i];
            }
        }
        else if($matchingUsers[$i]['Weight'] == 4)  {
            // 40% chance
            if(rand(0,1) <= 0.4 ) {
                // echo "\"\r\n\" 40%";
                // echo "picked <br>";
                $pickedUsers[] = $matchingUsers[$i];
            }
        }
        else if($matchingUsers[$i]['Weight'] == 5)  {
            // 50% chance
            if(rand(0,1) <= 0.5 ) {
                // echo "\"\r\n\" 50%";
                // echo "picked <br>";
                $pickedUsers[] = $matchingUsers[$i];
            }
        }

    }
    return $pickedUsers;
}

function getUserNamesForUsers($users)
{
    $usersFullInfo = array(array());

    for($i=0; $i < sizeof($users); $i++) {
        $usersFullInfo[$i] = array();
        for($j=0; $j < sizeof($users[$i]); $j++) {
            $usersFullInfo[$i] = $users[$i];
        }
    }

    $conn = connectDatabase();

    for($i=0; $i < sizeof($users); $i++) {
        // Query will only be ran 5 times as picked users is never more than 5
        $userID = $usersFullInfo[$i]["UserID"];
        $query = "SELECT FirstName, LastName FROM User WHERE UserID = $userID";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $usersFullInfo[$i]['FirstName'] = $row["FirstName"];
                $usersFullInfo[$i]['LastName'] = $row["LastName"];
            }
        } else {
            echo "................ 0 RESULTS ..............";
        }
    }
    $conn->close();

    return $usersFullInfo;
}

function getRecommendUsers ($userAttribMap){
    $matchingUsers =  getMapOfSimilarUsers($userAttribMap);
    $pickedUsers = recommendMatchesFromMap( $matchingUsers);
    $pickedUsers = getUserNamesForUsers($pickedUsers);

    return $pickedUsers;
}


function returnMapOfSearchedUsers($name){
    $searchedUsersMap = array(array());

    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, FirstName, LastName FROM User WHERE CONCAT(firstName, ' ', lastName) LIKE CONCAT('%',?,'%')");
    $stmt->bind_param("s", $name);
    $stmt->bind_result($userID, $firstName, $lastName);

    if($stmt->execute()){
        $counter = 0;
        while ($stmt->fetch()) {
            // Check to make sure user is not banned
            if(!checkUserBanned($userID)) {
                $searchedUsersMap[$counter]['id'] = $userID;
                $searchedUsersMap[$counter]['FirstName'] = $firstName;
                $searchedUsersMap[$counter]['LastName'] = $lastName;
                $counter++;
            }
        }
    }else{
        echo "Error";
    }

    $stmt->close();
    $conn->close();

    return $searchedUsersMap;
}

function returnMapOfAllUsers(){
    $searchedUsersMap = array(array());

    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, FirstName, LastName FROM User");
    $stmt->bind_result($userID, $firstName, $lastName);

    if($stmt->execute()){
        $counter = 0;
        while ($stmt->fetch()) {
            // Check to make sure user is not banned
            if(!checkUserBanned($userID)) {
                $searchedUsersMap[$counter]['id'] = $userID;
                $searchedUsersMap[$counter]['FirstName'] = $firstName;
                $searchedUsersMap[$counter]['LastName'] = $lastName;
                $counter++;
            }
        }
    }else{
        echo "Error";
    }

    $stmt->close();
    $conn->close();

    return $searchedUsersMap;
}

function getProfileAttributesForMapOfUsers($usersMap) {
    if(!is_array_empty($usersMap)) {
        $profileAttributesMap = array();
        $counter = 0;
        while ($counter < sizeof($usersMap)) {
            $userId = $usersMap[$counter]['id'];
            $profileAttributesMap = getUserProfileAttributes($userId);
            if (sizeof($profileAttributesMap) > 0) {
                $usersMap[$counter]['sex'] = $profileAttributesMap['sex'];
                $usersMap[$counter]['countryID'] = $profileAttributesMap['countryID'];
                $usersMap[$counter]['occupationID'] = $profileAttributesMap['occupationID'];
                $usersMap[$counter]['smoker'] = $profileAttributesMap['smoker'];
                $usersMap[$counter]['drinker'] = $profileAttributesMap['drinker'];
            } else {
                print "<br> This user has no profile information <br>";
            }
            $counter++;
        }
    }

    return $usersMap;

}

function is_array_empty($a){
    foreach($a as $elm)
        if(!empty($elm)) return false;
    return true;
}

function addMatches($from, $to){
	$conn = connectDatabase();
		
    	$stmt = $conn->prepare("INSERT INTO Connections (UserIDOne, UserIDTwo, Accepted) VALUES (?, ?, 0)");
    	$stmt->bind_param("ii", $from, $to);

	if($stmt->execute() === TRUE) {
		//echo "Match request has been sent";
		//echo " db values. $from . $to";
	} else {
		echo "Error: " . $stmt . "<br>" . $conn->error;
	}
	
	$stmt->close();
    	$conn->close();
}

function acceptMatches($from, $to){
	$conn = connectDatabase();
		
    	$stmt = $conn->prepare("UPDATE Connections SET Accepted = 1 WHERE UserIDOne = ? AND UserIDTwo = ?");
    	$stmt->bind_param("ii", $from, $to);

	if($stmt->execute() === TRUE) {
		echo "Match request was accepted";
		echo " db values. $from . $to";
	} else {
		echo "Error: " . $stmt . "<br>" . $conn->error;
	}
	
	$stmt->close();
    	$conn->close();
}

function rejectMatches($from, $to){
	$conn = connectDatabase();
		
    	$stmt = $conn->prepare("DELETE FROM Connections WHERE (Accepted=0 AND UserIDOne=? AND UserIDTwo=?)");
    	$stmt->bind_param("ii", $from, $to);

	if($stmt->execute() === TRUE) {
		echo "Match request was rejected";
		echo " db values. $from . $to";
	} else {
		echo "Error: " . $stmt . "<br>" . $conn->error;
	}
	
	$stmt->close();
    	$conn->close();
}

function getAllMatches($id) {
	$conn = connectDatabase();
	$stmt = $conn->prepare("SELECT DISTINCT FirstName, LastName, UserID FROM User FULL JOIN Connections WHERE (UserID = UserIDTwo AND UserIDOne = ? AND Accepted=1)");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($firstName, $lastName, $userID);
    if($stmt->execute()){
        while($stmt->fetch()) {
            echo "<li onclick=\"clicked_name(" . $userID . ")\" class=\"list-group-item highlight\">
						<img class=\"picture\" src=\"profile_pictures/" . returnProfilePicture($userID) . "\">
						<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
				    </li>";

        }
    }
    
    $stmt->close();
	$conn->close();
}

function getAllRequests($id) {
	$conn = connectDatabase();
	$stmt = $conn->prepare("SELECT DISTINCT FirstName, LastName, UserID FROM User FULL JOIN Connections WHERE (UserID = UserIDOne AND UserIDTwo = ? AND Accepted=0)");
    $stmt->bind_param("i", $id);
    $stmt->bind_result($firstName, $lastName, $userID);
    if($stmt->execute()){
        while($stmt->fetch()) {
        	echo "<li class=\"list-group-item highlight\">
						<img class=\"picture\" src=\"data:image/jpeg;base64,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\">
						<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
						<form method=\"post\">
							<input type=\"submit\" class=\"btn btn-primary purple floatRight\" value=\"Reject\" name=\"reject\">
							<input type=\"submit\" class=\"btn btn-primary purple floatRight\" value=\"Accept\" name=\"accept\">
						</form>		    
			        </li>";         
	}
    }
    $stmt->close();
	$conn->close();
}

function getAllRequestsInMap(){

    $conn = connectDatabase();
    $currentUserID = $_SESSION['user_id'];

    $query = "SELECT DISTINCT FirstName, LastName, UserID FROM User FULL JOIN Connections WHERE (UserID = UserIDOne AND UserIDTwo = $currentUserID AND Accepted=0)";

        // Array of request users
    $requestUsersArray = array();
    // Matching users attributes
    $requestMap = array();
    // Execute query
    $result = $conn->query($query);

    $count = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $requestMap['UserID'] = $row["UserID"];
            $requestMap['FirstName'] = $row["FirstName"];
            $requestMap['LastName'] = $row["LastName"];

            $requestUsersArray[$count] = $requestMap;
            $count++;
        }
    } else {
        echo "................ 0 RESULTS ..............";
    }

    $conn->close();
    return $requestUsersArray;

}
function isFriendOrRequestSent($userId1, $userId2) {

    $conn = connectDatabase();
    $query = "SELECT Accepted FROM Connections WHERE (UserIDOne =  '$userId1' AND UserIDTwo =  '$userId2') OR (UserIDOne =  '$userId2' AND UserIDTwo =  '$userId1')";

    $result = $conn->query($query);

    $accepted = "";
    $answer = "";
   
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $accepted = $row["Accepted"];
        }
    }

    $conn->close();

    if($accepted == null){
       

        $answer = "N";

    }
    else if ($accepted == 1) {
        

        $answer = "F";
    }
    else if ($accepted == 0) {
        

        $answer = "R";

    }
    $accepted = "";

    return $answer;


}