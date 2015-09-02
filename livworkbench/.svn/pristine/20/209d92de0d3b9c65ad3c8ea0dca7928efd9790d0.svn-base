<?php
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'crontab');
define('WITHOUT_LOGIN', true);
require('./global.php');

$sql = 'SELECT * FROM ' . DB_PREFIX . 'crontab where is_use=1 and run_time<=' . TIMENOW;
$q = $gDB->query($sql);
while($r = $gDB->fetch_array($q))
{
	$cron[] = array(
		'host' => $r['host'],
		'port' => $r['port'],
		'dir' => $r['dir'],
		'file' => $r['file_name'],
		'token' => $r['token']	
	);
	//$runtime = strtotime(date('Y-m-d') . ' ' . date('H:i:s', $r['run_time']));
	$sql = "UPDATE " . DB_PREFIX . "crontab SET run_time=" . (TIMENOW + $r['space']) . " WHERE id=" . $r['id'];
	$gDB->query($sql);
}
echo json_encode($cron);
?>