<?php
include_once "includes/formatting.php";
include_once "includes/validate.php";
include_once "includes/database.php";
include_once "includes/template.php";

$db = get_database();

$user = verify_login($db);

if($_GET['search'])
{
	$title = "Items Matching $_GET[search]";

	preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $_GET['search'], $matches);

	$statementNames = $db->prepare("SELECT code FROM items WHERE name LIKE ?");
	$statementCodes = $db->prepare("SELECT code FROM items WHERE code LIKE ?");
	$itemcodes = array();
	foreach($matches[0] as $match)
	{
		$search = "%".$match."%";
		$statementNames->bind_param("s", $search);
		$statementNames->execute();
			
		$searchItems = fetch($statementNames);
		foreach($searchItems as $searchItem)
		{
			$itemcodes[] = $searchItem['code'];
		}
			
		$statementCodes->bind_param("s", $search);
		$statementCodes->execute();

		$searchItems = fetch($statementCodes);
		foreach($searchItems as $searchItem)
		{
			$itemcodes[] = $searchItem['code'];
		}
	}

	$statement = $db->prepare("SELECT itemtags.item FROM tags, itemtags WHERE tags.text LIKE ? AND tags.id=itemtags.tag");
	$results = array();
	foreach($matches[0] as $match)
	{
		$search = "%".$match."%";
		$statement->bind_param("s", $search);
		$statement->execute();

		$searchItems = fetch($statement);
		foreach($searchItems as $searchItem)
		{
			$itemcodes[] = $searchItem['item'];
		}
	}

	$itemscount = array_count_values($itemcodes);

	asort($itemscount);
	
	$itemscount = array_reverse($itemscount);

	$results = get_items($db, array_keys($itemscount));
}
else if($_GET['tag'])
{	
	$statement = $db->prepare("SELECT * FROM tags WHERE text=?");
	$statement->bind_param("s", $_GET['tag']);
	$statement->execute();
	
	$tagcheck = fetch($statement);
	verify($tagcheck, $db, "tags", "Tag not found");
	
	$title = $tagcheck[0]['text'];
	
	$statement = $db->prepare("SELECT items.*, tags.text FROM items, itemtags, tags WHERE tags.text=? AND itemtags.item=items.code AND tags.id=itemtags.tag ORDER BY items.code");
	$statement->bind_param("s", $_GET['tag']);
	$statement->execute();

	$results = fetch($statement);
}
else
{
	$title = "All Items";

	$statement = $db->prepare("SELECT items.* FROM items ORDER BY items.code");
	$statement->execute();

	$results = fetch($statement);
}

$items = get_item_tags($db, $results);
	
$items = array();
$statement = $db->prepare("SELECT tags.* FROM itemtags, tags WHERE itemtags.item=? AND itemtags.tag=tags.id ORDER BY tags.id");
foreach($results as $item)
{
	$statement->bind_param("s", $item['code']);
	$statement->execute();

	$item['tags'] = fetch($statement);

	$items[] = $item;
}
	
verify($results, $db, "", "");
	
print_template('templates/page.php', array(
		'user' => $user,
		'search' => $_GET['search'],
		'content' => new Template('templates/itemlist.php', array('title'=>$title,
				'items' => $items,
				'user' => $user,
				'tag' => $_GET['tag']))));
?>