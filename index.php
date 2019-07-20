<?php
function clean($str){

	return htmlentities($str, ENT_QUOTES);
}

$username = @clean((string)$_GET['username']);
$password = @clean((string)$_GET['password']);
print('user:'.$username);
print('<br >'.$password);
$query='SELECT * FROM users WHERE name=\''.$username.'\' AND pass=\''.$password.'\';';
print('<br />'.$query);
?>