<?php

require 'global.php';
require(ROOT_PATH.'lib/class/curl.class.php');
$curl = new curl($gGlobalConfig['officeconvert']['host'], $gGlobalConfig['officeconvert']['dir']);
$curl->setSubmitType('post');
$curl->setReturnFormat('str');
$curl->initPostData();
//$curl->setCurlTimeOut(60 * 2);
$curl->setClient(CUSTOM_APPID, CUSTOM_APPKEY);
$curl->addFile($_FILES);
$result = $curl->request('convert');
if(!$result){
    echo json_encode(array('error' => true));
    exit();
}
$path = 'cache/word/zip_'.uniqid().'/';
$zipDir = ROOT_PATH.$path;
if(hg_mkdir($zipDir) && is_writeable($zipDir)){
    $zipFile = $zipDir.'word.zip';
    file_put_contents($zipFile, $result);

    $unzipDir = $zipDir.'unzip/';
    if(hg_mkdir($unzipDir) && is_writeable($unzipDir)){
        $unzipCmd = ' unzip ' . $zipFile . ' -d ' . realpath($unzipDir);
        exec($unzipCmd);
        $rmZip = ' rm -f '.$zipFile;
        exec($rmZip);

        echo json_encode(array('ok' => 1, 'path' => $path.'unzip/', 'url' => $path.'unzip/index.html'));
        exit();
    }
}
echo json_encode(array('error' => true));
exit();
