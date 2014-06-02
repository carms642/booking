<?php
include_once "includes/validate.php";
include_once "includes/database.php";
include_once "includes/template.php";

$db = get_database();

$user = verify_login($db);

$show = $_GET['show'];
if(!$show)
{
    $show = "active";
}

$bookings = get_bookings($db, $user, $show);
$bookings = get_booking_items($db, $bookings);

if($show != "all")
{
    $morelink = array('link' => "bookings.php?show=all", 'text' => 'Show all bookings');
}

print_template('templates/page.php', array(
        'user' => $user,
		'content' => new Template('templates/events.php', array(
				'events' => $bookings, 'user' => $user, 'morelink' => $morelink))));

?>