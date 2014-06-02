<?php
include_once "includes/validate.php";
include_once "includes/database.php";
include_once "includes/template.php";

$db = get_database();

$user = verify_login($db);
verify_parameter($_GET, "id", $db, "Unknown Booking");

$statement = $db->prepare("SELECT bookings.*, users.name FROM bookings, users WHERE bookings.id=? AND users.id=bookings.user");
$statement->bind_param("s",$_GET['id']);
$statement->execute();
$result = fetch($statement);
$booking = $result[0];
$booking['items'] = get_booking_items($db,$_GET['id']);

$events = get_booking_events($db, $booking['id']);

$main = new Template('templates/page.php', array(
    	'title' => $booking['reason'],
		'user' => $user,
		'content' => new Template('templates/booking.php', array('booking' => $booking, 'user' => $user, 'events' => $events))));

$main->render();

?>