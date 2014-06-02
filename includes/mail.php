<?php
include_once "class.phpmailer.php";

function &get_mail()
{
	$mail = new PHPMailer;
	$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'pat.cs.nott.ac.uk;smtp.nottingham.ac.uk';  // Specify main and backup server
	$mail->IsHTML(true);
	
	return $mail;
}	
?>