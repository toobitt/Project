<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php
***************************************************************************/

/**
 * 去掉重复appid
 * @param sting $select_appid 查询出的app_id
 * @param int $appid
 * @return array $app_ids
 */
function make_appid_unique($select_appid,$appid)
{
	$app_ids = explode(',', $select_appid);
	$app_ids[] = $appid;
	$app_ids = array_unique($app_ids);
	$app_ids = implode(',', $app_ids);
	return $app_ids;
}

/**
 *删除文件和文件夹
 * @param string $dir   目录路径
 */
function deldir($dir) 
{
	//先删除目录下的文件：
  	$dh=opendir($dir);
  
  	while ($file=readdir($dh)) 
  	{
    	if($file!="." && $file!="..") 
    	{
      		$fullpath=$dir."/".$file;
      		if(!is_dir($fullpath)) 
      		{
          		unlink($fullpath);
      		} 
      		else 
      		{
        		deldir($fullpath);
      		}
		}
  	}
  	closedir($dh);
  	//删除当前文件夹：
  	if(rmdir($dir)) 
  	{
    	return true;
  	}
  	else
  	{
    	return false;
  	}
}
/**
 *判断目录是否为空
 * @param string $path   目录路径
 */
function isEmptyDir($path) 
{
	$dh= opendir( $path ); 
	while(false !== ($f = readdir($dh))) 
	{ 
		if($f != "." && $f != ".." )
		{
			return false; 
		}
	}
	return true; 
}
/**
 *api生成文件 
 * @param array $setting  api配置
 * @param string $tpl_str 生成文件模板
 * 
 */
function mobile_build_file($setting,$tpl_str)
{
	$curl_settings = $setting;
		
	$class_name= explode('.', $setting['request_file']);
	$class_name = 'hg_'.$class_name[0];
	
	$ret_code 	= html_entity_decode($setting['ret_code'],ENT_QUOTES,'UTF-8');
	$param_code	= html_entity_decode($setting['param_code'],ENT_QUOTES,'UTF-8');
	
	unset($curl_settings['map'],$curl_settings['argument'],$curl_settings['ret_code'],$curl_settings['param_code'],$curl_settings['brief']);
	$curl_settings = serialize($curl_settings);
	
	if($setting['extend_api'] && $setting['extend_api_switch'])
	{
		$setting['request_file'] = '';
		$extend_api_arr = unserialize($setting['extend_api']);
		foreach ($extend_api_arr as $k => $v)
		{
			$setting['request_file'] .= $k.$v;
		}
	}
	
	$replace_value = array(
		$setting['request_file'],
		$class_name,
		$setting['argument'],
		$setting['map'],
		$setting['map_val'],
		$setting['extend_api'],
		$curl_settings,
		$ret_code,
		$param_code,
	);
	
	$handler = array(
		'{$file_name}',
		'{$class_name}',
		'{$args}',
		'{$maps}',
		'{$map_val}',
		'{$extend_api}',
		'{$settings}',
		'{$ret_code}',
		'{$param_code}'
	);
	
	$tpl_str = str_replace($handler, $replace_value, $tpl_str);
	
	if(!is_dir(DATA_DIR . $setting['sort_dir']))
	{
		hg_mkdir(DATA_DIR . $setting['sort_dir']);
	}
	@file_put_contents(DATA_DIR . $setting['sort_dir'] . $setting['file_name'], $tpl_str);
}

function outError($ErrorCode = '1', $ErrorText = '请求异常')
{
    $data = array(
        "ErrorCode" => $ErrorCode,
        "ErrorText" => $ErrorText?$ErrorText:'请求异常',
    );
    echo json_encode($data);
    exit;
}

function hg_mobile_client_stat($data)
{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://stat.cloud.hogesoft.com/mobile_client_stat.php');
			
		curl_setopt($ch,CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if($type == 'https')
		{
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		$data['customer_id'] = CUSTOM_APPID;
		$data['ip'] = hg_getip();
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$ret = curl_exec($ch);
		$ret = json_decode($ret, 1);
		$head_info = curl_getinfo($ch);
		curl_close($ch);
}

?>