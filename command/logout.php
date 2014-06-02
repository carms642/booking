<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";

$db = get_database();
$user = verify_login($db);

$statement = $db->prepare("DELETE FROM usersessions WHERE session=?");
$statement->bind_param("s", $_COOKIE['mrlsession']);
$statement->execute();

// redirect browser back to login page
redirect($db, "../index.php");
?>