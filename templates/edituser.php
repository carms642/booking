<form name="userform" action="command/edituser.php" method="post">
<div class="details">
<div class="titleblock">
    <div class="titlepanel">
        <input name="id" type="hidden" value="<?php print $this->user['id'] ?>" />
		<input name="name" type="text" class="title text" value="<?php print $this->user['name'] ?>" placeholder="Name"	maxlength="50" required />
		<input name="email" type="text" class="text" value="<?php print $this->user['email'] ?>" placeholder="Email" maxlength="20" required />
    <div><div class="fieldname"></div><div class="fielditem">
    <?php if($this->user['admin']): ?>
	<input type="checkbox" class="fieldinput" name="admin" id="admin" checked="checked" value="1" />
	<?php else: ?>
	<input type="checkbox" class="fieldinput" name="admin" id="admin" value="1" />
	<?php endif; ?>
	<label for="admin"><span></span>Administrator</label></div>

        <?php if($this->user['enabled']): ?>
        <a class="adminbutton" href="user.php?id=<?php print $this->user['id'] ?>&action=edit">Disable User</a>
        <?php else: ?>
        <a class="adminbutton" href="user.php?id=<?php print $this->user['id'] ?>&action=edit">Enable User</a>
    	<?php endif; ?>

</div>
	</div>
</div>
<?php if($this->user['id'] == $this->current_user['id']): ?>
<div class="fill">
<div class="field">
    <label for="password" class="fieldname">New Password</label><div class="fielditem">
    <input type="password" name="password" id="password" class="fieldinput"></div>
</div>
<div class="field">
    <label for="passconfirm" class="fieldname">Confirm Password</label><div class="fielditem">
    <input type="password" name="passconfirm" id="passconfirm" class="fieldinput"></div>
</div>
</div>
<?php endif; ?>
</div>

<div class="right">
	<input type="submit" class="book fieldbutton" value="Save Changes" />
</div>

</form>

<?php if($this->user['id'] != $this->current_user['id']): ?>
<div class="right spacer">
	<form name="resetpass" action="command/resetpassword.php" method="get">
        <input name="id" type="hidden" value="<?php print $this->user['id'] ?>" />	
		<input type="submit" class="button fieldbutton" value="Reset Password" />
	</form>
</div>
<?php endif; ?>