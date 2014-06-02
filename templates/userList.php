<?php 
include_once "includes/formatting.php";
include_once "includes/validate.php";
?>
<div class="titlepanel tagitem" style="display: block">Users</div>
<div class="itemlist" style="border-bottom: 5px solid #AAA;">
<?php foreach($this->users as $user): ?>
	<a class="tagitem" href="user.php?id=<?php print $user['id'] ?>">
		<?php print $user['name']?>
	</a>
<?php endforeach; ?>
</div>