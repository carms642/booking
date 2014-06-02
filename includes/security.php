<?php 
$salt = "D9.~hSioUOopOAJro=0MLL~[t*{TVOwS>k1M:EpG";

function generate_password ($length = 8)
{
	// given a string length, returns a random password of that length
	$password = "";
	// define possible characters
	$possible = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$i = 0;
	// add random characters to $password until $length is reached
	while ($i < $length) {
		// pick a random character from the possible ones
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		// we don't want this character if it's already in the password
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	return $password;
}

?>