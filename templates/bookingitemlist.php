<?php 
include_once "includes/formatting.php";
include_once "includes/validate.php";
?>


<div class="itemlist">
<?php if($this->title): ?>
<div class="item" style="display: inline-block"><div class="titlepanel title"><?php print $this->title ?></div>
<?php if(is_admin($this->user)):?>
<a href="command/deletetag.php?tag=<?php print $this->tag ?>" class="adminbutton">Delete <?php print $this->tag ?> Tag</a>
<?php endif;?>
</div>
<?php endif; ?>
<?php foreach($this->items as $item): ?>
<div class="item<?php print $item['returned'] ? " returned" : "" ?>">
	<?php if($item['image']):?>
	<a href="item.php?code=<?php print $item['code'] ?>"><img class="thumbimage" src="uploads/<?php print $item['image'] ?>" /></a>
	<?php endif;?>	
	<div class="inline" <?php if(!$item['image']) { print "style=\"margin-left: 74px;\""; } ?>>
		<div>
			<a class="itemname" href="item.php?code=<?php print $item['code'] ?>"><?php print $item['name'] ?></a>
			<span class="code"><?php print $item['code'] ?></span>

			<?php if($item['status']): ?>
			<div><?php print $item['status'] ?></div>
			<?php endif; ?>
		</div>
		<div class="iteminfo">
			<?php if($item['returned']): ?>
			<span> Returned <?php print format_date($item['returned']) ?>
			</span>
			<?php elseif($item['collected']): ?>
			<div class="inline">
				<div>
					Collected
					<?php print format_date($item['collected']) ?>
				</div>
				<?php if(can_edit_booking($this->user, $this->booking)): ?>
				<div>
					Return to <a href="user.php?id=<?php print $item['contact'] ?>"><?php print $item['username'] ?> </a>
				</div>
				<?php endif; ?>
			</div>
			<?php if(can_edit_booking($this->user, $this->booking)): ?>
			<form class="inline" action="command/itembooking.php" method="post">
				<input type="hidden" name="booking" value="<?php print $item['booking'] ?>" />
				<input type="hidden" name="item" value="<?php print $item['code'] ?>" />
				<input type="hidden" name="action" value="return" />
				<button type="submit" class="button">Returned</button>
			</form>
			<?php endif;?>
			<?php else: ?>
			<?php if(can_collect($this->user, $this->booking, $item)): ?>
			<div class="inline">
				<div>Collect from</div>
				<div>
					<a href="user.php?id=<?php print $item['contact'] ?>"><?php print $item['username'] ?> </a>
				</div>
			</div>
			<form class="inline" action="command/itembooking.php" method="post">
				<input type="hidden" name="booking" value="<?php print $item['booking'] ?>" />
				<input type="hidden" name="item" value="<?php print $item['code'] ?>" />
				<input type="hidden" name="action" value="collect" />
				<button type="submit" class="button">Collected</button>
			</form>
			<?php endif; ?>
			<?php if(can_edit_booking($this->user, $this->booking)):?>
			<form class="inline" action="command/itembooking.php" method="post">
				<input type="hidden" name="booking" value="<?php print $item['booking'] ?>" />
				<input type="hidden" name="item" value="<?php print $item['code'] ?>" />
				<input type="hidden" name="action" value="remove" />
				<button type="submit" class="button">Remove</button>
			</form>
			<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endforeach; ?>
</div>