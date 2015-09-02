<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
set_time_limit(0);
$softvar = str_replace(array('.', '\/'), '', trim($_REQUEST['softvar']));
!$softvar && $softvar = 'lib';
$group = str_replace(array('.', '\/'), '', trim($_REQUEST['group']));
!$group && $group = 'default';

$resArr = array(
    'js', 'css', 'images'
);

$currentDir = realpath('./');
$zipCache = $currentDir . '/zipcache/';
if(!is_dir($zipCache)){
    @mkdir($zipCache, 0777);
}
$tmpDir = $zipCache . uniqid() . '/';
@mkdir($tmpDir, 0777);
if($softvar == 'lib'){
    $cpArr = array(array('lib', 'tpl/lib/'));
}else{
    $cpArr = array(array($group , 'tpl/' . $softvar . '/' . $group . '/'));
    if($group != 'default'){
        $cpArr[] = array('default', 'tpl/' . $softvar . '/default/');
    }
}
foreach($cpArr as $k => $v){
    @mkdir($tmpDir . $v[0] . '/', 0777);
    $realPath = realpath($v[1]);
    foreach($resArr as $kk => $vv){
        if(is_dir($currentDir . '/' . $v[1] . $vv . '/')){
            exec(' cp -r ' . $realPath . '/' .$vv . ' ' . $tmpDir . $v[0] . '/');
        }

    }
}
$zipFile = $tmpDir.'tmp.zip';
if($softvar == 'lib'){
    exec(' cd '.$tmpDir.'lib/ && zip -r '.$zipFile. ' ' . implode('/ ', $resArr) . '/');
}else{
    $needZipDir = '';
    foreach($cpArr as $k => $v){
        $needZipDir .= $v[0] . '/ ';
    }
    if($needZipDir){
        exec(' cd '.$tmpDir.' && zip -r '.$zipFile. ' '.$needZipDir);
    }
}
echo file_get_contents($zipFile);
exec(' rm -fr ' . $tmpDir);
