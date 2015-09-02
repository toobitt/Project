<?php
/**
*
*
	$str = array(
		'action' => 'restart',
		'env' => 'nginx',
		'user' => 'root',
		'pass' => 'nginx',
	);
	
	echo '<textarea rows="40" cols="120">' . $data . '</textarea><br />';
*/
require('./global.php');
$action = $_REQUEST['action'];
if (!$_REQUEST['file'] || !$_REQUEST['id'])
{
	header('Location:./index.php');
}
$server = $servers[$_REQUEST['id']];
if (!$server)
{
	header('Location:./index.php');
}
$file = $Cfg['servertype'][$server['type']]['conf'][$_REQUEST['file']];
if (!$file)
{
	header('Location:./index.php');
}
$sock = new hgSocket();
switch ($action)
{
	case 'df':
		$configs = hg_run_cmd($sock, $server, 'df');
		$doaction = 'babbaa';
		include('tpl/man.tpl.php');
		break;
	case 'getfile':
		$configs = get_serv_file($sock, $server, $file);
		$doaction = 'dowritefile';
		include('tpl/man.tpl.php');
		break;
	case 'dowritefile':
		$configs = write_serv_file($sock, $server, $file, $_REQUEST['content']);
		header('Location:./man.php?action=getfile&id=' . $_REQUEST['id'] . '&file=' . $_REQUEST['file']);
		break;
	default:
		$configs = 'No specify file';
		include('tpl/man.tpl.php');
		break;
}

?>