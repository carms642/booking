<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/modernizr.custom.50114.js"></script>
<script src="js/polyfiller.js"></script>

<script>
    $.webshims.setOptions('forms-ext', {
    datepicker: {
        dateFormat: 'd M yy'
    }});
    $.webshims.polyfill('forms forms-ext');
</script>

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
				<a href="items.php?tag=<?php print $tag['id'] ?>"><?php print $tag['text'] ?></a>
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
			<?php if($this->item['holder']): ?>
			<div>Currently with: <a href="user.php?id=<?php print $this->item['contact'] ?>"><?php print $this->item['username'] ?> </a></div>
			<?php endif; ?>
		</div>
		<?php if ($this->item['image']): ?>
		<img class="itemimage spacer" src="uploads/<?php print $this->item['image'] ?>" />
		<?php endif; ?>
	</div>
	<form class="fill" name="bookitem" action="command/addbooking.php" method="post">
		<input type="hidden" name="item" value="<?php print $this->item['code'] ?>" />

		<div class="field">
			<label for="reason" class="fieldname">Reason for Booking</label><div class="fielditem">
			<input class="fieldinput" type="text" maxlength="100" name="reason" id="reason" value="<?php print $_POST[reason] ?>" autofocus required
			placeholder="Reason" />
		</div></div>

		<div class="field">
			<label for="start" class="fieldname">Start Date</label><div class="fielditem">
			<input class="fieldinput fielddate" type="date" name="start" id="start" min="<?php print date("Y-m-d") ?>" value="<?php print date("Y-m-d") ?>" required />
		</div></div>
		<div class="field">
			<label for="end" class="fieldname">End Date</label><div class="fielditem">
			<input class="fieldinput fielddate" type="date" name="end" id="end" min="<?php print date("Y-m-d") ?>" value="<?php print date("Y-m-d") ?>" required />
		</div></div>
		<?php if($this->error['param'] == "end"):?>
			<div class="fielderror">
				<?php print $this->error['error'] ?>
			</div>
		<?php endif; ?>

        <?php if(can_collect($this->user, null, $this->item)): ?>
		<div class="field">
			<div class="fieldname"></div><div class="fielditem">
			<input class="fieldinput" type="checkbox" name="collected" id="collected" value="true" />
			<label for="collected"><span></span>This item has already been collected</label>
		</div></div>
        <?php endif; ?>

		<?php if(is_admin($this->user)): ?>
        <script>
            var users = [];
            <?php foreach($this->users as $user): ?>
                users[<?php print "'".$user['email']."'" ?>] = "<?php print $user['name'] ?>";
            <?php endforeach;?>

            function usersname_changed()
            {
                var usersname = document.getElementById("usersname").value;
            	for(user in users)
            	{
            		if(users[user].toLowerCase() == usersname.toLowerCase())
            		{
                        var usersemail = document.getElementById("usersemail");
                        usersemail.value = user;
            			return;
            		}
            	}
            }

            window.onload = function()
            {
                document.getElementById('usersname').addEventListener('input',usersname_changed,false);
            }
        </script>
		<div class="field spacer">
			<?php if($this->users): ?>
				<datalist id="usernames">
					<?php foreach($this->users as $user): ?>
						<?php if($user['id'] != $this->user['id']): ?>
							<option value="<?php print $user['name'] ?>" />
						<?php endif; ?>
					<?php endforeach; ?>
				</datalist>
				<datalist id="useremails">
					<?php foreach($this->users as $user): ?>
						<?php if($user['id'] != $this->user['id']): ?>
							<option value="<?php print $user['email'] ?>" />
						<?php endif; ?>
					<?php endforeach; ?>
				</datalist>
			<?php endif; ?>

			<label for="usersname" class="fieldname">User's Name</label><div class="fielditem">
			<input class="fieldinput" type="text" maxlength="100" name="usersname" id="usersname" list="usernames" placeholder="Name" />
		</div></div>
		<div class="field">
			<label for="usersemail" class="fieldname">User's Email</label><div class="fielditem">
			<input class="fieldinput" type="email" maxlength="100" name="usersemail" id="usersemail" list="useremails" placeholder="Email" />
		</div></div>
		<div class="fieldinfo">Optional. If booking an item on someone else's behalf, put their name and email here</div>
		<?php endif; ?>


		<div class="right">
			<input type="submit" class="book spacer fieldbutton" value="Book Item" />
		</div>

	</form>
</div>
<?php print_template('templates/events.php', array('events' => $this->item['events'], 'user' => $this->user, 'item' => $this->item)); ?>