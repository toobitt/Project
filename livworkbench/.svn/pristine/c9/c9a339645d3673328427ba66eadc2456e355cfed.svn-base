<?php

function base64_encode_image ($filename, $filetype) {
    $filename = trim($filename);
    $imgbinary = file_get_contents($filename);
    return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
}


$callback = $_GET['callback'];
$url = trim(urldecode($_GET['url']));
$urlInfo = parse_url($url);
$info = explode('.', $urlInfo['path']);
$type = $info[count($info) - 1];
$type = strtolower($type);
if($type == 'jpeg'){
    $type = 'jpg';
}

$curl = curl_init();
curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
define('CURLOPT_IPRESOLVE', 113);
define('CURL_IPRESOLVE_V4', 1);
curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$img = curl_exec($curl);
curl_close($curl);	
//exit;
//echo file_get_contents($url);
//exit();
$json = json_encode(array('data' => base64_encode_image($url, $type)));
echo $callback.'('.$json.')';

?>