<?php
include_once "includes/validate.php";
include_once "includes/database.php";
include_once "includes/template.php";

$db = get_database();

$user = verify_admin($db);

$users = get_users($db);

print_template('templates/page.php', array(
    	'user' => $user,
		'content' => new Template('templates/userlist.php', array(
				'users' => $users))));

?>