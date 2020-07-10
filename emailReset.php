	<?php
		require "resources/templates/header.php";
	?>
	<body>
		<?php
			require "resources/library/databaseFunctions.php";
			if(isset($_POST['submit'])){
				$email = $_POST['email'];
				passwordResetEmail($email);
			}
		?>
		<div class = "userInfoForm">
			<h1>Please Enter email</h1><br>
			
			<form class="userInfoForm" method="POST" action="emailReset.php">
				<input type="email" class="form-control" id="email" placeholder="Email Address" name="email"><br>
			
				<input type="submit" class="btn btn-primary" value="Submit" name="submit"><br><br>
			</form>
		</div>

	</body>
</html>