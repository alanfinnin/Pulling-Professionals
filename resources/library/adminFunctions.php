<?php
require "databaseFunctions.php";

function returnUsers(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName, Admin FROM User");
    $stmt->bind_result($userID, $email, $firstName, $lastName, $admin);
	?>	
		<table class="table table-light" style="margin-top: 10px;">
		<thead class="thead-light">
			<tr><th colspan="6">All Users</th></tr>
			<tr>
				<th>User ID</th>
				<th>Username</th>
				<th>Email</th>
				<th>Banned</th>
				<th>Admin</th>
				<th></th>
			</tr>
		</thead>
	<tbody>
	<?php
    if($stmt->execute()){
		while ($stmt->fetch()) {
			if(checkUserBanned($userID)){
				$banned = "Yes";
				echo "<tr class=\"table-danger\">";
			}else{
				$banned = "No";
				echo "<tr>";
			}
			
			if(checkUserAdmin($userID)){
				$admin = "Yes";
			}else{
				$admin = "No";
			}
			?>
			
				<td><?php echo htmlentities($userID);?></td>
				<td><?php echo htmlentities($firstName . " " . $lastName);?></td>
				<td><?php echo htmlentities($email);?></td>
				<td><?php echo htmlentities($banned);?></td>
				<td><?php echo htmlentities($admin);?></td>
				<td>
				<div class="dropdown">
					<button class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Actions</button>
					<div class="dropdown-menu" role="menu">
						<?php
						//Banning Button Handlers
						if($banned == "No" && $userID != $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_banClicked" value="<?php echo $userID; ?>">Ban</button><?php
						}elseif($banned == "No" && $userID == $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_banClicked" value="<?php echo $userID; ?>" disabled>Ban</button><?php
						}elseif($userID == $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_unbanClicked" value="<?php echo $userID; ?>" disabled>Unban</button><?php
						}else{
							?><button class="dropdown-item" type="submit" name="table1_unbanClicked" value="<?php echo $userID; ?>">Unban</button><?php
						}
						
						//Admin Button Handlers
						if($admin == "No" && $userID != $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_grantAdminClicked" value="<?php echo $userID; ?>">Grant Admin</button><?php
						}elseif($admin == "No" && $userID == $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_grantAdminClicked" value="<?php echo $userID; ?>" disabled>Grant Admin</button><?php
						}elseif($userID == $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_revokeAdminClicked" value="<?php echo $userID; ?>" disabled>Revoke Admin</button><?php
						}else{
							?><button class="dropdown-item" type="submit" name="table1_revokeAdminClicked" value="<?php echo $userID; ?>">Revoke Admin</button><?php
						}
						
						if($userID != $_SESSION['user_id']){
							?><button class="dropdown-item" type="submit" name="table1_deleteUserClicked" value="<?php echo $userID; ?>">Delete User</button><?php
						}else{
							?><button class="dropdown-item" type="submit" name="table1_deleteUserClicked" value="<?php echo $userID; ?>" disabled>Delete User</button><?php
						}
						
						?>
						<button class="dropdown-item" role="presentation" value="<?php echo $userID; ?>" disabled>View Profile</button>
						<button class="dropdown-item" role="presentation" value="<?php echo $userID; ?>" disabled>Message User</button>
					</div>
				</div>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
<?php
    }
    $conn->close();
}

function returnBannedUsers(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT Banned_Users.UserID, User.FirstName, User.LastName, User.Email, Banned_Users.End_Date FROM Banned_Users INNER JOIN User ON Banned_Users.UserID=User.UserID;");
    $stmt->bind_result($userID, $firstName, $lastName, $email, $bannedUntil);
	?>
		<table class="table table-light" style="margin-top: 10px;">
		<thead class="thead-light">
			<tr><th colspan="6">Banned Users</th></tr>
			<tr>
				<th>User ID</th>
				<th>User Name</th>
				<th>Email</th>
				<th>Banned Until</th>
				<th>Action</th>
			</tr>
		</thead>
	<tbody>
	<?php
    if($stmt->execute()){
		while ($stmt->fetch()) {
			?>
			<tr>
				<td><?php echo htmlentities($userID);?></td>
				<td><?php echo htmlentities($firstName . " " . $lastName);?></td>
				<td><?php echo htmlentities($email);?></td>
				<td><?php echo htmlentities($bannedUntil);?></td>
				<td>
				<button type="submit" name="table2_unbanClicked" value="<?php echo $userID; ?>" class="btn btn-danger btn-sm">Unban</button>
				</form></td>
			</tr>
			<?php
		}
		?>
	</table>
<?php
    }
    $conn->close();
}

function returnAdmins(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User WHERE Admin > 0");
    $stmt->bind_result($userID,$email, $firstName, $lastName);
	?>

		<table class="table table-light" style="margin-top: 10px;">
		<thead class="thead-light">
			<tr><th colspan="6">Manage Administrator</th></tr>
			<tr>
				<th>User ID</th>
				<th>Username</th>
				<th>Email</th>
				<th>Admin</th>
				<th>Action</th>
			</tr>
		</thead>
	<tbody>
    <?php
    if($stmt->execute()){
		while ($stmt->fetch()) {
			if(checkUserAdmin($userID)){
				$admin = "Yes";
			}else{
				$admin = "No";
			}
			?>
			<tr>
				<td><?php echo htmlentities($userID);?></td>
				<td><?php echo htmlentities($firstName . " " . $lastName);?></td>
				<td><?php echo htmlentities($email);?></td>
				<td><?php echo htmlentities($admin);?></td>
				<td>
				<?php
						
						if($admin == "No" && $userID != $_SESSION['user_id']){
							?><button class="btn btn-danger btn-sm" type="submit" name="table3_grantAdminClicked" value="<?php echo $userID; ?>">Grant Admin</button><?php
						}elseif($admin == "No" && $userID == $_SESSION['user_id']){
							?><button class="btn btn-danger btn-sm" type="submit" name="table3_grantAdminClicked" value="<?php echo $userID; ?>" disabled>Grant Admin</button><?php
						}elseif($userID == $_SESSION['user_id']){
							?><button class="btn btn-danger btn-sm" type="submit" name="table3_revokeAdminClicked" value="<?php echo $userID; ?>" disabled>Revoke Admin</button><?php
						}else{
							?><button class="btn btn-danger btn-sm" type="submit" name="table3_revokeAdminClicked" value="<?php echo $userID; ?>">Revoke Admin</button><?php
						}
				?>
				</form></td>
			</tr>
			<?php
		}
		?>
	</table>
<?php
    }
    $stmt->close();
    $conn->close();
}

function grantAdmin($userID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("UPDATE User SET Admin = 1 WHERE UserID=?");
    $stmt->bind_param("i", $userID);
	
	if($_SESSION['user_id'] == $userID){
		$_SESSION['user_admin'] = true;
	}

    if ($stmt->execute() === TRUE) {
        //echo "record updated successfully";
		displaySuccessAlert("Successfully granted admin to User " . $userID);
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function revokeAdmin($userID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("UPDATE User SET Admin = 0 WHERE UserID=?");
    $stmt->bind_param("i", $userID);
	
	if($_SESSION['user_id'] == $userID){
		$_SESSION['user_admin'] = false;
	}

    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("User has been revoked as admin");
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function banUser($userID, $startDate, $endDate){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO Banned_Users (UserID, Start_Date, End_Date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $startDate, $endDate);

    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("User has been banned until " . $endDate);
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function permaBanUser($userID){
    $conn = connectDatabase();

    $startDate = date("Y-m-d");
    $d = strtotime("+10 Years");
    $endDate = date("Y-m-d", $d);

    // prepare and bind
    $stmt = $conn->prepare("INSERT INTO Banned_Users (UserID, Start_Date, End_Date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userID, $startDate, $endDate);

    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("User has been banned until " . $endDate);
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function unbanUser($userID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM Banned_Users WHERE UserID=?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("User has been unbanned");
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function deleteUser($userID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM User WHERE UserID=?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("User has been permanently deleted");
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function displaySuccessAlert($message){
	?>
	<div class="alert alert-success alert-dismissible">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Success!</strong> <?php echo $message; ?>.
	</div>
	<?php
}

function displayErrorAlert($message){
	?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong>Error!</strong> <?php echo $message; ?>.
	</div>
	<?php
}

function returnInterestsAdmin(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT InterestID, Description FROM Available_Interests");
    $stmt->bind_result($interestID, $description);
	?>

		<table class="table table-light" style="margin-top: 10px;">
		<thead class="thead-light">
			<tr>
				<th>Manage Interests</th>
				<th>Add Interest:&nbsp;<input class="md-form mt-0 form-control" type="text" name="interestDescription" style="width: 50% !important; margin-left: 10px;"></input></th>
				<th><button class="btn btn-success btn-sm" type="submit" name="addInterestClicked">Add Interest</button></th>
			</tr>
			<tr>
				<th colspan="2">Interests</th>
				<th>Action</th>
			</tr>
		</thead>
	<tbody>
    <?php
    if($stmt->execute()){
		while ($stmt->fetch()) {
			?>
			<tr>
				<td colspan="2"><?php echo htmlentities($description);?></td>
				<td>
				<button class="btn btn-danger btn-sm" type="submit" name="removeInterestClicked" value="<?php echo $interestID; ?>">Remove Interest</button>
				</form></td>
			</tr>
			<?php
		}
		?>
	<!--<th>Add Interests: <input class="md-form mt-0 form-control" type="text" name="addInterestClicked" />
		<input type="submit" />
	</th>-->
	</table>
<?php
    }
    $stmt->close();
    $conn->close();
}

function removeInterest($interestID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM Available_Interests WHERE InterestID = ?");
    $stmt->bind_param("i", $interestID);
	
    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("Interest has been removed");
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function returnOccupationsAdmin(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT JobID, Description FROM Available_Occupations");
    $stmt->bind_result($jobID, $description);
	?>

		<table class="table table-light" style="margin-top: 10px;">
		<thead class="thead-light">
			<tr>
				<th>Manage Occupations</th>
				<th>Add Occupation:&nbsp;<input class="md-form mt-0 form-control" type="text" name="occupationDescription" style="width: 50% !important; margin-left: 10px;"></input></th>
				<th><button class="btn btn-success btn-sm" type="submit" name="addOccupationClicked">Add Occupation</button></th>
			</tr>
			<tr>
				<th colspan="2">Occupations</th>
				<th>Action</th>
			</tr>
		</thead>
	<tbody>
    <?php
    if($stmt->execute()){
		while ($stmt->fetch()) {
			?>
			<tr>
				<td colspan="2"><?php echo htmlentities($description);?></td>
				<td><button class="btn btn-danger btn-sm" type="submit" name="removeOccupationClicked" value="<?php echo $jobID; ?>">Remove Occupation</button>
				</form></td>
			</tr>
			<?php
		}
		?>
	<!--<th>Add Occupation: <input class="md-form mt-0 form-control" type="text" name="addOccupationClicked" />
		<input type="submit" />
	</th> -->
	</table>
<?php
    }
    $stmt->close();
    $conn->close();
}

function removeOccupation($jobID){
    $conn = connectDatabase();

    // prepare and bind
    $stmt = $conn->prepare("DELETE FROM Available_Occupations WHERE JobID = ?");
    $stmt->bind_param("i", $jobID);
	
    if ($stmt->execute() === TRUE) {
        displaySuccessAlert("Occupation has been removed");
    } else {
        displayErrorAlert("" . $stmt->error . " | " . $conn->error);
    }

    $conn->close();
}

function addInterestAdmin($description){
	if(!$description.trim() == ""){
		if(addInterest($description)){
			displaySuccessAlert("Added interest to database");
		}else{
			displayErrorAlert("Failed to add interest : Database Error");
		}
	}else{
		displayErrorAlert("Failed to add interest, description must contain characters!");
	}
}

function addOccupationAdmin($description){
	if(!$description.trim() == ""){
		if(addOccupation($description)){
			displaySuccessAlert("Added occupation to database");
		}else{
			displayErrorAlert("Failed to add occupation : Database Error");
		}
	}else{
		displayErrorAlert("Failed to add occupation, description must contain characters!");
	}
}


