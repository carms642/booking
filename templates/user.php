<div class="details">
<div class="titleblock">
	<div class="titlepanel">
		<div class="title" style="white-space: nowrap">
			<?php print $this->user['name'] ?>
		</div>
		<a  style="white-space: nowrap" href="mailto:<?php print $this->user['email'] ?>"><?php print $this->user['email'] ?></a>
        <?php if(can_edit_user($this->current_user, $this->user)): ?>
    	<a class="adminbutton" href="user.php?id=<?php print $this->user['id'] ?>&action=edit">Edit</a>
    	<?php endif; ?>
    	<?php if($this->current_user['id'] == $this->user['id']): ?>
    		<a class="adminbutton" href="command/logout.php">Logout</a>
    	<?php endif; ?>
	</div>
</div>
<?php print_template('templates/events.php', array('events' => $this->events, 'ignoreuser' => true)); ?>
</div>
