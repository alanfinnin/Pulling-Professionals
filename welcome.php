<?php
    require_once("resources/templates/header.php");
?>
    <body class="mainBlock">
    <?php
                require_once("resources/library/databaseFunctions.php");
		require_once("resources/library/loginFunctions.php");
            ?>
        <?php
	    checkedLoggedIn();
            echo $_SESSION['loggedin'];
            echo "<br>";
            echo $_SESSION['user_id'];
            echo "<br>";
            echo $_SESSION['email'];
            echo "<br>";
            echo $_SESSION['user_admin'];
            echo "<br>";
	    echo $_SESSION['user_banned'];
            echo "<br>";
         ?>
<?php
    include "resources/templates/footer.php"
?>