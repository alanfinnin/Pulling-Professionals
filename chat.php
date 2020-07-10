<?php 
//session_start();
/* ini_set("display_errors", "true");
ini_set("html_errors", "true");
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRICT); */

include('resources/templates/header.php');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chat</title>
<script src="js/jquery.min.js"></script>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>
<link href="css/chat.css" rel="stylesheet" id="bootstrap-css">
<script src="js/chat.js"></script>

<style>
.modal-dialog {
    width: 400px;
    margin: 30px auto;	
}
</style>
</head>

<body class="mainBlock">
<?php 
include("resources/templates/navigation.php");
?>
<div class="container" style="min-height:500px;">
<div class="container">		

	<br>		
	<?php if(isset($_SESSION['user_id']) && $_SESSION['user_id']) { ?> 	
		<div class="chat">	
			<div id="frame">		
				<div id="sidepanel">
					<div id="profile">
					<?php
					include ('resources/library/Chat.php');
					$chat = new Chat();
					$loggedUser = $chat->getUserDetails($_SESSION['user_id']);
					echo '<div class="wrap">';
					$currentSession = '';
					foreach ($loggedUser as $user) {
						$currentSession = $user['current_session'];
						$avatar = $chat->getUserAvatar($user['UserID']);
						if(!file_exists('/var/www/html' . $avatar)){
							$avatar = "/cs4116/group01/img/profile-image.png";
						}
						echo '<img id="profile-img" src="'.$avatar.'" class="online" alt="/cs4116/group01/img/profile-image.png" />';
						echo  '<p>'.$user['FirstName'].' '.$user['LastName'].'</p>';
							echo '<div id="status-options">';
							echo '<ul>';
								echo '<li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>';
								echo '<li id="status-away"><span class="status-circle"></span> <p>Away</p></li>';
								echo '<li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>';
								echo '<li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>';
							echo '</ul>';
							echo '</div>';
					}
					echo '</div>';
					?>
					</div>
					<div id="search">
						<p>Matches</p>	
					</div>
					<div id="contacts">	
					<?php
					echo '<ul>';
					$chatUsers = $chat->chatUsers($_SESSION['user_id']);
					foreach ($chatUsers as $user) {
						$avatar = $chat->getUserAvatar($user['UserID']);
						if(!file_exists('/var/www/html' . $avatar)){
							$avatar = "/cs4116/group01/img/profile-image.png";
						}
						
						$status = 'offline';						
						if($user['Online']) {
							$status = 'online';
						}
						$activeUser = '';
						if($user['UserID'] == $currentSession) {
							$activeUser = "active";
						}
						echo '<li id="'.$user['UserID'].'" class="contact '.$activeUser.'" data-touserid="'.$user['UserID'].'" data-tousername="'.$user['FirstName'].' '.$user['LastName'].'">';
						echo '<div class="wrap">';
						echo '<span id="status_'.$user['UserID'].'" class="contact-status '.$status.'"></span>';
						echo '<img src="'.$avatar.'" alt="" />';
						echo '<div class="meta">';
						echo '<p class="name">'.$user['FirstName'].' '.$user['LastName'].'<span id="unread_'.$user['UserID'].'" class="unread">'.$chat->getUnreadMessageCount($user['UserID'], $_SESSION['user_id']).'</span></p>';
						echo '<p class="preview"><span id="isTyping_'.$user['UserID'].'" class="isTyping"></span></p>';
						echo '</div>';
						echo '</div>';
						echo '</li>'; 
					}
					echo '</ul>';
					?>
					</div>
					<div id="bottom-bar">	
						
					</div>
				</div>			
				<div class="content" id="content"> 
					<div class="contact-profile" id="userSection">	
					<?php
					$userDetails = $chat->getUserDetails($currentSession);
					foreach ($userDetails as $user) {							
						$avatar = $chat->getUserAvatar($user['UserID']);
						if(!file_exists('/var/www/html' . $avatar)){
							$avatar = "/cs4116/group01/img/profile-image.png";
						}
						echo '<img src="'.$avatar.'" alt="" />';
							echo '<p>'.$user['FirstName'].' '.$user['LastName'].'</p>';
					}	
					?>						
					</div>
					<div class="messages" id="conversation">		
					<?php
					echo $chat->getUserChat($_SESSION['user_id'], $currentSession);						
					?>
					</div>
					<div class="message-input" id="replySection">				
						<div class="message-input" id="replyContainer">
							<div class="wrap">
								<input type="text" class="chatMessage" id="chatMessage<?php echo $currentSession; ?>" placeholder="Write your message..." />
								<button class="submit chatButton" id="chatButton<?php echo $currentSession; ?>"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>	
							</div>
						</div>					
					</div>
				</div>
			</div>
		</div>
	<?php } else { 
		header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
	} ?>
</div>	
</div>
<?php include('resources/templates/footer.php');?>