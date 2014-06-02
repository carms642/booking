<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";

$db = get_database();

verify_admin($db);

verify_parameter($_GET, 'tag', $db, "Tag required");

$statement = $db->prepare("SELECT * FROM tags WHERE text=?");
$statement->bind_param("s", $_GET['tag']);
$statement->execute();

$result = fetch($statement);

verify($result, $db, 'tag', "Tag doesn't exist");

$tag= $result[0];

$statement = $db->prepare("DELETE FROM itemtags WHERE tag=?");
$statement->bind_param("s", $tag['id']);
$statement->execute();

$statement = $db->prepare("DELETE FROM tags WHERE id=?");
$statement->bind_param("s", $tag['id']);
$statement->execute();

redirect($db, "../index.php");
?>