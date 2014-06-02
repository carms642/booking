<?php
include_once "../includes/database.php";
include_once "../includes/validate.php";

$db = get_database();
$user = verify_admin($db);

verify_parameter($_POST, 'code', $db);
verify_parameter($_POST, 'name', $db);
verify_parameter($_POST, 'contact', $db);

verify(get_user($db, $_POST['contact']), $db, 'contact', "Contact not found");

if($_FILES['image']['error'] == 0)
{
    $uploadpath = "../uploads/";

	$imagehash = sha1_file($_FILES['image']['tmp_name']);

	$uploadfile = $uploadpath.$imagehash;
	
	if(!move_uploaded_file($_FILES["image"]["tmp_name"], $uploadfile))
	{
		print "Error!";
		$imagehash = null;
	}
}

if($_POST['oldcode'] != $_POST['code'])
{
    verify(!get_items($db, $_POST['code']), $db, 'code', "An item with that code already exists");
}

$controlled = $_POST['controlled'];
if(!$controlled)
{
    $controlled = 0;
}
$bookable = $_POST['bookable'];
if(!$bookable)
{
    $bookable = 0;
}

if($_POST['oldcode'] )
{
	if($imagehash)
	{
		$statement = $db->prepare("UPDATE items SET
					code=?,
					name=?,
					bookable=?,
					controlled=?,
					contact=?,
					serial=?,
					image=?,
					description=?
				WHERE code=?");
		$statement->bind_param("ssdddssss", $_POST['code'], $_POST['name'], $bookable, $controlled, $_POST['contact'], $_POST['serial'], $imagehash, $_POST['description'], $_POST['oldcode']);
	}
	else
	{
		$statement = $db->prepare("UPDATE items SET
					code=?,
					name=?,
					bookable=?,
					controlled=?,
					contact=?,
					serial=?,
					description=?
				WHERE code=?");
		$statement->bind_param("ssdddsss", $_POST['code'], $_POST['name'], $_POST['bookable'], $_POST['controlled'], $_POST['contact'], $_POST['serial'], $_POST['description'], $_POST['oldcode']);
	}
	$statement->execute();

	if($_POST['oldcode'] != $_POST['code'])
	{
		// Code has changed. Update bookings, tags, events
		$statement = $db->prepare("UPDATE bookings SET item=? WHERE item=?");
		$statement->bind_param($_POST['code'], $_POST['oldcode']);
		$statement->execute();

		$statement = $db->prepare("UPDATE itemtags SET item=? WHERE item=?");
		$statement->bind_param($_POST['code'], $_POST['oldcode']);
		$statement->execute();

		$statement = $db->prepare("UPDATE events SET item=? WHERE item=?");
		$statement->bind_param($_POST['code'], $_POST['oldcode']);
		$statement->execute();
	}
}
else
{
	$statement = $db->prepare("INSERT INTO items VALUES (?,?,?,?,?,?,?,NULL,?,now())");
	$statement->bind_param("ssdddsss", $_POST['code'], $_POST['name'], $bookable, $controlled, $_POST['contact'], $_POST['serial'], $imagehash, $_POST['description']);
    print_r($statement);
	$statement->execute();
    print_r($statement);
}

$existingtags = get_item_tags($db, $_POST['code']);
$existingtagids = array();

foreach($existingtags as $tag)
{
	$existingtagids[] = $tag['id'];
}

if($_POST['tags'])
{
    $removetags = array_diff($existingtagids, $_POST['tags']);
    $statement = $db->prepare("DELETE FROM itemtags WHERE tag=? AND item=?");
    foreach($removetags as $tagid)
    {
    	$statement->bind_param("ss", $tagid, $_POST['code']);
    	$statement->execute();
    }

    $newtags = array_diff($_POST['tags'], $existingtagids);
    $statement1 = $db->prepare("SELECT * FROM tags WHERE id=?");
    $statement2 = $db->prepare("INSERT INTO tags VALUES (0,?)");
    $statement3 = $db->prepare("INSERT INTO itemtags VALUES (?,?)");
    foreach($newtags as $tag)
    {
    	$statement1->bind_param("s", $tag);
    	$statement1->execute();

    	$tagid=$tag;
    	$result = fetch($statement1);

    	if(!$result)
    	{
    		$statement2->bind_param("s", $tag);
    		$statement2->execute();

    		$tagid = $statement2->insert_id;
    	}

    	$statement3->bind_param("sd", $_POST['code'], $tagid);
    	$statement3->execute();
    }
}
else
{
    $statement = $db->prepare("DELETE FROM itemtags WHERE item=?");
    $statement->bind_param("s", $_POST['code']);
    $statement->execute();
}

redirect($db, "../item.php?code=$_POST[code]");
?>