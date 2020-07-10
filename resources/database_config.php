<?php

/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/

$config = array(
    "db" => array(
        "db_host" => "localhost",
        "db_user" => "group01",
        "db_pass_encoded" => "WGtNdmJYb3plekpsU2taTVlTcFFhQT09",
        "db_name" => "dbgroup01",
        "db_port" => "3306",
    ),
);
?>
