<?php include_once "includes/formatting.php"; ?>

<div class="details">
<div class="titleblock">
	<div class="titlepanel">
		<div class="title">
			<?php print $this->booking['reason']; ?>
		</div>
    	<div><?php print format_daterange($this->booking['start'], $this->booking['end'], true, true); ?></div>
		<div><a href="user.php?id=<?php print $this->booking['user'] ?>"><?php print $this->booking['name'] ?></a></div>
        <a class="adminbutton" href="booking.php?id=<?php print $this->booking[id]?>&action=edit">Edit</a>
	</div>
</div>

<div>
<?php print_template('templates/bookingitemlist.php', array('items' => $this->booking['items'], 'user' => $this->user, 'booking' => $this->booking)); ?>
</div>
</div>
<?php print_template('templates/events.php', array('events' => $this->events, 'user' => $this->user, 'booking' => $this->booking, 'comments' => true)); ?>
