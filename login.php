<!DOCTYPE html>
<html>
<head>
    <?php require_once ("resources/templates/header.php"); ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
    <link rel="stylesheet" href="css/loginStyles.css">
</head>

<body class = "loginBackground">
<?php
require_once ("resources/library/databaseFunctions.php");
//require_once ("resources/templates/navigation.php");
?>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="img\pulling_professionals_logo3.png" class="brand_logo" alt="Logo">
                    </div>
                </div>
                    <div class="d-flex justify-content-center form_container">
                    <?php
					
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	header("Location: http://hive.csis.ul.ie/cs4116/group01/home.php");
}
session_start();
$email = $_POST['email'];
$password = $_POST['password'];
if (isset($_POST['login']))
{
    if (!(checkUniqueEmail($email)))
    {
        $hash_password = getUserPasswordHash($email);
        if (password_verify($_POST['password'], $hash_password))
        {
            // echo 'Password works';
            $user_id = getUserID($email);
	    if(isset($_POST['remember'])) {
		$hash = hash("sha256", $email.$password);
		setcookie('hash', $hash, time()+60*60*7);
	    }
	    $user_admin = checkUserAdmin($user_id);
            $user_banned = checkUserBanned($user_id);
	        $user_name = getUserFullname($user_id);
            $_SESSION['user_banned'] = $user_banned;
            if ($_SESSION['user_banned'] == true)
            {
                header("location: login.php");
            }
            else
            {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user_id;
		        $_SESSION['user_name'] = $user_name;
                $_SESSION['email'] = $email;
                $_SESSION['user_admin'] = $user_admin;		        
                	if ($user_admin)
                	{
                    	header("location: admin.php");
                	}
                	else
                	{
                	    header("location: home.php");
                	}
            	}	
        	}else{
				$errorMessage = "Incorrect password";
			}
    	}else{
			$errorMessage = "Account does not exist";
		}
}

?>
                    <form action ='login.php' method="POST">
                        <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="email" class="form-control input_user" value="" placeholder="email">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" name="password" class="form-control input_pass" value="" placeholder="password">
                        </div>
						<?php
							echo "<p class = \"userInfoForm\">" . $errorMessage . "</p>";
						?>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customControlInline" name="remember">
                                <label class="custom-control-label" for="customControlInline">Remember me</label>
                            </div>
                        </div>
                            <div class="d-flex justify-content-center mt-3 login_container">
                            <input type="submit" class="btn btn-primary purple" value="Login" name="login" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                    </div>
                    </form>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-center links">
                        Don't have an account? <a href="http://hive.csis.ul.ie/cs4116/group01/registration.php" class="ml-2">Sign Up</a>
                    </div>
                                    </div>
            </div>
        </div>
    </div>
<?php
    include "resources/templates/footer.php"
?>
</body>
</html>
