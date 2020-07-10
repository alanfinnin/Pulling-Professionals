<?php
			include "resources/library/databaseFunctions.php";
			
?>
<?php
    $conn = connectDatabase();
    $id = $_SESSION['user_id'];    
    $result = mysqli_query($conn, "SELECT FirstName, LastName, UserID FROM User FULL JOIN Connections WHERE (UserID = UserIDOne AND UserIDTwo = $id AND Accepted=0)");

$data = array();
while ($row = mysqli_fetch_object($result))
{
    array_push($data, $row);
}

echo json_encode($data);
exit();?>