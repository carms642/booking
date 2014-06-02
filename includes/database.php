<?php
include_once "db_config.php";

/**
 * @param $db mysqli
 * @return null
 */
function get_error($db)
{
	session_start();
	$statement = $db->prepare("SELECT * FROM errors WHERE session=?");
	$statement->bind_param("s", session_id());
	$statement->execute();

	$result = fetch($statement);
	if(count($result) == 1)
	{
		return $result[0];
	}

	return null;
}

function fetch($result, $array = array())
{
	if($result instanceof mysqli_stmt)
	{
		if(function_exists("msqli_stmt_get_result"))
		{
			return fetch($result->get_result());
		}
		$result->store_result();

		$variables = array();
		$data = array();
		$meta = $result->result_metadata();

		while($field = $meta->fetch_field())
		{
			$variables[] = &$data[$field->name]; // pass by reference
		}

		call_user_func_array(array($result, 'bind_result'), $variables);

		$i=count($array);
		while($result->fetch())
		{
			$array[$i] = array();
			foreach($data as $k=>$v)
			{
				$array[$i][$k] = $v;
			}
			$i++;

			// don't know why, but when I tried $array[] = $data, I got the same one result in all rows
		}
	}
	elseif($result instanceof mysqli_result)
	{
		while($row = $result->fetch_assoc())
		{
			$array[] = $row;
		}
	}

	return $array;
}

/**
 * @param $db mysqli
 * @param $user
 * @param string $filter
 * @return array
 */
function get_bookings($db, $user, $filter = "active")
{
    if($user == null || is_admin($user))
	{
        if($filter == "overdue")
        {
            $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE returned is NULL AND end < ? AND bookings.user=users.id ORDER BY end");
            $statement->bind_param("s", date("Y-m-d 00:00:00" , time()));
        }
        else if($filter == "active")
        {
            $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE returned is NULL AND bookings.user=users.id ORDER BY end");
        }        
        else if($filter == "all")
        {
            $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE bookings.user=users.id ORDER BY end");
        }
		else
		{
			return null;
		}

        $statement->execute();
        return fetch($statement);
	}
	else
	{
		return get_user_bookings($db, $user, $filter);
	}

}

/**
 * @param $db mysqli
 * @param $user
 * @param string $filter
 * @return array
 */
function get_user_bookings($db, $user, $filter = "active")
{
    if($filter == "overdue")
    {
        $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE bookings.user=? AND returned is NULL AND end < ? AND bookings.user=users.id ORDER BY end");
        $statement->bind_param("ss", $user['id'], date("Y-m-d 00:00:00" , time()));
    }
    else if($filter == "active")
    {
	    $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE bookings.user=? AND returned is NULL AND bookings.user=users.id ORDER BY end");
        $statement->bind_param("s", $user['id']);
    }
    else if($filter == "all")
    {
        $statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE bookings.user=? AND bookings.user=users.id ORDER BY end");
        $statement->bind_param("s", $user['id']);
    }
	else
	{
		return null;
	}

    $statement->execute();
	return fetch($statement);
}

/**
 * @param $db mysqli
 * @param $bookings
 * @return array
 */
function get_booking_items($db, $bookings)
{
	$statement = $db->prepare("SELECT items.*, users.name as username, itembookings.booking, itembookings.collected, itembookings.returned FROM itembookings, items, users WHERE itembookings.booking=? AND items.code=itembookings.item AND items.contact=users.id ORDER BY returned ASC");
	if(is_array($bookings))
	{
		$bookingResults = array();
		foreach ($bookings as $booking)
		{
			$statement->bind_param("s", $booking['id']);
			$statement->execute();

			$booking['items'] = get_item_tags($db, fetch($statement));

            $end = strtotime($booking['end']) + (24 * 60 * 60);
            if($end < time())
            {
                $collected = false;
                foreach($booking['items'] as $item)
                {
                    if($item['collected'])
                    {
                        $collected = true;
                        break;
                    }
                }

                if(!$collected)
                {
                    $return = $db->prepare("UPDATE bookings SET returned=now() WHERE id=?");
                    $return->bind_param("s", $booking['id']);
                    $return->execute();
                }
            }

			$bookingResults[] = $booking;
		}

		return $bookingResults;

	}
	else if(is_string($bookings))
	{
		$statement->bind_param("s", $bookings);
		$statement->execute();

		return get_item_tags($db, fetch($statement));
	}

	return null;
}

/**
 * @param $db mysqli
 * @param $id string
 * @return null
 */
function get_booking($db, $id)
{
	$statement = $db->prepare("SELECT bookings.*, users.name, users.email FROM bookings, users WHERE bookings.id=? AND bookings.user=users.id");
	$statement->bind_param("s", $id);
	$statement->execute();

	$result = fetch($statement);
	if(count($result) == 0)
	{
		return null;
	}

	return $result[0];
}

/**
 * @param $db mysqli
 * @param $itemcodes
 * @return array|null
 */
function get_items($db, $itemcodes)
{
	$statement = $db->prepare("SELECT items.*, users.name AS username, users.email FROM items, users WHERE items.code=? AND items.contact = users.id");
	if(is_array($itemcodes))
	{
		$results = array();
		foreach($itemcodes as $itemcode)
		{
			$statement->bind_param("s", $itemcode);
			$statement->execute();

			$results = fetch($statement, $results);
		}

		return $results;
	}
	else if(is_string($itemcodes))
	{
		$statement->bind_param("s", $itemcodes);
		$statement->execute();

		$result = fetch($statement);
		if(count($result) == 0)
		{
			return null;
		}

		return $result[0];
	}

	return null;
}

/**
 * @param $db mysqli
 * @param string $id
 * @param string $email
 * @return null
 */
function get_user($db, $id = NULL, $email = NULL)
{
    if($email)
    {
        $statement = $db->prepare("SELECT * FROM users WHERE email=?");
        $statement->bind_param("s", strtolower($email));
        $statement->execute();
    }
	else if(!$id)
	{
		if($_COOKIE['mrlsession'])
		{
			$statement = $db->prepare("SELECT * FROM users, usersessions WHERE usersessions.session=? AND users.id=usersessions.user");
			$statement->bind_param("s", $_COOKIE['mrlsession']);
			$statement->execute();
		}
		else
		{
			return null;
		}
	}
	else
	{
		$statement = $db->prepare("SELECT * FROM users WHERE id=?");
		$statement->bind_param("s", $id);
		$statement->execute();
	}

	$result = fetch($statement);
	if(count($result) == 0)
	{
		return null;
	}
	return $result[0];
}

/**
 * @param $db mysqli
 * @return array
 */
function get_users($db)
{
	$statement = $db->prepare("SELECT * FROM users");
	$statement->execute();

	return fetch($statement);
}

/**
 * @param $db mysqli
 * @return array
 */
function get_tags($db)
{
	$statement = $db->prepare("SELECT * FROM tags ORDER BY tags.id");
	$statement->execute();

	return fetch($statement);
}

/**
 * @param $db mysqli
 * @param $items
 * @return array
 */
function get_item_tags($db, $items)
{
	// Get item tags
	$statement1 = $db->prepare("SELECT tags.* FROM itemtags, tags WHERE itemtags.item=? AND itemtags.tag=tags.id ORDER BY tags.id");
	$statement2 = $db->prepare("SELECT booking FROM itembookings WHERE item=? AND collected IS NOT NULL AND returned IS NULL");
	if(is_array($items))
	{
		$resultItems = array();
		foreach($items as $item)
		{
			$statement1->bind_param("s", $item['code']);
			$statement1->execute();

			$item['tags'] = fetch($statement1);

			$statement2->bind_param("s", $item['code']);
			$statement2->execute();
			$current = fetch($statement2);
			if(count($current) >= 1)
			{
				$item['current'] = $current[0]['booking'];
			}

			$resultItems[] = $item;
		}

		return $resultItems;
	}
	else if(is_string($items))
	{
		$statement1->bind_param("s", $items);
		$statement1->execute();

		return fetch($statement1);
	}

	return null;
}

/**
 * @param $db mysqli
 * @param $itemcode
 * @return null
 */
function get_item_booking($db, $itemcode)
{
    $statement = $db->prepare("SELECT booking FROM itembookings WHERE item=? AND collected IS NOT NULL AND returned IS NULL");
	$statement->bind_param("s", $_GET['code']);
	$statement->execute();

    $result = fetch($statement);
    if(count($result) >= 1)
    {
        return $result[0]['booking'];
    }
    return null;
}

/**
 * @param mysqli $db
 * @param string $itemcode
 * @param bool $returned
 * @return array
 */
function get_item_bookings($db, $itemcode, $returned = false)
{
	// Get item bookings
    if($returned)
    {
        $statement = $db->prepare("SELECT bookings.*, users.name FROM bookings, itembookings, users WHERE itembookings.item=? AND itembookings.booking = bookings.id AND bookings.user = users.id ORDER BY end");
    }
    else
    {
        $statement = $db->prepare("SELECT bookings.*, users.name FROM bookings, itembookings, users WHERE itembookings.item=? AND itembookings.returned IS NULL AND bookings.returned IS NULL AND itembookings.booking = bookings.id AND bookings.user = users.id ORDER BY end ASC");
    }
	$statement->bind_param("s", $itemcode);
	$statement->execute();

	return fetch($statement);
}

/**
 * @param $db mysqli
 * @param $bookingid
 * @param $user
 * @param $text
 */
function add_booking_event($db, $bookingid, $user, $text)
{
    $statement = $db->prepare("INSERT INTO bookingevents VALUES (0,?,?,?,now())");
    $statement->bind_param("dds", $bookingid, $user['id'], $text);
    $statement->execute();
}

/**
 * @param $db mysqli
 * @param $itemcode
 * @param $user
 * @param $text
 * @param bool $archived
 * @param bool $status
 */
function add_item_event($db, $itemcode, $user, $text, $archived = false, $status = false)
{
    $archive = 0;
    if($archived)
    {
        $archive = 1;
    }
    $statement = $db->prepare("INSERT INTO itemevents VALUES (0,?,?,?,now(),?)");
    $statement->bind_param("sdsd", $itemcode, $user['id'], $text, $archive);
    $statement->execute();

    if($status)
    {
        $item = get_items($db, $itemcode);

    	if(can_edit_item($user, $item))
    	{
    		$statement = $db->prepare("UPDATE items SET status=? WHERE code=?");
    		$statement->bind_param("ss", $text, $itemcode);
    		$statement->execute();
    	}
    }
}

/**
 * @param $db mysqli
 * @param $bookingid
 * @return array
 */
function get_booking_events($db, $bookingid)
{
    $statement = $db->prepare("SELECT bookingevents.*, users.name FROM bookingevents, users WHERE bookingevents.booking=? AND bookingevents.user=users.id ORDER BY time ASC");
	$statement->bind_param("d", $bookingid);
	$statement->execute();

	$events = fetch($statement);

	return $events;
}

/**
 * @param $db mysqli
 * @param $itemcode
 * @param $itembookings
 * @param bool $archived
 * @return array
 */
function get_item_events($db, $itemcode, $itembookings, $archived = false)
{
	// Get events
    if($archived)
    {
        $statement = $db->prepare("SELECT itemevents.*, users.name FROM itemevents, users WHERE itemevents.item=? AND itemevents.user=users.id ORDER BY time ASC");
    }
    else
    {
	    $statement = $db->prepare("SELECT itemevents.*, users.name FROM itemevents, users WHERE itemevents.item=? AND itemevents.archived=0 AND itemevents.user=users.id ORDER BY time ASC");
    }
	$statement->bind_param("s", $itemcode);
	$statement->execute();

	$events = fetch($statement);

	if(!$itembookings)
	{
		$itembookings = get_item_bookings($db,$itemcode);
	}
	foreach ($itembookings as $booking)
	{
		$i = 0;
		foreach($events as $event)
		{
			$bookingend = strtotime($booking['start']);
			if($event['time'])
			{
				$time = strtotime($event['time']);
			}
			else
			{
				$time = strtotime($event['start']);
			}

			if($bookingend < $time)
			{
				break;
			}
			$i++;
		}

		// Insert booking into events
		array_splice($events, $i, 0, array($booking));
	}

	return $events;
}
?>