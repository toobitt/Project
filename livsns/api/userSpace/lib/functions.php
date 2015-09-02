<?php
function spaceNameGenerate($data)
{
	$re = implode(SPACENAMELINKS, $data);
	return (SPACENAMEPREFIX).$re;
}
function domainNameGenerate($data)
{
	$re = implode(SPACEDOMAINLINKS, $data);
	return SPACEDOMAINPREFIX.$re;
}
function divisor($m,$n)
{
  	if($m % $n == 0) 
  	{
  		return $n;
  	}
  	else
  	{
   		return divisor($n, $m % $n);
  	}
}
function hg_build_objid($length)
{
	// 密码字符集，可任意添加你需要的字符  
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  
	$randnum = '';  
	for ( $i = 0; $i < $length; $i++ )  
	{ 
		$randnum .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
	}  
	return $randnum;  

}
function player_code($id, $param)
{
	$object_id = hg_build_objid(14);
	$vuid = encrypt($id , ENCRYPT_VID_KEY);
	
	if(!defined('REWRITE') || !REWRITE)
	{
		$swf = SWF_PLAYER_URL . '?config=' . $param['config_xml'] . '&video=' . $vuid . 'autoPlay='.$param['auto_play'];
		$code = array(
		'swf'=>$swf,
		'html'=>'<embed src="'.SWF_PLAYER_URL.'" allowFullScreen="true" quality="high"  width="'.$param['width'].'" height="'.$param['height'].'" align="middle" allowScriptAccess="always" flashvars="video='.$vuid.'&config='.$param['config_xml'].'&autoPlay='.$param['auto_play'].'" type="application/x-shockwave-flash"></embed>',
		'javascript'=>'<script type="text/javascript">var hogecloud_player_conf =  {"domain":"'.CLOUD_VIDEO_DOMIAN.'","video":"'.$vuid.'","config":"'.$param['config_xml'].'","auto_play":'.$param['auto_play'].',"gpcflag":1,"width":'.$param['width'].',"height":'.$param['height'].'};</script>',
		'url'=>CLOUD_VIDEO_DOMIAN . '/hcloud.html?video='.$vuid.'&config='.$param['config_xml'].'&auto_play='.$param['auto_play'].'&gpcflag=1&width='.$param['width'].'&height='.$param['height'],
		'auto'=>'<script id="autoJs'.$object_id.'" type="text/javascript">var pNode=document.getElementById("autoJs'.$object_id.'").parentNode,dWidth=pNode.clientWidth,pHeight=pNode.clientHeight,dHeight=ReCallHeight(pHeight,pNode);function ReCallHeight(e,t){if(e==""||e=="100%"){var n=t.parentNode,r=t.parentNode.clientHeight;if(r) return r;var i=ReCallHeight(r,n);return i}return e}var hogecloud_player_conf =  {"domain":"'.CLOUD_VIDEO_DOMIAN.'","video":"'.$vuid.'","config":"'.$param['config_xml'].'","auto_play":'.$param['auto_play'].',"gpcflag":1,"width": dWidth,"height": dHeight};</script>'
		);
	}
	else
	{
		$swf_domain = pathinfo(SWF_PLAYER_URL);
		$swf_domain = $swf_domain['dirname'];
		$code = array(
		'swf'=>$swf_domain . '/' .  str_replace('vod.xml', '', $param['config_xml']) . '/' . $vuid . '/v.swf' . '?' . 'autoPlay='.$param['auto_play'],
		'html'=>'<embed src="'.SWF_PLAYER_URL.'" allowFullScreen="true" quality="high"  width="'.$param['width'].'" height="'.$param['height'].'" align="middle" allowScriptAccess="always" flashvars="video='.$vuid.'&config='.$param['config_xml'].'&autoPlay='.$param['auto_play'].'" type="application/x-shockwave-flash"></embed>',
		'javascript'=>'<script type="text/javascript"> var hogecloud_player_conf =  {"domain":"'.CLOUD_VIDEO_DOMIAN.'","video":"'.$vuid.'","config":"'.$param['config_xml'].'","auto_play":'.$param['auto_play'].',"gpcflag":1,"width":'.$param['width'].',"height":'.$param['height'].'};</script>',
		'url'=>CLOUD_VIDEO_DOMIAN . '/id_'.$vuid.'.html?config='.$param['config_xml'].'&auto_play='.$param['auto_play'].'&gpcflag=1&width='.$param['width'].'&height='.$param['height'],
		'auto'=>'<script id="autoJs'.$object_id.'" type="text/javascript">var pNode=document.getElementById("autoJs'.$object_id.'").parentNode,dWidth=pNode.clientWidth,pHeight=pNode.clientHeight,dHeight=ReCallHeight(pHeight,pNode);function ReCallHeight(e,t){if(e==""||e=="100%"){var n=t.parentNode,r=t.parentNode.clientHeight;if(r) return r;var i=ReCallHeight(r,n);return i}return e}var hogecloud_player_conf =  {"domain":"'.CLOUD_VIDEO_DOMIAN.'","video":"'.$vuid.'","config":"'.$param['config_xml'].'","auto_play":'.$param['auto_play'].',"gpcflag":1,"width": dWidth,"height": dHeight};</script>'
		);
	}
	$load_js = '<script type="text/javascript" src="'.CLOUD_VIDEO_DOMIAN.'/static/js/hcloud.js"></script>';
	$auto_js = '<script type="text/javascript" src="'.CLOUD_VIDEO_DOMIAN.'/static/js/module/jquery/jquery-1.8.3.min.js"></script>'.'<script type="text/javascript" src="'.CLOUD_VIDEO_DOMIAN.'/static/js/device.min.js"></script>'.$load_js;
	$code['auto'] = $code['auto'] . $auto_js ;
	$code['javascript'] = $code['javascript']  . $auto_js;
	$code['url'] .= '&domain='.CLOUD_VIDEO_DOMIAN;
	return $code;	
}
function encrypt($txt, $key = '')
{
    include_once(CUR_CONF_PATH . 'lib/XDeode.php');
    $authcode = new XDeode(8, $key);
    return $authcode->encode($txt);
}
function parseQueryString($str) { 
    $op = array(); 
    $pairs = explode("&", $str); 
    foreach ($pairs as $pair) { 
        list($k, $v) = array_map("rawurldecode", explode("=", $pair)); 
        $op[$k] = $v; 
    } 
    return $op; 
}
function log2file($user, $level, $message, $input, $output=array())
{
	if(!LOG_LEVEL)
	{
		return;
	}
	if(LOG_FOR_USER != 'ALL' && $user['user_name'] != LOG_FOR_USER)
	{
		return;
	}
	$level = strtoupper($level);
	$log_level = array('ERROR'=>1, 'DEBUG'=>2, 'ALL'=>3);
	if($log_level[$level] > LOG_LEVEL)
	{
		return;
	}
	$log_path = CUR_CONF_PATH . 'data/log/' . date('Y') . '/' . date('m') . '/';
	if(!is_dir($log_path))
	{
		hg_mkdir($log_path);
	}
	
	$input = json_encode($input);
	$output = json_encode($output);
	$time = date('Y-m-d H:i');
	$user = @json_encode($user);
	$log_message_tpl = <<<LC
Level   : {$level}
Message : {$message}
Input   : {$input}
Ouput   : {$output}
Date	: {$time}
User	: {$user}\n\n
LC;
	hg_file_write($log_path . 'log-'.date('Y-m-d').'.php', $log_message_tpl, 'a+');
}
function analytic_statistics($field='', $user_id=0)
{
	global $gDB;
	if(!$field || !$user_id)
	{
		return;
	}
	if(!in_array($field, array('upload','delete','callback', 'audit')))
	{
		return;
	}
	$sql = 'UPDATE ' . DB_PREFIX . 'analytic_statistics SET `' . $field . '`=`'.$field.'`+1 WHERE user_id='.$user_id;
	$gDB->query($sql);
}
?>