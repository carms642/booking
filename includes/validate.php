<?php

date_default_timezone_set('Europe/London');

function can_add_item($booking, $itembookings)
{
	$start = strtotime($booking['start']);
	$end = strtotime($booking['end']);

	foreach($itembookings as $itembooking)
	{
		if($booking['id'] == $itembookings['id'])
		{
			return false;
		}

		$bookstart = strtotime($itembooking['start']);
		$bookend = strtotime($itembooking['end']);

		if(overlap($start, $end, $bookstart, $bookend))
		{
			return false;
		}
	}
	return true;
}

function can_remove($user, $booking, $item)
{
    if(!can_edit_booking($user, $booking))
	{
		return false;
	}

	return true;
}

function can_collect($user, $booking, $item)
{
    // Current = current booking. This item has already been collected, or hasn't been returned.
	if($item['current'])
	{
		return false;
	}

    if($item['controlled'] && !is_admin($user) && $user['id'] != $item['contact'])
    {
        return false;
    }

    if($booking)
    {
        if(!can_edit_booking($user, $booking))
    	{
    		return false;
    	}

        $today = time();
    	$startTime = strtotime($booking['start']);
    	$endTime = strtotime($booking['end']) + (24 * 60 * 60);

    	return $startTime <= $today && $today < $endTime;
    }
    return true;
}

function can_return($user, $booking, $item)
{
    if(!can_edit_booking($user, $booking))
	{
		return false;
	}

    if($item['controlled'])
    {
        if(is_admin($user))
        {
            return true;
        }
        else
        {
            return $user['id'] == $item['contact'];
        }
    }

    return false;
}

function can_edit_booking($user, $booking)
{
	if(!$booking)
	{
		return false;
	}

	if(is_admin($user))
	{
		return true;
	}

	if($user['id'] == $booking['user'])
	{
		return true;
	}

	return false;
}


function can_edit_item($user, $item)
{
	if(is_admin($user))
	{
		return true;
	}

	if($user['username'] == $item['contact'])
	{
		return true;
	}

	return false;
}

function can_edit_user($current_user, $user)
{
	if(!$user)
	{
		return false;
	}

	if(is_admin($current_user))
	{
		return true;
	}

	if($current_user['id'] == $user['id'])
	{
		return true;
	}

	return false;
}

function is_admin($user)
{
	return $user['admin'] == 1;
}

// function is_in_booking($booking, $time = NULL)
// {
// 	if($time)
// 	{
// 		$date = date($time);
// 	}
// 	else
// 	{
// 		$date = date();
// 	}
// }

function is_booking_overdue($booking)
{
	if($booking['returned'])
	{
		return false;
	}

	$time = time();
	$end = strtotime($booking['end']) + (24 * 60 * 60);

	return $time > $end;
}

function is_booking_expired($booking)
{
	if($booking['returned'])
	{
		return true;
	}

	$time = time();
	$end = strtotime($booking['end']) + (24 * 60 * 60);

	if($booking['items'])
	{
		foreach($booking['items'] as $item)
		{
			if($item['collected'])
			{
				return false;
			}
		}
	}

	return $time > $end;
}

function overlap($start1, $end1, $start2, $end2)
{
	return !(($start2 < $start1 && $end2 < $start1) || ($start1 < $start2 && $end1 < $start2));
}

function redirect($db, $url = NULL)
{
	if(!$url)
	{
		$url = $_SERVER['HTTP_REFERER'];
	}

    session_start();
	$statement = $db->prepare("DELETE FROM errors WHERE session=?");
	$statement->bind_param("s", session_id());
	$statement->execute();

	header( "Location: $url" ) ;

	exit;
}

function redirect_error($db, $param, $error, $url = NULL)
{
	if(!$url)
	{
		$url = $_SERVER['HTTP_REFERER'];
	}

	session_start();
	$statement = $db->prepare("REPLACE INTO errors SET session=?, param=?, error=?");
	$statement->bind_param("sss", session_id(), $param, $error);
	$statement->execute();

	header( "Location: $url" ) ;

	exit;
}

function verify($result, $db, $param, $error)
{
	if(!$result)
	{
		redirect_error($db, $param, $error);
	}
}

function verify_admin($db)
{
	$user = verify_login($db);
	if(!is_admin($user))
	{
		redirect_error($db, "x", "Admin rights required");
	}

	return $user;
}

function verify_booking_times($db, $start, $end)
{
	if(!$start)
	{
		redirect($db, "start", "No start date");
	}
	if(!$end)
	{
		redirect($db, "end", "No end date");
	}
	else if($start > $end)
	{
		// Booking ends before it begins
		redirect_error($db, "end", "Booking ends before it starts");
	}
}

function verify_bookings($bookings, $testBookings)
{
	$results = array();
	foreach($bookings as $booking)
	{
		$startTime = strtotime($booking['start']);
		$endTime = strtotime($booking['end']);

		$booking['valid'] = true;
		foreach($testBookings as $testBooking)
		{
			if($testBooking['returned'])
			{
				continue;
			}
			if(overlap($startTime, $endTime, strtotime($testBooking['start']), strtotime($testBooking['end'])))
			{
				$booking['valid'] = false;
				break;
			}
		}

		$results[] = $booking;
	}

	return $results;
}

function verify_login($db)
{
	$user = get_user($db);

	if(!$user)
	{
		redirect_error($db, "password", "Please login", "index.php");
	}

	return $user;
}

function verify_parameter($array, $param, $db, $error = NULL, $url = NULL)
{
	if(!$error)
	{
		$error = "$param Required";
	}

	if(!isset($array[$param]))
	{
		redirect_error($db, $param, $error, $url);
		exit;
	}
}

function verify_time($db, $param, $timestr)
{
	if(!$timestr)
	{
		// No time supplied
		redirect($db, $param, "No time supplied");
	}
	$time = strtotime($timestr);
	$today = strtotime(date("j M Y"));
	if($time <= 0)
	{
		// Time not parsed
		redirect($db, $param, "Didn't understand date given");
	}
	if($time < $today)
	{
		// Time in past
		redirect($db, $param, "Date is in the past");
	}

	return $time;
}
?>