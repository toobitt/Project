<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
set_time_limit(0);
require 'global.php';
$mid = $_REQUEST['mid'];
if(is_array($mid)){
    foreach($mid as $k => $v){
        $v = intval($v);
        $v && $gTpl->buildres($v);
    }
}else{
    $mid = intval($mid);
    $gTpl->buildres($mid);
}
exit();









/*
function getApplication(){
    global $db, $mid;
    $sql = "SELECT * FROM " . DB_PREFIX . "modules WHERE id=" . $mid;
    $module = $db->query_first($sql);
    if(!$module){
        exit('运行模块不存在');
    }
    $application = hg_check_application(intval($module['application_id']));
    if (!$application){
        exit('应用不存在或已被删除');
    }
    return $application;
}

function changeCss($dir, $root = false){
    if(!$root){
        $root = $dir;
    }
    if(is_dir($dir) && ($dh = opendir($dir))){
        while(false !== ($file = readdir($dh))){
            if($file != '.' && $file != '..'){
                if(is_dir($dir . $file . '/')){
                    changeCss($dir . $file . '/', $root);
                }else{
                    if(preg_match('/.css$/', $file)){
                        $content = file_get_contents($dir . $file);
                        $content = preg_replace("/{\\$[a-zA-Z0-9_\[\]\-\'\>]+}/", RESOURCE_URL, $content);
                        hg_file_write($dir . $file, $content);
                    }
                }
            }
        }
        closedir($dh);
    }
}

$db = hg_checkDB();
$mid = intval($_REQUEST['mid']);

if(!$mid){
    $softvar = 'lib';
    $group = '';
}else{
    $application = getApplication();
    $softvar = $application['softvar'];
    $group = 'default';
}



require(ROOT_PATH.'lib/class/curl.class.php');
$curl = new curl('localhost', 'livtemplates/');
$curl->setSubmitType('post');
$curl->setReturnFormat('str');
$curl->addRequestData('softvar', $softvar);
$curl->addRequestData('group', $group);
$result = $curl->request('buildres.php');
if(!result){
    exit('出错拉！！！');
}

$zipDir = ROOT_PATH . 'cache/buildres/zip_' . uniqid() . '/';
$unzipDir = ROOT_PATH . 'res/' . $softvar . '/';
exec(' rm -fR ' . $unzipDir);
if(hg_mkdir($zipDir) && is_writeable($zipDir)){
    $zipFile = $zipDir.'buildres.zip';
    file_put_contents($zipFile, $result);

    $doComplete = false;
    if(hg_mkdir($unzipDir) && is_writeable($unzipDir)){
        exec(' unzip ' . $zipFile . ' -d ' . realpath($unzipDir));
        $cssArr = array($group);
        if($group != 'default'){
            $cssArr[] = 'default';
        }
        foreach($cssArr as $k => $v){
            if(is_dir($unzipDir . $v . '/css/')){
                changeCss($unzipDir. $v . '/css/');
            }
        }
        $doComplete = true;
    }
    exec(' rm -fr '.$zipDir);
    if($doComplete){
        echo json_encode(array('ok' => true));
        exit();
    }
}
echo json_encode(array('error' => true));
exit();
*/