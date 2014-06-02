<?php
require 'includes/mail.php';

$mail = get_mail();

$mail->From = 'ktg@cs.nott.ac.uk';
$mail->FromName = 'MRL Booking System';
$mail->AddAddress('ktg@cs.nott.ac.uk', 'Kevin Glover');  // Add a recipient

$mail->Subject = 'MRL Booking System Test';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->Send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

?>