<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";

$db = get_database();

$user = verify_login($db);

verify_parameter($_POST, 'booking', $db, "");
verify_parameter($_POST, 'action', $db, "");

$booking = get_booking($db, $_POST['booking']);

verify(can_edit_booking($user, $booking), $db, "x", "error");

if($_POST['item'])
{
	$items = array($_POST['item']);
}
else
{
	$items = $_POST['items'];
}

if($_POST['action'] == "collect")
{
	verify(can_collect($user, $booking, $items), $db, "x", "error");

	$statement = $db->prepare("UPDATE itembookings SET collected=now() WHERE item=? AND booking=?");

	foreach($items as $item)
	{
		$statement->bind_param("sd", $item, $_POST['booking']);
		$statement->execute();

        add_item_event($db, $item, $user, "Item Collected for <a href=\"booking.php?id=$booking[id]\">$booking[reason]</a>", true);
	}
}
else if($_POST['action'] = "return")
{
	$statement = $db->prepare("UPDATE itembookings SET returned=now() WHERE item=? AND booking=?");

	foreach($items as $item)
	{
		$statement->bind_param("sd", $item, $_POST['booking']);
		$statement->execute();

        add_item_event($db, $item, $user, "Item Returned from <a href=\"booking.php?id=$booking[id]\">$booking[reason]</a>", true);
	}

	$statement = $db->prepare("SELECT * FROM itembookings WHERE booking=? AND returned IS NULL");
	$statement->bind_param("d", $_POST['booking']);
	$statement->execute();

	$unreturned = fetch($statement);

	if(count($unreturned) == 0)
	{
		$statement = $db->prepare("UPDATE bookings SET returned=now() WHERE id=?");
		$statement->bind_param("d", $_POST['booking']);
		$statement->execute();
	}
}
else if($_POST['action'] = "remove")
{
	verify(can_collect($user, $booking, $items), $db, "x", "error");

	$statement = $db->prepare("DELETE FROM itembookings WHERE item=? AND booking=?");

	foreach($items as $item)
	{
		$statement->bind_param("sd", $item['code'], $_POST['booking']);
		$statement->execute();
	}
}

redirect($db);

?>