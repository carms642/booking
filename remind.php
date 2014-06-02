<?php
include_once "includes/database.php";
include_once "includes/security.php";
include_once "includes/validate.php";
include_once "includes/template.php";
include_once "includes/formatting.php";
include_once "includes/mail.php";

$db = get_database();

// Get overdue bookings
$bookings = get_bookings($db, null, "overdue");
$bookings = get_booking_items($db, $bookings);

foreach ($bookings as $booking)
{
	$mail = get_mail();
	$mail->From = 'noreply@cs.nott.ac.uk';
	$mail->FromName = 'MRL Booking System';
	
	$mail->Subject = 'MRL Booking System Reminder';
	$mail->Body = get_template('templates/email.php', array(
			'content' => new Template('templates/emailremind.php', array(
					'booking' => $booking))));

	$mail->AddAddress($booking['email'], $booking['name']);
	
	//if(!$mail->Send())
	//{
	//	echo "error $mail->ErrorInfo";
	//}
}

// Get administrators
$statement = $db->prepare("SELECT * FROM users WHERE users.admin=1");
$statement->execute();
$admins = fetch($statement);

$mail = get_mail();
$mail->From = 'noreply@cs.nott.ac.uk';
$mail->FromName = 'MRL Booking System';

$mail->Subject = 'MRL Booking System Update';
$mail->Body = get_template('templates/email.php', array(
	        'content' => new Template('templates/emailupdate.php', array(
	                'bookings' => $bookings))));

print $mail->Body;

foreach($admins as $admin)
{
	$mail->AddAddress($admin['email'], $admin['name']);
}

//if(!$mail->Send())
//{
//	echo "error $mail->ErrorInfo";	
//}

?>