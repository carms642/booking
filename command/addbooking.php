<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";
include_once "../includes/formatting.php";

$db = get_database();

$user = verify_login($db);
verify_parameter($_POST, 'item', $db, "No item to book");

if(!$_POST['booking'])
{
    // Check new booking details
	verify_parameter($_POST, 'reason', $db, "A reason for booking is required");

	$start = verify_time($db, "start", $_POST['start']);
	$end = verify_time($db, "end", $_POST['end']);

	verify_booking_times($db, $start, $end);
}
else
{
	// Check existing booking details
	$booking = get_booking($db, $_POST['booking']);

	verify(can_edit_booking($user, $booking), $db, "user", "User cannot edit booking");

	$start = strtotime($booking['start']);
	$end = strtotime($booking['end']);
}

if($_POST['collected'])
{
    //verify( , $db, "collected", "Booking must start today in order to be collected today");
}

// Check bookings don't conflict
$itembookings = get_item_bookings($db, $_POST['item']);
$overlaps = array();
foreach($itembookings as $itembooking)
{
	$itemstart = strtotime($itembooking['start']);
	$itemend = strtotime($itembooking['end']);
	if(overlap($start, $end, $itemstart, $itemend))
	{
		$overlaps[] = $itembooking;
	}
}
if(count($overlaps) > 0)
{
	$error = "Booking conflicts with ";
	$comma = false;
	foreach($overlaps as $itembooking)
	{
		$error .= "<div><a href=\"booking.php?id=$itembooking[id]\">$itembooking[reason]</a>: " . format_daterange($itembooking['start'], $itembooking['end'], true)."</div>";
	}
	redirect_error($db, "end", $error);
}

// Create booking, if necessary
if(!$_POST['booking'])
{
	if(is_admin($user) && $_POST['usersemail'])
	{
        $bookinguser = get_user($db, null, $_POST['usersemail']);
        if(!$bookinguser)
        {
            $statement = $db->prepare("INSERT INTO users(name, email) VALUES(?,?)");
            $statement->bind_param("ss", $_POST['usersname'], $_POST['usersemail']);
            $statement->execute();

            $bookinguser = get_user($db, null, $_POST['usersemail']);
        }
	}
    else
    {
        $bookinguser = $user;
    }

	$sql_start = date("Y-m-d H:i:s", $start);
	$sql_end = date("Y-m-d H:i:s", $end);

	$statement = $db->prepare("INSERT INTO bookings (user, reason, start, end) VALUES (?, ?, ?, ?)");
	$statement->bind_param("ssss", $bookinguser['id'], $_POST['reason'], $sql_start, $sql_end);
	$statement->execute();

	$booking = get_booking($db, $statement->insert_id);
}

// Set as current booking
$statement = $db->prepare("UPDATE users SET booking=? WHERE id=?");
$statement->bind_param("dd", $booking['id'], $booking['user']);
$statement->execute();
if($booking['user'] != $user['id'])
{
    $statement->bind_param("dd", $booking['id'], $user['id']);
    $statement->execute();
}

// Add items to booking
if($_POST['collected'])
{
    $statement = $db->prepare("INSERT INTO itembookings(booking, item, collected) VALUES (?, ?, now())");
}
else
{
    $statement = $db->prepare("INSERT INTO itembookings(booking, item) VALUES (?, ?)");
}
$statement->bind_param("ss", $booking['id'], $_POST['item']);
$statement->execute();

redirect($db, "../item.php?code=$_POST[item]");
?>