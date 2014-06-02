<?php
include_once "../includes/validate.php";
include_once "../includes/database.php";

$db = get_database();

$user = verify_login($db);

verify_parameter($_POST, 'comment', $db);

if($_POST['item'])
{
    add_item_event($db, $_POST['item'], $user, $_POST['comment'], false, $_POST['status'] == "true");
}
else if($_POST['booking'])
{
    add_booking_event($db, $_POST['booking'], $user, $_POST['comment']);
}

redirect($db);
?>