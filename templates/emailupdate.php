<?php foreach ($this->bookings as $booking): ?>
<div>
	<div style="padding: 5px 20px; background: #EEE; border-radius: 3px; font-weight: bold; display: inline-block;">
		<a href="http://www.cs.nott.ac.uk/mrlbooking/user.php?id=<?php print $booking['user'] ?>"><?php print $booking['name'] ?></a> - <a href="http://www.cs.nott.ac.uk/mrlbooking/booking.php?id=<?php print $booking['id'] ?>"><?php print $booking['reason'] ?></a>
        <span style="margin-left: 20px;
font-size: 11px;"><?php print format_daterange($booking['start'], $booking['end']); ?></span>
	</div>
	<div style="margin-left: 20px">
	<?php foreach($booking['items'] as $item): ?>
	<?php if($item['collected'] && !$item['returned']): ?>
	<div style="display: -webkit-flex; -webkit-align-items: center;">
		<?php if($item['image']):?>
		<a href="http://www.cs.nott.ac.uk/mrlbooking/item.php?code=<?php print $item['code'] ?>"><img style="display: inline-block; margin-right: 10px; max-width: 64px;" src="http://www.cs.nott.ac.uk/mrlbooking/uploads/<?php print $item['image'] ?>" /></a>
		<?php endif;?>	
		<div style="display: inline-block;<?php if(!$item['image']) { print " margin-left: 74px;"; } ?>">
			<div>
				<a style="font-weight: bold; margin-right: 20px;" href="http://www.cs.nott.ac.uk/mrlbooking/item.php?code=<?php print $item['code'] ?>"><?php print $item['name'] ?></a>
				<span style="font-size: smaller"><?php print $item['code'] ?></span>
	
				<?php if($item['status']): ?>
				<div><?php print $item['status'] ?></div>
				<?php endif; ?>
			</div>
			<div>
				<?php if($item['returned']): ?>
				<div>Returned <?php print format_date($item['returned']) ?></div>	
				<?php elseif($item['collected']): ?>
				<div>Collected <?php print format_date($item['collected']) ?></div>
				<div>Return to <a href="user.php?id=<?php print $item['contact'] ?>"><?php print $item['username'] ?></a></div>
				<?php endif; ?>			
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php endforeach; ?>
	
	</div>
</div>
<?php endforeach; ?>