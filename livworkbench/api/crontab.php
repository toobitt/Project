<?php
define('WITH_DB', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'crontab');
require('../global.php');

$sql = 'SELECT * FROM ' . DB_PREFIX . 'crontab where 1 and run_time<=' . TIMENOW;
$q = $gDB->query($sql);
include_once(ROOT_PATH . 'lib/class/log.class.php');
$log = new hglog();
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
	if ($r['is_log'])
	{
		$logcontent = $r['host'] . '/' .  $r['dir'] .  $r['file_name'] . '已执行';
		$log->add_log($logcontent, 'log');
	}
}
echo json_encode($cron);
?>