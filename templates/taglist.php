<?php
include_once "includes/formatting.php";
include_once "includes/validate.php";
?>
<div class="itemlist">
<?php if($this->links): ?>
<?php foreach($this->links as $link): ?>
	<a class="tagtitle" href="<?php print $link['link'] ?>">
		<?php if($link['image']): ?>
		<img class="thumbimage" src="uploads/<?php print $tag['image'] ?>" />
		<?php endif; ?>
		<?php print $link['text']?>
	</a>
<?php endforeach; ?>
<?php endif; ?>
</div>
<div class="tagtitle" style="display: block">Items</div>
<div class="itemlist" style="border-bottom: 5px solid #AAA; margin: 0 5px;">
<?php foreach($this->tags as $tag): ?>
	<a class="tagitem" href="items.php?tag=<?php print $tag['text'] ?>">
		<?php if($tag['image']): ?><img class="tagicon" src="uploads/<?php print $tag['image'] ?>" /><?php endif; ?><?php print $tag['text']?>
	</a>
<?php endforeach; ?>
</div>