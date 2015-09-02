<?php
define('WITH_DB', true);
define('ROOT_DIR', '../');

require('../global.php');
$type = $_INPUT['type'];
$id = $_INPUT['id'];
if($type)
{
	$con .= ' AND type ='.$type;
}
if($id)
{
	$con .= ' AND id ='.$id;
}
$sql = 'SELECT * FROM ' . DB_PREFIX . 'servers where 1 '.$con;

$q = $gDB->query($sql);

while($r = $gDB->fetch_array($q))
{
	$info[] = $r;
	
}
foreach($info as $k=>$v)
{
	$e = $gDB->query('SELECT * FROM '. DB_PREFIX .'servers_extend WHERE sid='.$v['id']);
	while ($row = $gDB->fetch_array($e))
	{
		$extend[] = $row;
	}
	$info[$k]['extend'] = $extend;
}


echo json_encode($info);
?>