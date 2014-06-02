<?php
define("SECOND", 1);
define("MINUTE", 60 * SECOND);
define("HOUR", 60 * MINUTE);
define("DAY", 24 * HOUR);
define("MONTH", 30 * DAY);

function format_date($time)
{
	$timestamp = strtotime($time);
	$delta = time() - $timestamp;

	if ($delta < 2 * MINUTE)
	{
		return "just now";
	}
	if ($delta < 45 * MINUTE)
	{
		return floor($delta / MINUTE) . " minutes ago";
	}
	if ($delta < 90 * MINUTE)
	{
		return "an hour ago";
	}
	if ($delta < 24 * HOUR)
	{
		return floor($delta / HOUR) . " hours ago";
	}
	if ($delta < 48 * HOUR)
	{
		return "yesterday";
	}
	if ($delta < 30 * DAY)
	{
		$today = getdate();
		$date = getdate($timestamp);
		if($today['year'] == $date['year'])
		{
			return date("j M", $timestamp);
		}
		else
		{
			return date("j M Y", $timestamp);
		}
	}
}

function format_daterange($startString, $endString, $fullmonth = false, $year = false)
{
	if($fullmonth)
	{
		$month = "F";
	}
	else
	{
		$month = "M";
	}
	$startTime = strtotime($startString);
	$start = getdate($startTime);
	$endTime = strtotime($endString);
	$end = getdate($endTime);

	$today = getdate();

	if($start['year'] == $end['year'])
	{
		if($start['mon'] == $end['mon'])
		{
			if($start['mday'] == $end['mday'])
			{
				if($start['hours'] != 0)
				{
					if(!$year && $start['year'] == $today['year'])
					{
						return date("j $month g:i",$startTime). "&ndash;".date("g:i",$endTime);
					}
					else
					{
						return date("j $month Y g:i",$startTime)."&ndash;".date("g:i",$endTime);
					}
				}
				if(!$year && $start['year'] == $today['year'])
				{
					return date("j $month",$endTime);
				}
				else
				{
					return date("j $month Y",$endTime);
				}
			}
			if(!$year && $start['year'] == $today['year'])
			{
				return date("j",$startTime)."&ndash;".date("j $month",$endTime);
			}
			else
			{
				return date("j",$startTime)."&ndash;".date("j $month Y",$endTime);
			}
		}

		if(!$year && $start['year'] == $today['year'])
		{
			return date("j $month",$startTime)."&ndash;".date("j $month",$endTime);
		}
		else
		{
			return date("j $month",$startTime)."&ndash;".date("j $month Y",$endTime);
		}
	}
	return date("j $month Y",$startTime)."&ndash;".date("j $month Y",$endTime);
}
?>