<?php
header('Access-Control-Max-Age:' . 5 * 60 * 1000);
header("Content-Type: application/javascript");

$url = rawurldecode($_REQUEST['url']);
$callback = $_REQUEST['callback'];
if(!$url || !$callback) exit();

$curl = curl_init();
curl_setopt($curl, CURLOPT_DNS_USE_GLOBAL_CACHE, true);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_NOBODY, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
define('CURLOPT_IPRESOLVE', 113);
define('CURL_IPRESOLVE_V4', 1);
curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$imgData = curl_exec($curl);
$mimeType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
curl_close($curl);

if(!$mimeType){
    $imgInfo = parse_url($url);
    $path = explode('.', $imgInfo['path']);
    $type = $path[count($path) - 1];
    !$type == 'jpeg' && $type = 'jpg';
    !$type && $type = 'png';
    $mimeType = 'image/' . $type;
}

echo $callback . '("data:' .$mimeType. ';base64,' . base64_encode($imgData) . '");';
?>