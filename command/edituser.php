<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";
include_once "../includes/security.php";

$db = get_database();

verify_parameter($_POST, 'name', $db);
verify_parameter($_POST, 'email', $db);

$email = strtolower($_POST['email']);

$current_user = verify_login($db);

if($_POST['id'])
{
	$user = get_user($db, $_POST['id']);

	verify(can_edit_user($current_user, $user), $db, 'password', "Can't Edit User");

	if(is_admin($current_user))
	{
        if($_POST['admin'])
        {
            $_POST['admin'] = 1;
        }
        else
        {
            $_POST['admin'] = 0;
        }

    	if($_POST['password'])
		{
    		$statement = $db->prepare("UPDATE users SET name=?, email=?, password=?, admin=? WHERE id=?");
            $statement->bind_param("sssdd", $_POST['name'], $email, sha1($salt.$_POST['password']), $_POST['admin'], $_POST['id']);
            $statement->execute();
		}
		else
		{
			$statement = $db->prepare("UPDATE users SET name=?, email=?, admin=? WHERE id=?");
            $statement->bind_param("ssdd", $_POST['name'], $email, $_POST['admin'], $_POST['id']);
            $statement->execute();
		}
	}
	else
	{
		if($_POST['password'])
		{
			$statement = $db->prepare("UPDATE users SET name=?, email=?, password=? WHERE id=?");
            $statement->bind_param("sssd", $_POST['name'], $email, sha1($salt.$_POST['password']), $_POST['id']);
            $statement->execute();
		}
		else
		{
			$statement = $db->prepare("UPDATE users SET name=?, email=? WHERE id=?");
            $statement->bind_param("ssd", $_POST['name'], $email, $_POST['id']);
            $statement->execute();
		}
	}
}
else if(is_admin($user))
{
	$statement = $db->prepare("INSERT INTO users(name, email) VALUES(?,?,?)");
}

redirect($db, "../user.php?id=$_POST[id]");
?>