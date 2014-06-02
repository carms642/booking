<?php
include_once "../includes/database.php";
include_once "../includes/security.php";
include_once "../includes/validate.php";
include_once "../includes/template.php";

$db = get_database();
verify_parameter($_POST, "email", $db, "An email is required");

$statement = $db->prepare("SELECT * FROM users WHERE email=?");
$statement->bind_param("s", strtolower($_POST['email']));
$statement->execute();
$result = fetch($statement);
if(count($result) == 0)
{
    redirect_error($db, "email", "No account found with that email");
}

$user = $result[0];

$session = sha1($salt.time());

$statement = $db->prepare("INSERT usersessions VALUES(?, ?, now())");
$statement->bind_param("sd", $session, $user['id']);
$statement->execute();

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";

 // Additional headers
 $headers .= "From: MRL Booking System <noreply@cs.nott.ac.uk>\r\n";

$url = "http://www.cs.nott.ac.uk/mrlbooking/command/resetpassword.php?key=$session";

$content = get_template('../templates/email.php', array(
     'content' => new Template('../templates/emailpassreset.php', array(
                    'url' => $url))));

mail($user['email'], "MRL Booking System Password Reset", $content, $headers);

redirect_error($db, "password", "An email has been sent to you. Follow the instructions in the email to reset your password", "../index.php");
?>