<?php
define('WITH_DB', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'crontab');
require('../global.php');

header('HTTP/1.1 200 OK',true,200);
$dbname = trim($_INPUT['dbname']);
if ($dbname)
{
	$dbname = $dbname . '.';
}
$table = trim($_INPUT['table']);
$condition = trim($_REQUEST['condition']);
if (!$table)
{
	$data = array();
	echo json_encode($data);
	exit;
}
if (!in_array($table, array('applications', 'modules', 'module_append', 'module_node', 'module_op', 'node', 'crontab','menu')))
{
	//$data = array();
	//echo json_encode($data);
	//exit;
}
$sql = 'SELECT * FROM ' . $dbname . DB_PREFIX . $table . ' ' . $condition;
$q = $gDB->query($sql);
$data = array();
while($r = $gDB->fetch_array($q))
{
	if ($r['id'])
	{
		$data[$r['id']] = $r;
	}
	else
	{
		$data[] = $r;
	}
}
echo json_encode($data);
?>