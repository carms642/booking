<div class="details">
	<div class="titleblock">
		<form class="titlepanel" action="command/login.php" method="post">
			<input class="text" type="email" maxlength="64" name="email" size="16" placeholder="Email" required autofocus spellcheck="false" />
			<?php if($this->error['param'] == "email"):?>
			<div class="error">			
				<?php print $this->error['error'] ?>
			</div>
			<a href="password.php">forgotten password?</a>
			<?php endif; ?>
			
			<input class="text spacer" type="password" maxlength="64" name="password" size="16" placeholder="Password" required />
			<?php if($this->error['param'] == "password"):?>
			<div class="error">			
				<?php print $this->error['error'] ?>
			</div>
			<?php endif; ?>
			<div style="text-align: center; font-size: smaller;"><a href="password.php">forgotten password?</a></div>
			<div class="right">
			<input type="submit" class="book" style="margin-right: 0px;" name="submit" value="Sign in" />
			</div>
		</form>
	</div>
	<img class="titleimage" src="images/jubilee.jpg" style="max-width: 100%" alt="Jubilee Campus, Home of the MRL">
</div>
<div class="spacer" style="text-align: justify">
	Welcome to the booking system for the Mixed Reality Laboratory (MRL). This site allows you to book equipment for use
	in MRL associated projects. You will need to log in to use the features of the booking system. If you don't have a
	username, please contact <a href="mailto:">Samantha Stapleford</a>.
</div>