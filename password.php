<?php 
include_once "includes/template.php";

print_template('templates/page.php', array(
    	'content' => array(new Template('templates/resetrequest.php'))));

?>