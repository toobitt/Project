<?php
//curl
$token=$_GET['access_token'];
include_once(ROOT_PATH . 'lib/class/curl.class.php');
$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
$postdata = array(
                'appid' => '9',
                'appkey' => '9Wb0h50vwfArhjTwjUTAIm3NXEeLKaGK',
                'access_token' => $token,
                'a' => 'get_user_info',
);
foreach ($postdata as $k => $v)
{
        $curl->addRequestData($k, $v);
}
$ret = $curl->request('get_access_token.php');
?>