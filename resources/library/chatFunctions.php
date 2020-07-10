<?php
require "databaseFunctions.php";

function getUserChat($userID){
    $conversation = array();
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName, Admin FROM User");
    $stmt->bind_result($userID, $email, $firstName, $lastName, $admin);

    if($stmt->execute()){
		while ($row = $stmt->fetch()) {
            array_push($conversation, $row);
        }
    }else{
        echo "DB getUserChat Error";
    }
    $stmt->close();
    $conn->close();

    return $conversation;
}

function getUserChat($userID){
    $conversation = array();
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT MessageID, MessageTime, SenderUserID, RecipientUserID, MessageBody, Status FROM Messages 
							WHERE (SenderUsedID = '".$from_user_id."' 
							AND RecipientUserID = '".$to_user_id."') 
							OR (SenderUsedID = '".$to_user_id."' 
							AND RecipientUserID = '".$from_user_id."') 
							ORDER BY MessageTime ASC");
							
    $stmt->bind_result($messageID, $messageTime, $senderUserID, $recipientUserID, $messageBody, $status);

    if($stmt->execute()){
		while ($row = $stmt->fetch()) {
            array_push($conversation, $row);
        }
    }else{
        echo "DB getUserChat Error";
    }
    $stmt->close();
    $conn->close();

    return $conversation;
}

function getUserChat($from_user_id, $to_user_id) {
	$conn = connectDatabase();
	$fromUserAvatar = $this->getUserAvatar($from_user_id);	
	$toUserAvatar = $this->getUserAvatar($to_user_id);			
	$stmt = $conn->prepare("
		SELECT * FROM Messages 
		WHERE (SenderUsedID = '".$from_user_id."' 
		AND RecipientUserID = '".$to_user_id."') 
		OR (SenderUsedID = '".$to_user_id."' 
		AND RecipientUserID = '".$from_user_id."') 
		ORDER BY MessageTime ASC");
	
	$conversation = '<ul>';
	foreach($userChat as $chat){
		$user_name = '';
		if($chat["SenderUserID"] == $from_user_id) {
			$conversation .= '<li class="sent">';
			$conversation .= '<img width="22px" height="22px" src="userpics/'.$fromUserAvatar.'" alt="" />';
		} else {
			$conversation .= '<li class="replies">';
			$conversation .= '<img width="22px" height="22px" src="userpics/'.$toUserAvatar.'" alt="" />';
		}			
		$conversation .= '<p>'.$chat["message"].'</p>';			
		$conversation .= '</li>';
	}		
	$conversation .= '</ul>';
	return $conversation;
}