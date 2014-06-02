<?php
include_once "includes/formatting.php";
include_once "includes/validate.php";
?>

<?php if($this->title): ?>
<div class="tagtitle" style="display: block">
    <?php print $this->title ?>
</div>
<?php if($this->tag && is_admin($this->user)):?>
    <a style="margin: 0 5px; font-size: smaller;" href="command/deletetag.php?tag=<?php print $this->tag ?>" class="adminbutton">Delete <?php print $this->tag ?> Tag</a>
<?php endif; ?>
<?php endif;?>

<div class="itemlist" style="border-bottom: 5px solid #AAA; margin: 0 5px;">
<?php foreach($this->items as $item): ?>
<div class="item<?php print $item['returned'] ? " returned" : "" ?>">
	<?php if($item['image']):?>
	<a style="display: inline-block" href="item.php?code=<?php print $item['code'] ?>"><img class="thumbimage" src="uploads/<?php print $item['image'] ?>" /></a>
	<?php endif;?>
	<div style="display: inline-block; <?php if(!$item['image']) { print "margin-left: 74px;"; } ?>">
		<div>
			<a class="itemname" href="item.php?code=<?php print $item['code'] ?>"><?php print $item['name'] ?></a>
			<div class="code"><?php print $item['code'] ?></div>
			<div>
			<?php foreach($item['tags'] as $tag):?>
			<?php if(strcasecmp($tag['text'],$this->tag) != 0): ?>
			<div class="tag"><a href="items.php?tag=<?php print $tag['text'] ?>"><?php print $tag['text'] ?> </a></div>
			<?php endif; ?>
			<?php endforeach; ?>
			</div>

			<?php if($item['status']): ?>
			<div><?php print $item['status'] ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endforeach; ?>
</div>