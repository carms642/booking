<?php
include_once "includes/validate.php";
include_once "includes/database.php";
include_once "includes/template.php";

$db = get_database();

$user = verify_login($db);

if($_GET['action'] == "new")
{
	$error = get_error($db);

    print_template('templates/page.php', array(
			'title' => "Create New Item",
			'user' => $user,
			'content' => new Template('templates/edititem.php', array(
					'error' => $error,
					'user' => $user,
					'users' => get_users($db),
					'tags' => get_tags($db)))));
}
else
{
	verify_parameter($_GET, "code", $db, "Unknown Item");

	$item = get_items($db, $_GET['code']);
	if($item == null)
	{
		redirect_error($db, "code", "No item found");
	}

	$item['tags'] = get_item_tags($db, $_GET['code']);
    $item['current'] = get_item_booking($db, $_GET['code']);

	$itembookings = get_item_bookings($db, $_GET['code'], $_GET['show'] == "all");
	$itembookings = get_booking_items($db, $itembookings);

	$item['events'] = get_item_events($db, $_GET['code'], $itembookings, $_GET['show'] == "all");

	$bookings = get_bookings($db, $user);
	$bookings = get_booking_items($db, $bookings);
	$bookings = verify_bookings($bookings, $itembookings);

	foreach ($bookings as $booking)
	{
		// Select most reasonable booking
		if($booking['id'] == $user['booking'])
		{
			$selected = $booking;
			break;
		}
		else if($booking['valid'] && !$selected)
		{
			$selected = $booking;
		}
	}

	if($_GET['action'] == "edit" && can_edit_item($user, $item))
	{
		$error = get_error($db);

		print_template('templates/page.php', array(
				'title' => "$item[name] ($_GET[code])",
				'user' => $user,
				'content' => new Template('templates/edititem.php', array(
						'item' => $item,
						'error' => $error,
						'bookings' => $bookings,
						'booking' => $selected,
						'users' => get_users($db),
						'tags' => get_tags($db)))));
	}
	else if($_GET['action'] == "book")
	{
		$error = get_error($db);

		print_template('templates/page.php', array(
				'title' => "$item[name] ($_GET[code])",
				'user' => $user,
				'content' => new Template('templates/bookitem.php', array(
						'item' => $item,
						'error' => $error,
						'bookings' => $bookings,
						'booking' => $selected,
						'user' => $user,
						'users' => get_users($db)))));
	}
	else
	{
        if($_GET['show'] != "all")
        {
            $morelink = array('link' => "item.php?code=$item[code]&show=all", 'text' => 'Show all comments and bookings');
        }

		print_template('templates/page.php', array(
				'title' => "$item[name] ($_GET[code])",
				'user' => $user,
				'content' => new Template('templates/item.php', array(
						'item' => $item,
						'user' => $user,
                        'morelink' => $morelink,
						'bookings' => $bookings,
						'booking' => $selected))));
	}
}
?>