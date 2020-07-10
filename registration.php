<!DOCTYPE html>
<html>
<head>
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
		<?php 
		//require_once("resources/templates/navigation.php");
		require_once("resources/library/adminFunctions.php");
		require_once("resources/library/databaseFunctions.php");
		require_once("resources/library/databaseFunctions.php");
		/*ini_set("display_errors", "true");
			ini_set("display_errors", "true");
			ini_set("html_errors", "true");
			ini_set("error_reporting", "true");
			error_reporting(E_ALL|E_STRICT);*/
			
		?>
		<div class="container">
			<div class="row registration">
				<div class = "col-sm-2"></div>
					<div class="col-sm-8 nopadding mainBox">
						<?php
							$message = "";
							if(isset($_POST['submit'])){
								$email = $_POST['email'];
								$firstName = $_POST['firstName'];
								$lastName = $_POST['lastName'];
								$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
								  
								  //$message = "Success! You entered: ".$firstName . $password;
								  
								  if(checkUniqueEmail($email)){
									createUser($email, $firstName, $lastName, $password);
									header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
								}
							}    
							?>
						<div class="purpleHeader" id="header">
							<p class = "larger registrationHeader">Registration</p><br><br>
						</div>
						<form class="userInfoForm " method="POST" action="registration.php">
							
							<br><br><input type="text" id="firstName" placeholder="First Name" name="firstName" onkeyup='infoCheck();'>
							
							<input type="text" id="lastName" placeholder="Last Name" name="lastName" onkeyup='infoCheck();'>
							
							<br><br><input type="email" class="form-control" id="email" placeholder="Email Address" name="email" onkeyup='infoCheck();'><br><br>

							<hr><br>

							<input type="password" id="password1" placeholder="Password"><br><br>
							
							<input type="password" id="password2" class="" placeholder="Confirm Password" onkeyup='check();' name ="password"><br><br>
							<p id="passwordResult"></p><br>
							
							<button type="submit" class="btn btn-primary disabled purple" value="Register" id="submitButton" name="submit">Submit</button><br><br>
							
						</form>
					</div>
					<script>
						document.getElementById("submitButton").disabled = true;
						var passwordCheck = false;
						var check = function() {
							if (document.getElementById("password2").value != null) {
								var firstPassword = (document.getElementById('password1').value);
								var secondPassword = (document.getElementById('password2').value);
								
								if(firstPassword != secondPassword)
										document.getElementById('password2').className += " alert alert-danger";
								else if(firstPassword == secondPassword){
									document.getElementById('password2').className = "alert alert-success";
									if(document.getElementById('password2').value.length > 8){
										document.getElementById('passwordResult').innerHTML  = "";
										
										passwordCheck = true;
										infoCheck();
									}else{
										document.getElementById('passwordResult').innerHTML  = "Password is too short";
									}
								}
							}
						}
						var infoCheck = function(){
							if(document.getElementById("firstName").value.trim() != "") {
								if(document.getElementById("lastName").value.trim() != "") {
									if(document.getElementById("email").value != "") {
										if(document.getElementById("password1").value != "") {
											if(document.getElementById("password2").value != "") {
												if(passwordCheck){
													console.log("pass");
													document.getElementById('submitButton').className="btn btn-success purple";
													document.getElementById("submitButton").disabled = false;
												}
											}else document.getElementById('passwordResult').innerHTML  = "Please confirm password";
										}else document.getElementById('passwordResult').innerHTML  = "Please enter password";
									}else document.getElementById('passwordResult').innerHTML  = "Please enter email";
								} else document.getElementById('passwordResult').innerHTML  = "Please enter last name";
							}else document.getElementById('passwordResult').innerHTML  = "Please enter name";
						}
					</script>
			</div>
		</div>
		
<?php include("resources/templates/footer.php"); ?>
</body>
</html>