<?php
ini_set("session.cookie_httponly","1");
session_start();

function connectDatabase(){
    $config = require_once('resources/database_config.php');
    
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

function returnAllUsers(){
	echo "<script>console.log(\"Hello world\")</script>";
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User");
    $stmt->bind_result($userID,$email, $firstName, $lastName);

    if($stmt->execute()){
		while ($stmt->fetch()) {
			if(checkUserBanned($userID)){
				$banned = "Yes";
			}else{
				$banned = "No";
			}
			if($banned == "No"){
				echo "<li class=\"list-group-item highlighted\">
						<img class=\"picture\" src=\"data:image/jpeg;base64,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\">
						<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
						<button class=\"btn btn-primary floatRight\" type=\"button\">Add</button>
						</li>";
			}else{
				echo "<li class=\"list-group-item list-group-item-danger\">
						<img class=\"picture\" src=\"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxITEhUSEhIVFRUVFRUVFRcVFhUVFRYXFRcWFxYXFxcYHSggGBolHRUXITEhJSkrLi4uFx8zODMsNygtLisBCgoKBQUFDgUFDisZExkrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrKysrK//AABEIAOEA4QMBIgACEQEDEQH/xAAcAAAABwEBAAAAAAAAAAAAAAAAAQIDBAUGBwj/xAA7EAABAwIDBgMFBwQBBQAAAAABAAIRAwQFITEGEkFRcYETImEHMpGhsUJSYsHR4fAUIzPxchYkNVOC/8QAFAEBAAAAAAAAAAAAAAAAAAAAAP/EABQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AOloBBGEAhGgEaAoQRoIEwjRooQJIQQe4DMqBd4oxgkkAc3GAgnInOA1MLn2N7etbLaR33fhG60dzmVhcSx65rE79Z4HIOgfJB227xm3p+/WYPSQT8AoR2ts/wD3N+a4aWP+/wDGD9U3vuH2uwmD8UHfKO0lq73azVMpX1N3uvaehBXnh91HMj14J+2xaow5OI7n5Qg9Ego1yjZn2hObDa8lv3tSOvNbq12tsnkNbcMk6Ay3PqckF5CEINcCJCNAmEEpBAlCEpBAiEIS4RQgSiS4RQgSglQggWEaIJSAI0SNAEaAQQBIe6EslZXanaVlLyCC7U8YQNbT7RtpZDPLL1K5xjOK1q5l790cGjIQnMSu31nzEk6D/Sbp4YBnUM8hr/OyCmj7onvl3IGacFs7jA+Stbms1uWnw/LNUV9fj8XyH1QOPbTaNST8fmodW5HCPgoFxeg8Cf8A6H5KE+qDz+MoLN9wIg6JFKsMx8FWb56o21IKC08TLLXP9k/b1zqQIORkTmOIVXRrdBylPmuYj01j9EHQtkdpq9CA2oKtPjTcc2j8JOi63h18yswPYddQdQeRC8w07hwORI/nNdD2H218MtZV6b44jk4ceqDsqCRQqhzQ5pkEAg8CCnEBIQlBBAlBKRICRQlQiIQFCCNBAEpJRhAoIIBBAaJGo99cimxz3GAASgzW3O0X9Ozw6Z/uP0jVo4lc0ph9V8akmST8+qfxe9Naq+u86nyj0Gn85qVZzSZvOyc4ZD7o1z9UDxayiIJBPJUmI4tn+Q4d0dao6ocsgNTmnKWz7iJcCG+oMn1gmO5I6IMvc3lR58sjpl8TE/NQGWL3GdemfzW/OENjys3u28PpHyUa6w941b2OQ+CDFHDCPeLR6an5JTrFo1B+iuLqk/POPQQqe5o8fN3JP5oGjTYkyPX5Jo0wEbBnCAncwpBOQ17fskBkmE+acDU9oQMAhO0pboe0qK4cifgjp1HN0MhB1v2ZbYR/21Y5fYJ4enRdYavLltWkhzSWvBmfX19F3v2fbRC6twHZVaflePoehQapGgggJElIIEokqEIQJQSoRIEJQSQjCBSNEjQGsV7SL+KbaIMb5l3PdHDuVtCVyfb+93rlw1DAB8B+p+SCjtWh9TeOYbmBw9B/OSlVLepWdAybq52Yj5IrCgYAMifM7qdGjsrPxYEcOA/U8T0+SB+1o0KLRMEjiY4cgP8Aaj3mNMHu0y48yMu05BV9e/G9us8zok5ZN/n8Kqr0vdoZJOX7IJF9tFcHQQOUqiuMUqn3oz6A/RafDdj6jmyRnz/T9U9c7I7uo+SDBVml2Yc6eRP5qP8A0z+MrfDZ1oGnZNVMIAQYoWj+P0S22eei1L7IclFqWqChFGDKVWgjRWNW3UK5Zl+iCDUpA6fuoxZwPY8/0TzjmnYDhB/dBDYS10/wrZbDbQG2uG1J8phj/wDiePbVZXw+B7HmlWlTddB/nqg9VUnhwDhmCJCWsh7NcW8a2DHGXU8u3Ba9ASCNBASCNEgCCCCBkI0kJSAwlJKNAVQ5LiGNVvEuX+tQ/CSuy4rV3aL3cmk/JcNoPLq5PqSTx1QXHiDQGPvOPDkAq3GcR3WuIMfZE8fWPy/0pDnwQOp+IzPqYyVDdDxK1Jp0NST0agu7C33abW/bf5nuOvr+i2OyezgqHxnjLRo9FTYPaGpUA5kDsMz/AD0XVLakGNDQIAAQIZbNAgBRry3BBkKY6pCg3lfJBnq9ATAVRc0xPFXVYznoq25IzQU1ekFWXLYVvcFVNyUFbVaoF3SVnUCj1m5IMzdUuISKFTgeyn3dPP8AkKuIgoJpz66jrxSajAc/4Ut58rXD/RTlIT0PDkeKDceyPEyy58I6PBHcZhdqC847OVzRuabxluvHw/0vRlJ0gHmEC0EEEBII0EBQgjQQRAUoJoFLBQOBGkApSCr2nfFtUP4T81xiy9+Opd6+n85rsu1bZtag/CVxi3MOPMgoF75O+46wfmSPomLenNZh/C/5lOVDDX9QPlKfw+mC5vrl2JQbvZK28zDyG98Vt5VJsxZgN34106BX26giVioFdysblqqq3VBX1gq65Yrd49PioFy3igo7piqLgK8vIEqnuCEFc8JioFLqRzUWoc0Fdf0JzVLUZwWkrLPXzof6FAu1BdTc3i3MfVOWVXgiwz3yOBCYLC1xAygoLqmMw7kvQmAXHiW9J/NjfjAXnm0rA5+ma7N7Nb/ftvCOtMkD1acx+iDYIIIIAggggCCCCCAlApAKUgcBRgpDSlIKjaoE27wNSIC4pfO3HA8siu442JZHVcl2sw3daHjQ590FbVqA05HEz+RVngNMlzB6x2j91UWdPeoNI+w5wd3kj6q92VtnV3v3ZAZHz1Qb2rtVb28U8zAEkRCbft5a5ec5+mnVVmN4Xa0meZpcTymSfSM1ibyjSE+60jRhc+o/uG5DuUHTf+qrZ+QqtnhmnXVN4SMwc1xu0uKLnxAOcHdcQfg6fquo7L04aGAkjhPJBKrVVV316GgklWuN0CwSuf4xd70hxyQIxDaBvAKir4pVcZAgdEm6q8KYAJyB4npy6qtxWwqU4Dy4uMECCRBmTvTwIAiOeiCxo2dd5nfjvwUoYdXbxDgqSypVN3fEwDHEfNarCrtxb5p7oIu5lmIWexulmCtZeEahUuKUZagrMLd52z0+KexalFQ9Ao1Bu69kcx8yrLGGzUEfdCBrD38OC6r7LSRVInLwzPXeEfz1XK7VmfXIrr/sot/JVqH8LB2zP5IOgoIBHCBKCNBASNCEaCraUsJkJbSgcSgUgI0DGJMlh9FzXad0Mcw8CY+f6rqThIgrm+3NHdJy1QY3Ba/hVi12bHgbw+h7LpGxls0eNu8akjpqFzhtGZPb4ro+x9cEQBG60Nd6kEkH4GOyBzanZupW81N5GXfsRoVVOwSmy08FgNKqCKgqAH3s53nNzkyc10Wm2RHwUS5sQeCDjlls05tRz3O8QuLnGQSS50yS52epK6PsdY7lOHuDiPdOcgcipJwjPP8AVWllZtpjLU69OSCNtNSBpTxXGMcp+Yrs+OAmkeh1XHsQEvKBrC7Gl727J9eAHAK4fRaQA7edGm9BieUqDhrc4VsGFBVVLVvAE9Sl0baFZtopmuIQVl4xQatOQp1XNN1QGgTpxQZm5pbtVoHoT8U7jNX+7HoB3A/dTre0bvl+pJnos/d1yazjrn9EFxYZrt/s1obtoD95zj20XEsKb5mx9o5L0Ps7a+HQps5NCCzCNBGgJBGggCCCCCmCUCkhKQOBKTbSlhApY/2j2s0Wv4h0diteFSba0d60f6EH5oOZUKGR9I/ZafZG7bTrGm4geIMp+83MD6qLStJZEZuNMfLJUe0VMsqboycwkyOBnL6IO0Wz8k+RIWe2YxQV7enVnMjdd6Obk4fHPurxj0DgpJqplMpTqsBNNfIzORQVeNPlhErk+K0iyoZ55LrWNPZumBmB8/VcwxtrN6X1GtkwN4jPpzQRcPILvVaBtPJZqo00nNMgzy5LTW1cPbI5ICcAq66ep1cKtraoGadOSoOL1GCA926CYk6fzJW1NsBZHbWpmxvU/kgLEMTpsYadKHOMy8TGf1MLP0W5oU2qVaUZKDV7J2Bq1qTG/ZBe7lDea9BWfujoPouS+zbDHB28ct/yk8hyXX6TYCBSNBBAEEEEAQQQQU6MIgjQG1LCQEsIFKDjlLfoVG8wpqZvR5HdCgyxpNZSY/g17O8LHXtI1HOedXOJ7StnjAPgtHKfiVUtsobMcPyQR/ZliBZWrWrjk7+4zqMn/LdXSwuMU6/gXFOuNWuk9DqPguxW9UOaHNMggEHmDogdeJyR3NnTqMLHiWlIJ4o21ZGuSCkxq2FKjDJgcySsDjeBuyqwDOYK6BjWJ0S0skunIkcFmsUxphpimG5DPeP5IMZTtHE+bX1Vtand0UWtilEH3x2z+ijvxQHKk0vPdoHcoL8VQ4evFRKrM0qjpJyMZpBdKAlgtqa+/cOA0ZDe/FbTE7wUaTqh4Dyjm46Bc7zJ3jmSST1OZQG1sK6wq2zBKr7ShvOVlSrZuI0AMfRB2v2d02+DvDMnI+hHBbJcS2B2qFu8h4O44+bl1XYrLE6NUTTqNd0OaCYgi3kYQBBBGgJBGggpwjRBGEBpQRBGEBpYZIM8klHvIKipZB9NwI6dVW1aQ3APQK4r1dwzOQJy5qlxKrulzeGZHQ5oOe7WgDTnC1nst2hFSn/TVD5mTuE/aaNR2WI2qqlzw0eo7pq0D6RpmkYqNIII1n+fVB3eq2QQq2ts5Se3N1STmYqPAPaYUrCLo1aNOo4AOLRvAaTxjupiDI4hgdvTB/tuB57zj9SqN+E25kuZJJyBJPTVbbEQ52QCqali/Ut0QYW4sBve6GjkE5TpBugV7iNrmSdVUV2QgTvpVISVGYCSkX91A3Gn/kfyCDL7Y32+8MB8rdPUnUqtZRz+H0R4mN5+XCSp1iwOYHDoeyAqHlBPw6lXWz2H+PNMQHn3Z4ngCqt1HKPVTMOuH0HtqNBlhBj0CDS2WAkHepAAjJ9KppPEStvgVuWxNmGnmHHdVvhD6FywVmgS8CYj4FXNC3DRkgFBpgSI9E8gjQBBBBAaJBBBTpQSUoIDCUESNAajXLnDQT01UkJUIM5XpuJmD3VbidMkeoC2pot5BUV5ZuNZ8CQ4NPcZIOXDDjvGo8QGEwD9ongnsPw91Ql2g5raX+zVR+6XN3Wen5p8YJ4FKTnvVGtHSCfyQX2zbIogKyqMUbCKcUwFNJQQnhV93XGitq1KRkqDELVwzQUmIuBlUNamXGArm/rNbzKz17euOQ8o5Dj1KBm6rhstZrxdy9Aqi6dAKlPVfiByQVAZm488h0Cs8PtjSa0O0eJy5puzAa4FwkD6Louymx3ju8aqf7YB3BzJ0PQIMg/Cy8ZKba7O3Li1rgY4HiAul3ezdIsgAB4IHxMZK/t7FoaBExzQUmyezv8ATNkVCZzLYELUBIayEtAaCCCAIIIIAggggpwlBJCWEBhKSQpNK2nM5D5lAyE7Tpk5ASrOjZsjMKSykBoIQV1KxP2lJZbgafupJTYIlAltuCqbbK2DbRz2j/G5lTs1w3vkStCCk3du2ox1N3uvaWnoRCDOYfVDmAt0IBCcqOWR2fxF1vUfaVSZpOLQTyByPQiD3Wl8WUEhrslWYkZClCooF/VyQZbFaKzVdmei118JVHeUA0SgoaqrbwSp9w/MqK5soIrxAXXfZvjDRa0qFV0OAhhOQI4Cea5XTtS57W83ALX3dGKe6Mo0jhCDrFSgHEEjTMdeadAWJ2S2skClcHMZB/McN79Vt2OBEgyDxQGggjQBBBBAEaCCAIIIIKmnSJ0CnUMNJ1MdFP3R0UhpEIK9mHhpkmY4JOZcEu7uYMBFbGSgsWhB7oCA0UG4rSYCBbqhJT9KnCRb0oCfKASnQUwSnKbkGU252ZNwBXoAC4p9vEb90+vIrLYDi5PldIIMOB1BGoIXViFktrtlvFJuLcBtcRvDRtUDgeTo0KCE+4Crruuq+0xOTuPBa5phzXZEHkQp7WByCL4ZcqLHTGS3NvZ5LEbSmahaEGb8GU7TtFPt7f0WgwvZyo8hz2llP7RORI47oOZKCDs/gkD+oeMsxSHM8XdBonMQAGS0V6+dBugCGAaBo0CqKlmX7zuQyQV9rYT5h+ytsKx2rbndPmZyPDoVJwm38sc1Bxe23XERmg2WG7Q0asCd1x4O/Iq4C5TSoEhaXAcTqMETvAcDr2KDYo0za3DXiWntxHVPIAggggJBBBA7WTtPRBBBVXHvKXZIIIJ9b3Sq2nqgggs28EooIIEORsQQQPJBQQQcl28/8i7/AIM+ilYfoggg0dD/AB9lzrFv8p6oIINFsJ/lWsxrU9EEEGbvUVp7juh+iCCB3CNWqNtL/kHRGgggUdVYYT75RIIL/Bf8j+yvAgggJBBBASCCCD//2Q==\">
						<p class=\"larger\">" . $firstName . " " . $lastName . "</p>
						<button class=\"btn btn-primary floatRight\" type=\"button\">Add</button>
						</li>";
			}
		}
	}
			?>
	</table>
<?php
    
    $stmt->close();
    $conn->close();
}

function passwordResetEmail($email){
	$to = "alanfinnin@outlook.com";
	$subject = "My subject";
	$txt = "Hello world!";
	$headers = "From: webmaster@example.com" . "\r\n";

	mail($to,$subject,$txt,$headers);
}

function getUserData($userID){
    $conn = connectDatabase();

    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        //gg
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    if (!($result = $stmt->get_result())) {
        echo "Getting result set failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();

}

function createUser($email, $firstName, $lastName, $passwordHash){
    $conn = connectDatabase();
    $defaultAdminValue = 0;

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO `User` (`Email`, `FirstName`, `LastName`, `PasswordHash`, `Admin`) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $email,$firstName, $lastName, $passwordHash, $defaultAdminValue);

    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt . "<br>" . $conn->error;
    }

    $conn->close();
}


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
        return true;
    } else {
        return false;
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

function banUser($userID, $startDate, $endDate){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO `Banned_Users` (`UserID`, `Start_Date`, `End_Date`) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $startDate, $endDate);

    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error . "<br>" . $conn->error;
    }

    $conn->close();
}

function permaBanUser($userID){
    $conn = connectDatabase();

    $startDate = date("Y-m-d");
    $d = strtotime("+10 Years");
    $endDate = date("Y-m-d", $d);

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO `Banned_Users` (`UserID`, `Start_Date`, `End_Date`) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $startDate, $endDate);

    if ($stmt->execute() === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error . "<br>" . $conn->error;
    }

    $conn->close();
}

function unbanUser($userID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM Banned_Users WHERE UserID=?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute() === TRUE) {
        echo "record deleted successfully";
    } else {
        echo "Error: " . $stmt->error . "<br>" . $conn->error;
    }

    $conn->close();
}



