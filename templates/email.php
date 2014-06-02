<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="font-family: Verdana, Arial, sans-serif; color: black; font-size: 13px;">
	<div><a href="http://www.cs.nott.ac.uk/mrlbooking/index.php"><img style="border: 0" src="http://www.cs.nott.ac.uk/mrlbooking/images/logo.png" alt="Mixed Reality Lab" /></a></div>
	<div style="color: #FFF; font-size: 18px; background: #474747; background: -webkit-linear-gradient(#555, #222); background: -moz-linear-gradient(#555, #222); background: -ms-linear-gradient(top, #555 0%, #222 100%); background: linear-gradient(#555, #222); padding: 7px 55px;">Booking System</div>
	<div style="padding: 20px;">
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
