<?php
include_once "includes/formatting.php";
include_once "includes/validate.php";
?>
<div class="events">
    <?php foreach ($this->events as $event): ?>
	<div class="event<?php if($event['start'] && is_booking_expired($event)) { print " returned"; } ?>">
		<?php if($event['time']): ?>
		<div class="eventtitle">
			<a href="user.php?id=<?php print $event['username'] ?>"><?php print $event['name'] ?></a><span class="time"><?php print format_date($event['time']); ?></span>
		</div>
		<div class="eventinfo">
			<?php print $event['text'] ?>
		</div>
		<?php elseif($event['end']): ?>
		<div class="eventtitle<?php if(is_booking_overdue($event)) { print " overdue"; } else if($this->item['current'] == $event['id']) { print " current"; } ?>">
			<?php if(!$this->ignoreuser): ?>
				<a href="user.php?id=<?php print $event['user'] ?>"><?php print $event['name'] ?></a> - <a href="booking.php?id=<?php print $event['id'] ?>"><?php print $event['reason'] ?></a>
			<?php else: ?>
				<a href="booking.php?id=<?php print $event['id'] ?>"><?php print $event['reason'] ?></a>
			<?php endif; ?>
            <span class="time"><?php print format_daterange($event['start'], $event['end']); ?></span>
		</div>
		<div class="eventinfo">
		<?php print_template('templates/bookingitemlist.php', array('items' => $event['items'], 'user' => $this->user, 'booking' => $event)); ?>
		</div>

		<?php endif; ?>
	</div>
	<?php endforeach; ?>
    <?php if($this->morelink): ?>
	<div class="eventtitle"><a href="<?php print $this->morelink['link'] ?>"><?php print $this->morelink['text'] ?></a></div>
    <?php endif; ?>
	<?php if($this->comments): ?>
	<div class="event">
		<form action="command/addcomment.php" method="post">
			<div class="field">
				<label class="fieldname">Comment</label><div class="fielditem">
				<input type="text" class="fieldinput" name="comment" placeholder="Add a Comment" required />
                </div>
			</div>
            <?php if($this->item): ?>
			<input type="hidden" name="item" value="<?php print $this->item['code'] ?>" />
    		<?php if(can_edit_item($this->user, $this->item)):?>
    		<div class="field">
				<label class="fieldname"></label>
				<input class="fieldinput" type="checkbox" id="status" name="status" value="true" /><label for="status"><span></span>Display as item status</label>
			</div>
			<?php endif; ?>
            <?php elseif($this->booking): ?>
    		<input type="hidden" name="booking" value="<?php print $this->booking['id'] ?>" />
            <?php endif; ?>
			<div class="right">
			<input type="submit" class="button fieldbutton" name="submit" value="Add Comment" />
			</div>
		</form>
	</div>
	<?php endif; ?>
</div>