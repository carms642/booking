<?php
include_once "includes/template.php";
include_once "includes/database.php";
include_once "includes/validate.php";

$db = get_database();

$current_user = verify_login($db);

$id = $_GET['id'];
if($id)
{
	$user = get_user($db, $id);
}
else
{
	$user = $current_user;
}

$bookings = get_user_bookings($db, $user);
$bookings = get_booking_items($db, $bookings);

if($_GET['action'] == "edit" && can_edit_user($current_user, $user))
{
	print_template('templates/page.php', array(
			'title' => $user['name'],
			'user' => $current_user,
			'content' => new Template('templates/edituser.php', array(
					'current_user' => $current_user,			
					'user' => $user,
					'events' => $bookings))));
}
else
{
	print_template('templates/page.php', array(
			'title' => $user['name'],
			'user' => $current_user,
			'content' => new Template('templates/user.php', array(
					'current_user' => $current_user,
					'user' => $user,
					'events' => $bookings))));
}
?>