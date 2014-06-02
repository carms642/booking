<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";

$db = get_database();

verify_admin($db);
verify_parameter($_GET, 'code', $db, "Code required");
verify(get_items($db, $_GET['code']), $db, "code", "Item not found");

$statement = $db->prepare("DELETE FROM itemtags WHERE item=?");
$statement->bind_param("s", $_GET['code']);
$statement->execute();

$statement = $db->prepare("DELETE FROM itembookings WHERE item=?");
$statement->bind_param("s", $_GET['code']);
$statement->execute();

$statement = $db->prepare("DELETE FROM events WHERE item=?");
$statement->bind_param("s", $_GET['code']);
$statement->execute();

$statement = $db->prepare("DELETE FROM items WHERE code=?");
$statement->bind_param("s", $_GET['code']);
$statement->execute();

redirect($db, "../index.php");
?>