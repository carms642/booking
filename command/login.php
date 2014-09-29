<?php
include_once "../includes/database.php";
include_once "../includes/security.php";
include_once "../includes/validate.php";
include_once "../includes/template.php";

$db = get_database();

verify_parameter($_POST, "email", $db, "An email is required");
verify_parameter($_POST, "password", $db, "A password is required");

$user = get_user($db, null, $_POST['email']);
if(!$user)
{
	redirect_error($db, "email", "No User found");
}

if($user['fails'] > 0)
{
	if((time() - strtotime($user['failtime'])) < (2 ^ ($user['fails'] - 1)))
	{
		redirect($db);
	}
}

if($user['password'])
{
	if($user['password'] == sha1($salt.$_POST['password']))
	{
		$session = sha1($salt.time());

		$expire = time() + 60 * 60 * 24 * 14;

		setcookie("mrlsession", $session, $expire, "/", NULL, false, true);

		$statement = $db->prepare("INSERT usersessions VALUES(?, ?, now())");
		$statement->bind_param("sd", $session, $user['id']);
		$statement->execute();

		$statement = $db->prepare("UPDATE users SET fails=0, failtime=NULL WHERE id=?");
		$statement->bind_param("d", $user['id']);
		$statement->execute();

		redirect($db);
	}
	else
	{
		if($user['fails'])
		{
			$fails = $user['fails']+ 1;
		}
		else
		{
			$fails = 1;
		}

		$statement = $db->prepare("UPDATE users SET fails=?, failtime=now() WHERE id=?");
		$statement->bind_param("dd", $fails, $user['id']);
		$statement->execute();

		redirect_error($db, "password", "Incorrect password");
	}
}
else
{
	redirect_error($db, "password", "Incorrect password");
}

redirect_error($db, "password","Login not Recognised");
?>