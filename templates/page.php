<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta content="minimum-scale=1.0, width=device-width, maximum-scale=1.0, user-scalable=no" name="viewport" />
<title><?php if($this->title) { print $this->title . " - "; }?>MRL Booking System</title>
<link rel="stylesheet" type="text/css" href="styles/booking.css" />
<link rel="stylesheet" type="text/css" href="styles/fields.css" />
</head>
<body>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-7942548-4', 'nott.ac.uk');
      ga('send', 'pageview');

    </script>
	<div class="header">
		<a href="index.php"><img class="logo" src="images/logo.png" alt="Mixed Reality Lab" /></a>
        <?php if($this->user): ?>
		<form action="items.php" class="searchform" method="get" <?php if($this->search) { print "style='display:none'"; } ?>>
			<input class="search" type="text" name="search" placeholder="Search" value="<?php print $this->search ?>" />
			<button class="searchbutton" type="submit"></button>
		</form>
        <?php endif; ?>
	</div>
	<div class="navigation">
        <?php if($this->user): ?>
    	<div class="user">
			<a href="user.php"><?php print $this->user['name'] ?> </a>
		</div>
        <?php endif; ?>
		Booking System
	</div>
    <?php if($this->user): ?>
	<form action="items.php" class="searchform2 <?php if($this->search) { print "visible"; } ?>" method="get">
		<input class="search" type="text" name="search" placeholder="Search" value="<?php print $this->search ?>" />
		<button class="searchbutton" type="submit"></button>
	</form>
    <?php endif; ?>
	<div class="content">
		<?php
		if(is_array($this->content))
		{
			foreach($this->content as $content)
			{
				$content->render();
			}
		}
		else if($this->content)
		{
			$this->content->render();
		}
		?>

	</div>
</body>
</html>
