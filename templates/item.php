<?php include_once "includes/formatting.php"; ?>

<div class="details">
	<div class="titleblock">
		<div class="titlepanel">
			<div class="title">
				<?php print $this->item['name'] ?>
			</div>
			<div>
				<?php print $this->item['code'] ?>
			</div>
			<?php foreach($this->item['tags'] as $tag): ?>
			<div class="tag">
				<a href="items.php?tag=<?php print $tag['text'] ?>"><?php print $tag['text'] ?></a>
			</div>
			<?php endforeach;?>
			<?php if($this->item['status']): ?>
			<?php if($this->item['bookable']): ?>
			<div><?php print $this->item['status'] ?></div>
			<?php else: ?>
			<div class="error"><?php print $this->item['status'] ?></div>
			<?php endif; ?>
			<?php endif; ?>
			<div style="clear:both">Contact: <a href="user.php?id=<?php print $this->item['contact'] ?>"><?php print $this->item['username'] ?> </a></div>
			<?php if($this->booking): ?>
			<form class="titlebooking spacer" action="command/addbooking.php" method="post">

				<?php if($this->booking['valid']): ?>
				<button class="book">Book</button>
				<?php else: ?>
				<button class="book" disabled>Book</button>
				<?php endif; ?>
				<div class="bookdetails">
					<a href="booking.php?id=<?php print $this->booking['id'] ?>"><?php print $this->booking['reason'] ?></a>
					<span class="time"><?php print format_daterange($this->booking['start'], $this->booking['end'])?> </span>
					<div>
						<?php print $this->booking['name'] ?>
					</div>
				</div>

				<input name="booking" type="hidden" value="<?php print $this->booking['id'] ?>" />
				<input name="item" type="hidden" value="<?php print $this->item['code'] ?>" />

			</form>
			<?php endif; ?>
			<form class="titlebooking spacer" action="item.php">
				<input name="code" type="hidden" value="<?php print $this->item['code'] ?>" />
				<input name="action" type="hidden" value="book" />
				<input type="submit" class="book" value="Book" />
				<span>New Booking</span>
			</form>
			<div id="morebookings">
			<?php foreach($this->bookings as $booking): ?>
			<?php if($booking['id'] != $this->booking['id']):?>
			<form class="titlebooking spacer" action="command/addbooking.php" method="post">
				<?php $more = true; ?>
				<?php if($booking['valid']): ?>
				<button class="book">Book</button>
				<?php else: ?>
				<button class="book" disabled>Book</button>
				<?php endif; ?>
				<div class="bookdetails">
					<a href="booking.php?id=<?php print $booking['id'] ?>"><?php print $booking['reason'] ?></a>
					<span class="time"><?php print format_daterange($booking['start'], $booking['end'])?> </span>
					<div>
						<?php print $booking['name'] ?>
					</div>
				</div>

				<input name="booking" type="hidden" value="<?php print $booking['id'] ?>" />
				<input name="item" type="hidden" value="<?php print $this->item['code'] ?>" />

			</form>
			<?php endif; ?>
			<?php endforeach; ?>
			</div>
			<?php if($more):?>
			<script>
				function showMoreBookings()
				{
					var more = 	document.getElementById('morebookings');
					more.style.height= more.scrollHeight + "px";
					document.getElementById('morebookingslink').style.display='none';
				}
			</script>
			<?php if($this->booking): ?>
			<a id="morebookingslink" class="adminbutton" href="javascript:showMoreBookings();">Show more bookings</a>
			<?php else: ?>
			<a id="morebookingslink" class="adminbutton" href="javascript:showMoreBookings();">Show your bookings</a>
			<?php endif; ?>
			<?php endif; ?>
            <?php if(can_edit_item($this->user, $this->item)): ?>
		        <a class="adminbutton" href="item.php?code=<?php print $this->item['code'] ?>&action=edit">Edit</a>
		    <?php endif; ?>
            <?php if($this->error): ?>
			<div class="error">
				<?php print $this->error ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<div>
		<?php if ($this->item['image']): ?>
		<img class="itemimage" src="uploads/<?php print $this->item['image'] ?>" />
		<?php endif; ?>
		<div style="white-space:pre-line">
			<?php print $this->item['description'] ?>
		</div>
		<?php if ($this->item['serial']): ?>
		<label>Serial Number</label>
		<?php print $this->item['serial'] ?>
		<?php endif; ?>

	</div>
</div>
<?php print_template('templates/events.php', array('events' => $this->item['events'], 'user' => $this->user, 'item' => $this->item, 'morelink' => $this->morelink, 'comments' => true)); ?>