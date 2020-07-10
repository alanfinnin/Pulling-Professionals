<?php
require "databaseFunctions.php";

function returnUsers(){
    $conn = connectDatabase();
    $stmt = $conn->prepare("SELECT UserID, Email, FirstName, LastName FROM User");
    $stmt->bind_result($userID,$email, $firstName, $lastName);

    if($stmt->execute()){
		while ($stmt->fetch()) {
			if(checkUserBanned($userID)){
				$banned = "Yes";
			}else{
				$banned = "No";
			}
			?>
			<tr>
				<td><?php echo htmlentities($userID);?></td>
				<td><?php echo htmlentities($firstName . " " . $lastName);?></td>
				<td><?php echo htmlentities($email);?></td>
				<td><?php echo htmlentities($banned);?></td>
				<td>
				<?php
						if($banned == "No"){
							echo "<button type="submit" name="banClicked" class="btn btn-danger">Ban</button>";
						}else{
							echo "<button type="submit" name="unbanClicked" class="btn btn-danger">Unban</button>";
						}
				?>
				</form></td>
			</tr>
			<?php
		}
		?>
	</table>
<?php
    }
    $stmt->close();
    $conn->close();
}
