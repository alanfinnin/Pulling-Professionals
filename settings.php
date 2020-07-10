<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/settingsPage.css">
    <?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
<?php
require_once("resources/templates/navigation.php");
require_once("resources/library/databaseFunctions.php");



?>
<div class="row justify-content-center" >
    <div class = "col-12 col-sm-12 col-md-11 col-lg-12 col-xl-10 text-center" id = "settingHeading" >
      <h1 class="text-uppercase font-weight-bold pt-3 pb-2 " > <i class="fa fa-cog" aria-hidden="true"></i> General Settings</h1>
    </div>
</div>

<!-- Parent Container -->
<div class="parent-container justify-content-center" id = "block">

                <?php
				
				
				if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != 1){
					header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
				}
				
                $new_email = $_POST['new-email'];
                $password = $_POST['password'];
                $userID = $_SESSION['user_id'];
                if(isset($_POST['change-email'])) {
                    $hash_password = getUserPasswordHash($_SESSION['email']);
                    if (password_verify($password, $hash_password)) {
                        if(checkUniqueEmail($new_email)) {
                            editUserEmail($userID,$new_email);
                            $_SESSION['email'] = $new_email;
                        }
                        else{
                            echo "Email already in use";

                        }
                    }
                    else
                    {
                        echo "Incorrect password";
                    }
                }

                ?>

                <!-- Left Container -->

                <div class="container" id = "emailContainer">
                    <h2 class="text-uppercase text-center pt-3 pb-2"  > <br> 
                        <i class="fa fa-wrench" aria-hidden="true"></i> Edit Email</h2>
                    <div class="row justify-content-center" id ="emailFields">
                        <!-- Edit Email -->
                        <form class="row justify-content-center" action="settings.php" method="post">
                            <div class="col-md-12">
                                <div class="form-group justify-content-center">
                                    <label for="new-email"> New E-mail  </label>
                                    <input class="form-control justify-content-center" type="email" name="new-email" placeholder="New Email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group justify-content-center align-items-center">
                                    <label for="password"> Password  </label>
                                    <input type="password" name="password" class="form-control input_pass" value="" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-12">
                                <hr class="mt-2 mb-3">
                                <div class="d-flex flex-wrap justify-content-center align-items-center">
                                    <input type="submit" class="btn btn-primary purple" name="change-email" value="Change Email">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



<?php

$current_password = $_POST['password-current'];
$new_password = ($_POST['password-new']);
$userID = $_SESSION['user_id'];

if(isset($_POST['changepw'])) {
    $hash_password = getUserPasswordHash($_SESSION['email']);
    if (password_verify($current_password, $hash_password)) {

        if(strlen($new_password) > 7) {
            $new_password = password_hash($_POST['password-new'],PASSWORD_DEFAULT);
            editUserPassword($userID, $new_password);
        }
        else {
            echo "Your new password must be at least 8 characters";
        }
    }
    else {
        echo "You entered an incorrect password";
    }
}

?>

<!-- Right Container -->


            <div class="container justify-content-center" id = "passwordContainer">
                <h2 class="text-uppercase text-center pt-3 pb-2" > <br>
                    <i class="fa fa-wrench" aria-hidden="true"></i>Edit Password</h2>
                <div class="row justify-content-center" id ="emailFields"">
                    <!-- Edit Password -->
                    <form class="row" action="settings.php" method="post">
                        <div class="col-md-12">
                            <div class="form-group justify-content-center">
                                <label for="account-pass">Current Password</label>
                                <input class="form-control justify-content-center" type="password" name="password-current" placeholder="Password">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group justify-content-center">
                                <label for="account-pass">New Password</label>
                                <input class="form-control justify-content-center" type="password" name="password-new" placeholder="New Password">
                            </div>
                        </div>
                        <div class="col-12">
                            <hr class="mt-2 mb-3">
                            <div class="d-flex flex-wrap justify-content-center">
                                <input type="submit" class="btn btn-primary purple" name="changepw" value="Change Password" id = "submitBtns">
                            </div>

                        </div>
                    </form>
                </div>
            </div>



</div>






<?php include("resources/templates/footer.php");?>

<!-- Font Awesome https://fontawesome.com/  https://use.fontawesome.com/releases/v5.10.1/js/all.js -->
<script src="https://kit.fontawesome.com/1c6045c7b3.js" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>

</body>
</html>