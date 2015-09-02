<?php
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'kepp_alive');
require('./global.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
$curl = new curl($gGlobalConfig['App_live']['host'], $gGlobalConfig['App_live']['dir']);
$curl->setSubmitType('post');		
$curl->setReturnFormat('json');
$curl->initPostData();
$ret = $curl->request('alive.php');	
$ret = $ret[0];

if(is_array($ret))
{
	$dom = new DOMDocument('1.0', 'utf-8');
	$keep = $dom->createElement('keepAlive');
	$keep->setAttribute('current',$ret['current']);
	$dom->appendChild($keep);	
}
header('Content-type: text/xml; charset=UTF-8');
echo $dom->saveXml();
exit;
?>