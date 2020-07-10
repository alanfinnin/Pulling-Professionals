<!DOCTYPE html>
<html>
<head>
<!-- Style.css -->
	<link rel="stylesheet" href="css/style.css">
<?php require_once("resources/templates/header.php");?>
</head>
<body class = "mainBlock">
	<?php
    require_once("resources/templates/navigation.php");
	?>
	<div class="container" id="mainBlock2">
		<?php
			include "resources/library/databaseFunctions.php";
			connectDatabase();
        $userAttribMap = getUserProfileAttributes($_SESSION['user_id']);
        ?>
		<?php
        if($_SESSION['loggedin'] == 0){
            header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
            exit;
        }
        ?>
        <div id = "wholePage" >
        <!-- Page Heading -->
        <div class="d-flex justify-content-center">
            <div class="col-12 text-center caption" id="theHeading">
                <h1 class="text-uppercase pt-3 pb-2" >The Firehose</h1>
            </div>
        </div>
        <!-- Re-Mix-->
            <form method="POST" action="home.php">
                <div class="d-flex justify-content-center" >
                    <input type="submit" class="btn btn-primary" name="reMix" value="Re-Mix" id="reMix"/>
                </div>
            </form>
        <?php

        //Suggestions Heading
        echo " <div class=\"d-flex justify-content-center text-primary\" id=\"suggestionsHeading\">
            <h4 class=\"text-uppercase pt-3 pb-2\" >Suggestions</h4>
        </div>";
        echo " <div class=\"container-fluid mt-5\" id=\"usersBox\"> ";
        echo "<div class=\"row justify-content-center \" id = \"rowRecommendedUsers\" \">";


        $currentRecommendedUsers = getRecommendUsers($userAttribMap);

        if(is_array_empty($currentRecommendedUsers)) {
            echo " <div class=\"d-flex justify-content-center\" id=\"noSuggestionsHeading\">
            <h4 class=\"text-uppercase pt-3 pb-2 text-danger\" >No Recommendations: <br>       Add Some Attributes</h4>
        </div>";
        }
        else {

            printRecommendedProfiles($currentRecommendedUsers, 4);

        }



        echo "</div></div>";




        $allUsersMap = returnMapOfAllUsers();

        for ($i = 0; $i < sizeof($allUsersMap); $i++) {
            $userId = $allUsersMap[$i]['id'];

            if(isset($_POST[$userId])) {
                addMatches($_SESSION['user_id'], $userId);
                

            }
        }

        if(isset($_POST['reMix'])){



        }
        ?>

    </div>
    </div>

    <!-- Modal Pop-Up -->
    <div class="modal" id="myModal">
        <div class ="modal-dialog">
            <div class = "modal-content">
                <div class="modal-header">
                    <button class = "close" type = "button" data-dismiss="modal">x</button>
                    <h2 class = "modal-title"> You Have Sent A Friend Request </h2>
                </div>
            </div>
        </div>
    </div>



    <!-- End Modal -->






<?php include "resources/templates/footer.php" ?>

</body>
</html>

<?php
function printRecommendedProfiles($usersFullInfo, $limit)
{

    for ($i = 0; $i < sizeof($usersFullInfo) && $i < $limit; $i++) {
        // Need to check if banned
        $firstName = $usersFullInfo[$i]['FirstName'];
        $lastName = $usersFullInfo[$i]['LastName'];
        $weight = $usersFullInfo[$i]['Weight'];
        $userId = $usersFullInfo[$i]['UserID'];
        $characteristicsMsg = "";

       
        if($weight == 1 ) {
            $characteristicsMsg = "You have 1 characteristic in common!";
        }
        else {
            $characteristicsMsg = "You have $weight characteristics in common!";
        }

        $profilePageAddress = "http://hive.csis.ul.ie/cs4116/group01/other_users.php?uid=$userId";
        echo "<div class=\"card col-8 col-sm-5 col-md-3 col-lg-2 col-xl-2\" id = \"userCard\">
                <div class=\"card-header\" id=\"userCardHeader\">
                    <h4 class=\"card-title\"> <a href=\"$profilePageAddress\">$firstName<br>$lastName</a></h4>
                </div>
                
                <img   alt=\"Card image\" class=\"card-img-top\" src=\"profile_pictures/" . returnProfilePicture($userId). "\"> 
                <div class=\"card-body\" id=\"userCardBody\">
                     <p class=\"card-text\">$characteristicsMsg.      </p>
                     <form method=\"POST\" action=\"home.php\">
                     
                     <input type =\"submit\" class=\"pull-left btn btn-primary btn\" data-toggle= \"modal\" data-target = \"#myModal\" id=\"btnFriendRequest\" value =\"Add\" name= \"$userId\"/>
                   
                    </form>
                </div>
          </div>";
    }

}


/*
function printSearchedProfiles($usersFullInfo)
{

    //Suggestions Heading
    echo " <div class=\"d-flex justify-content-center text-primary\" id=\"searchHeading\">
            <h4 class=\"text-uppercase pt-3 pb-2\" >Search Results</h4>
        </div>";
    echo " <div class=\"container-fluid mt-5\" id=\"usersSearchBox\"> ";


    for ($i = 0; $i < sizeof($usersFullInfo); $i++) {
        // Need to check if banned
        $firstName = $usersFullInfo[$i]['FirstName'];
        $lastName = $usersFullInfo[$i]['LastName'];
        $userId = $usersFullInfo[$i]['id'];


        echo "<div class=\"row justify-content-center\" id =\"rowSearchedUsers\"> 
                 <div class=\"card mb-3 col-8 col-sm-10 col-md-10 col-lg-10 col-xl-10\" id = \"userCardSearch\">
                    <div class=\"row no-gutters\">
                        <div class=\"col-md-4\">
                          <img src=\"img/user_profile.png\" class=\"card-img\" alt=\"Card image\" id=\"imgSearch\">
                        </div>
                        <div class=\"col-md-8\">
                          <div class=\"card-body\">
                            <h4 class=\"card-title\"><a href=\"#\">$firstName $lastName</a></h4>
                             <a href=\"#\" class=\"btn btn-primary btn-lg\" name=$userId>Send Friend Request</a>
                          </div>
                        </div>
                 </div> 
                 </div>
              </div>";
    }
    echo "</div></div>";


}
*/
?>
