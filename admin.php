<!DOCTYPE html>
<html>
<head>
<?php
/* ini_set("display_errors", "true");
ini_set("html_errors", "true");
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRICT); */

 require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
<?php 
require_once("resources/templates/navigation.php");
require_once("resources/library/adminFunctions.php");
require_once("resources/library/databaseFunctions.php");
require_once("resources/library/loginFunctions.php");
?>

<?php
	checkedAdmin();
	checkIfBanned();
?>

<style>
.list-group{
  margin-top: 0;
}
.list-group-item{
  padding-top: 0.75rem;
}
#side{
  background: none!important;
  border: none;
  padding: 0!important;
  color: #007bff;
  cursor: pointer;
}
#side:hover {
  background: none!important;
  border: none;
  padding: 0!important;
  color: #0056b3;
  cursor: pointer;
}
</style>

<div class="container border-danger">
    <h1 style="margin-top: 25px;">Admin Dashboard</h1>
</div>

<div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
				<form action="admin.php" method="POST">	
					<ul class="list-group">
						<li class="list-group-item"><input id="side" type="submit" name="userList" value="View All Users"></input></li>
						<li class="list-group-item"><input id="side" type="submit" name="bannedUsers" value="View Banned Users"></input></li>
						<li class="list-group-item"><input id="side" type="submit" name="manageAdmin" value="Manage Administrators"></input></li>
						<li class="list-group-item"><input id="side" type="submit" name="interestList" value="Manage Interests"></input></li>
						<li class="list-group-item"><input id="side" type="submit" name="occupationList" value="Manage Occupations"></input></li>
					</ul>
				</form>
			</div>
            <div class="col-auto col-md-9">
                <div class="table-responsive">
					<form action="admin.php" method="POST">
						<?php
						///
						//Handlers for View All Users Table
						///
						if(isset($_POST['userList'])){ //RETURN ALL USERS MENU
							returnUsers();
						}
						if(isset($_POST['table1_banClicked'])){ //RETURN ALL USERS MENU
							permaBanUser($_POST['table1_banClicked']);
							returnUsers();
						}
						if(isset($_POST['table1_unbanClicked'])){ //RETURN ALL USERS MENU
							unbanUser($_POST['table1_unbanClicked']);
							returnUsers();
						}
						if(isset($_POST['table1_grantAdminClicked'])){ //RETURN ALL USERS MENU
							grantAdmin($_POST['table1_grantAdminClicked']);
							returnUsers();
						}
						if(isset($_POST['table1_revokeAdminClicked'])){ //RETURN ALL USERS MENU
							revokeAdmin($_POST['table1_revokeAdminClicked']);
							returnUsers();
						}
						if(isset($_POST['table1_deleteUserClicked'])){ //RETURN ALL USERS MENU
							deleteUser($_POST['table1_deleteUserClicked']);
							returnUsers();
						}
						
						///
						//Handlers for Banned Users Table
						///
						if(isset($_POST['bannedUsers'])){ //RETURN BANNED USERS TABLE
							returnBannedUsers();
						}
						if(isset($_POST['table2_unbanClicked'])){ //HANDLE UNBAN CLICK
							unbanUser($_POST['table2_unbanClicked']);
							returnBannedUsers();
						}
						
						///
						//Handlers for Manage Admins Table
						///
						if(isset($_POST['addAdmin'])){
							returnAdmins();
						}
						if(isset($_POST['manageAdmin'])){ 
							returnAdmins();
						}
						if(isset($_POST['table3_grantAdminClicked'])){ 
							grantAdmin($_POST['table3_grantAdminClicked']);
							returnAdmins();
						}
						if(isset($_POST['table3_revokeAdminClicked'])){ 
							revokeAdmin($_POST['table3_revokeAdminClicked']);
							returnAdmins();
						}

						///
						//Handlers for Manage Interests
						///
						if(isset($_POST['interestList'])){ 
							returnInterestsAdmin();
						}
						if(isset($_POST['addInterestClicked'])){
							addInterestAdmin($_POST['interestDescription']);
							returnInterestsAdmin();
						}
						if(isset($_POST['removeInterestClicked'])){
							removeInterest($_POST['removeInterestClicked']);
							returnInterestsAdmin();
						}

						///
						//Handlers for Manage Occupations
						///
						if(isset($_POST['occupationList'])){ 
							returnOccupationsAdmin();
						}
						if(isset($_POST['addOccupationClicked'])){
							addOccupationAdmin($_POST['occupationDescription']);
							returnOccupationsAdmin();
						}
						if(isset($_POST['removeOccupationClicked'])){
							removeOccupation($_POST['removeOccupationClicked']);
							returnOccupationsAdmin();
						}

						///
						//Handlers for View Reports
						///
						?>
						</tbody>
					</form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("resources/templates/footer.php"); ?>
</body>
</html>