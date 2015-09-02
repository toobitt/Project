<?php
// wait a second to simulate a some latency

$remote = $_REQUEST['remote'];
if ( $remote == 'yudoudou' ) {
	$msg = 'true';
} else {
	$msg = '"' . $remote . '不等于yudoudou"';
}
echo $msg;
exit;

sleep(10);
$user = $_REQUEST['user'];
$pw = $_REQUEST['password'];
if($user && $pw && $pw == "foobar")
	echo "Hi $user, welcome back.";
else
	echo "Your password is wrong (must be foobar).";
?>