<?php
include_once "../includes/database.php";
include_once "../includes/security.php";
include_once "../includes/validate.php";
include_once "../includes/template.php";
include_once "../includes/mail.php";

$db = get_database();

if($_GET['key'])
{
	$statement = $db->prepare("SELECT * FROM users, usersessions WHERE usersessions.session=? AND users.id=usersessions.user");
	$statement->bind_param("s", $_GET['key']);
	$statement->execute();

	$result = fetch($statement);

	if(count($result) > 0)
	{
		$user = $result[0];

		$statement = $db->prepare("DELETE FROM usersessions WHERE usersessions.session=?");
		$statement->bind_param("s", $_GET['key']);
		$statement->execute();
	}
}
else if($_GET['id'])
{
	verify_admin($db);

	$user = get_user($db, $_GET['id']);
}

if(!$user)
{
	redirect_error($db, "password", "No user found", "../index.php");
}

$password = generate_password();

$statement = $db->prepare("UPDATE users SET password=? WHERE id=?");
$statement->bind_param("sd",  sha1($salt.$password), $user['id']);
$statement->execute();

$mail = get_mail();
$mail->From = 'noreply@cs.nott.ac.uk';
$mail->FromName = 'MRL Booking System';

$mail->Subject = 'MRL Booking System Password Reset';
$mail->Body = get_template('../templates/email.php', array(
        'content' => new Template('../templates/emailpassword.php', array(
                'name' => $user['name'],
                'email' => $user['email'],
				'password' => $password))));

$mail->AddAddress($user['email'], $user['name']);

if(!$mail->Send())
{
	redirect_error($db, "password", "Mail could not be sent. $mail->ErrorInfo", "../index.php");	
}

redirect_error($db, "password", "Password reset. A new password has been emailed to you.", "../index.php");

?>