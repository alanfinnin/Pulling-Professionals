<?php      
	session_start();
        session_unset();
        session_destroy();

        header("Location: http://hive.csis.ul.ie/cs4116/group01/login.php");
        exit();
?>