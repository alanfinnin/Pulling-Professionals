<?php
    // If logged in is not set or the user is not logged in, then redirect to login page
    function checkedLoggedIn() {
        if (!(isset($_SESSION['loggedin'])) || (!($_SESSION['loggedin'] == true))) {
            header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
            exit();
        }
    }

    // If logged in is not set and the user is not an admin, go to login page
    // else if logged in is true and the user is not an admin, go to homepage
    function checkedAdmin() {
        if (!(isset($_SESSION['loggedin'])) && (!($_SESSION['user_admin'] == true))) {
            header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
            exit();
        }
        else if (($_SESSION['loggedin'] == true) && (!($_SESSION['user_admin'] == true))) {
            header("Location: http://hive.csis.ul.ie/cs4116/group01/index.php");
            exit();
        }
    }

    // If user is banned, redirect to homepage
    function checkIfBanned() {
        if(($_SESSION['user_banned'] == true)) {
            header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
            exit();
        }
    }
?>