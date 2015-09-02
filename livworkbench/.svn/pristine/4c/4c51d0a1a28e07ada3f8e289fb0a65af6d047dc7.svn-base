<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require 'global.php';
require(ROOT_PATH.'lib/class/curl.class.php');
$curl = new curl($gGlobalConfig['App_photoedit']['host'],$gGlobalConfig['App_photoedit']['dir']); 

//curl请求图片服务器接口
$curl->setSubmitType('post');
$curl->setReturnFormat('json');
$curl->initPostData();
$curl->addRequestData('a','create');
$curl->addRequestData('imgdata',$_REQUEST['file']);
$curl->addRequestData('oldurl',$_REQUEST['origurl']);
$ret = $curl->request('photoedit_update.php');
$ret = $ret[0];

//echo json_encode(array('url' => $ret['host'] . $ret['dir'] . $ret['filepath'] . $ret['filename']. '?'.time()));
echo json_encode(array('url' => array(
    'host' => $ret['host'],
    'dir' => $ret['dir'],
    'filepath' => $ret['filepath'],
    'filename' => $ret['filename']
)));


/*
$imgdata = $_REQUEST['file'];
$dir = '../cache/demo';
if(!is_dir($dir)){
    mkdir($dir);
    chmod($dir, 0777);
}
$oldurl = $_REQUEST['origurl'];
$urls = parse_url($oldurl);
$info = explode('.', $urls['path']);
$type = $info[count($info) - 1];
$type = '.'.$type;
$name = time().$type;
$lujin = $dir.'/'.$name;
file_put_contents($lujin, base64_decode($_REQUEST['file']));

$host = $_SERVER["HTTP_HOST"];
$request = $_SERVER["REQUEST_URI"];
if(strpos($request, 'livworkbench') !== false){
    $host = $host.'/livworkbench';
}
echo json_encode(array('url' => 'http://'.$host.'/canvas/'.$lujin));
*/
?>