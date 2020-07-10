<?php session_start(); ?>
<nav class="navbar navbar-dark navbar-expand-md sticky-top bg-dark navigation-clean" style="padding: 8px;">
	<div class="container"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
		<div class="collapse navbar-collapse" id="navcol-1"><img src="/cs4116/group01/img/pulling_professionals_logo3.png" height="60" style="padding: 0px;margin: 0px;">
			<ul class="nav navbar-nav ml-auto">
				<?php 
					if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){ ?>
					<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/home.php">Home</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/users.php">Users</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/chat.php">Chat</a></li>
					<?php if(isset($_SESSION['user_admin']) && $_SESSION['user_admin'] == true){ ?>
						<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/admin.php?userList=View+All+Users">Admin Dashboard</a></li>
					<?php } ?>
					<li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" href="#">My Profile</a>
						<div class="dropdown-menu" role="menu">
							<a class="dropdown-item" role="presentation" href="/cs4116/group01/profile.php">View Profile</a>
							<a class="dropdown-item" role="presentation" href="/cs4116/group01/matches.php">View Matches</a>
							<a class="dropdown-item" role="presentation" href="/cs4116/group01/requests.php">View Requests</a>
							<a class="dropdown-item" role="presentation" href="/cs4116/group01/settings.php">Settings</a>
							<div class="dropdown-divider"></div> 
							<a class="dropdown-item text-warning" name="logout"role="presentation" href="/cs4116/group01/logout.php">Logout</a>
						</div>
					</li>
				<?php }else{ ?>
				    <li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/index.php">Home</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/registration.php">Register</a></li>
					<li class="nav-item" role="presentation"><a class="nav-link" href="/cs4116/group01/login.php">Login</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</nav>