<?php
include_once "includes/template.php";
include_once "includes/database.php";
include_once "includes/validate.php";

$db = get_database();
$user = get_user($db);

if(!$user)
{
	$error = get_error($db);

	print_template('templates/page.php', array('content' => new Template('templates/login.php', array('error' => $error))));

	exit;
}

$bookings = get_user_bookings($db, $user, "overdue");
$bookings = get_booking_items($db, $bookings);

$statement = $db->prepare("SELECT tags.*, items.image FROM tags, itemtags, items WHERE itemtags.item=items.code AND itemtags.tag=tags.id GROUP BY tags.id ORDER BY tags.text");
$statement->execute();

$tags = fetch($statement);

if(is_admin($user))
{
	$links = array(array('link' => 'users.php', 'text' => 'Users'),
			array('link' => 'bookings.php', 'text' => 'Bookings'), array('link' => 'item.php?action=new', 'text' => 'Add Item'));
}
else
{
	$links = array(array('link' => 'user.php', 'text' => 'User'),
			array('link' => 'bookings.php', 'text' => 'Bookings'));
}

print_template('templates/page.php', array(
		'user' => $user,
		'content' => array(new Template('templates/taglist.php', array('links' => $links, 'tags' => $tags)), new Template('templates/events.php', array('events' => $bookings, 'user' => $user)) )));

?>