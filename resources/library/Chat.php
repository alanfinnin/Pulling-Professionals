<?php
include("databaseFunctions.php");
class Chat{
    private $chatTable = 'Messages';
	private $chatUsersTable = 'User';
	private $profileTable = 'Profile';
	private $chatActivityTable = 'Chat_Activity';
	private $dbConnect = false;
    public function __construct(){
        if(!$this->dbConnect){ 
			$conn = connectDatabase();
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect));
		}
		$data= array();
		while ($row = mysqli_fetch_array($result)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error($this->dbConnect));
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}		
	public function chatUsers($userid){
		/* $sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE UserID != '$userid'"; */
		$sqlQuery = "SELECT DISTINCT FirstName, LastName, UserID, Online FROM User FULL JOIN Connections WHERE (UserID = UserIDTwo AND UserIDOne = ".$userid." AND Accepted=1)";
			
		return  $this->getData($sqlQuery);
	}
	public function getUserDetails($userid){
		$sqlQuery = "
			SELECT * FROM ".$this->chatUsersTable." 
			WHERE UserID = '$userid'";
		return  $this->getData($sqlQuery);
	}
	public function getUserAvatar($userid){
		/* $sqlQuery = "
			SELECT PhotoPath 
			FROM ".$this->profileTable." 
			WHERE UserID = '$userid'";
		$userResult = $this->getData($sqlQuery);
		$userAvatar = ''; */
		
		return  '/cs4116/group01/profile_pictures/' . $userid . '.jpg';
	}	
	public function insertChat($reciever_userid, $user_id, $chat_message) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatTable." 
			(RecipientUserID, SenderUserID, MessageBody, Status) 
			VALUES ('".$reciever_userid."', '".$user_id."', '".$chat_message."', '1')";
		$result = mysqli_query($this->dbConnect, $sqlInsert);
		if(!$result){
			return ('Error in query: '. mysqli_error($this->dbConnect));
		} else {
			$conversation = $this->getUserChat($user_id, $reciever_userid);
			$data = array(
				"conversation" => $conversation			
			);
			echo json_encode($data);	
		}
	}
	public function getUserChat($from_user_id, $to_user_id) {
		$fromUserAvatar = $this->getUserAvatar($from_user_id);	
		if(!file_exists('/var/www/html' . $fromUserAvatar)){
			$fromUserAvatar = '/cs4116/group01/img/profile-image.png';
		}
		$toUserAvatar = $this->getUserAvatar($to_user_id);			
		if(!file_exists('/var/www/html' . $toUserAvatar)){
			$toUserAvatar = '/cs4116/group01/img/profile-image.png';
		}
		
		
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable." 
			WHERE (SenderUserID = '".$from_user_id."' 
			AND RecipientUserID = '".$to_user_id."') 
			OR (SenderUserID = '".$to_user_id."' 
			AND RecipientUserID = '".$from_user_id."') 
			ORDER BY MessageTime ASC";
		$userChat = $this->getData($sqlQuery);	
		$conversation = '<ul>';
		foreach($userChat as $chat){
			$user_name = '';
			if($chat["SenderUserID"] == $from_user_id) {
				$conversation .= '<li class="sent">';
				$conversation .= '<img width="22px" height="22px" src="'.$fromUserAvatar.'" alt="" />';
			} else {
				$conversation .= '<li class="replies">';
				$conversation .= '<img width="22px" height="22px" src="'.$toUserAvatar.'" alt="" />';
			}			
			$conversation .= '<p>'.$chat["MessageBody"].'</p>';			
			$conversation .= '</li>';
		}		
		$conversation .= '</ul>';
		return $conversation;
	}
	public function showUserChat($from_user_id, $to_user_id) {		
		$userDetails = $this->getUserDetails($to_user_id);
		$toUserAvatar = '';
		foreach ($userDetails as $user) {
			$toUserAvatar = $this->getUserAvatar($to_user_id);		
			if(!file_exists('/var/www/html' . $toUserAvatar)){
				$toUserAvatar = '/cs4116/group01/img/profile-image.png';
			}
			
			$userSection = '<img src="'.$toUserAvatar.'" alt="" />
				<p>'.$user['FirstName'].' '.$user['LastName'].'</p>';
		}		
		// get user conversation
		$conversation = $this->getUserChat($from_user_id, $to_user_id);	
		// update chat user read status		
		$sqlUpdate = "
			UPDATE ".$this->chatTable." 
			SET Status = '0' 
			WHERE SenderUserID = '".$to_user_id."' AND RecipientUserID = '".$from_user_id."' AND Status = '1'";
		mysqli_query($this->dbConnect, $sqlUpdate);		
		// update users current chat session
		$sqlUserUpdate = "
			UPDATE ".$this->chatUsersTable." 
			SET current_session = '".$to_user_id."' 
			WHERE userid = '".$from_user_id."'";
		mysqli_query($this->dbConnect, $sqlUserUpdate);		
		$data = array(
			"userSection" => $userSection,
			"conversation" => $conversation			
		 );
		 echo json_encode($data);		
	}	
	public function getUnreadMessageCount($senderUserid, $recieverUserid) {
		$sqlQuery = "
			SELECT * FROM ".$this->chatTable."  
			WHERE SenderUserID = '$senderUserid' AND RecipientUserID = '$recieverUserid' AND status = '1'";
		$numRows = $this->getNumRows($sqlQuery);
		$output = '';
		if($numRows > 0){
			$output = $numRows;
		}
		return $output;
	}	
	public function updateTypingStatus($is_type, $loginDetailsId) {		
		$sqlUpdate = "
			UPDATE ".$this->chatActivityTable." 
			SET IsTyping = '".$is_type."' 
			WHERE ID = '".$loginDetailsId."'";
		mysqli_query($this->dbConnect, $sqlUpdate);
	}		
	public function fetchIsTypeStatus($userId){
		$sqlQuery = "
		SELECT IsTyping FROM ".$this->chatActivityTable." 
		WHERE UserID = '".$userId."' ORDER BY LastActivity DESC LIMIT 1"; 
		$result =  $this->getData($sqlQuery);
		$output = '';
		foreach($result as $row) {
			if($row["IsTyping"] == 'yes'){
				$output = ' - <small><em>Typing...</em></small>';
			}
		}
		return $output;
	}		
	public function insertUserLoginDetails($userId) {		
		$sqlInsert = "
			INSERT INTO ".$this->chatActivityTable."(UserID) 
			VALUES ('".$userId."')";
		mysqli_query($this->dbConnect, $sqlInsert);
		$lastInsertId = mysqli_insert_id($this->dbConnect);
        return $lastInsertId;		
	}	
	public function updateLastActivity($loginDetailsId) {		
		$sqlUpdate = "
			UPDATE ".$this->chatActivityTable." 
			SET LastActivity = now() 
			WHERE ID = '".$loginDetailsId."'";
		mysqli_query($this->dbConnect, $sqlUpdate);
	}	
	public function getUserLastActivity($userId) {
		$sqlQuery = "
			SELECT LastActivity FROM ".$this->chatActivityTable." 
			WHERE UserID = '$userId' ORDER BY LastActivity DESC LIMIT 1";
		$result =  $this->getData($sqlQuery);
		foreach($result as $row) {
			return $row['LastActivity'];
		}
	}	
}
?>