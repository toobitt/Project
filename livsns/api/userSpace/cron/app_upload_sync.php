<?php
require('./global.php');
define('MOD_UNIQUEID','app_sync');//模块标识
class app_sync extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '上传数据异步回调',	 
			'brief' => '上传数据异步回调',
			'space' => 2,	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//删除重试次数<=0
		$this->db->query('DELETE FROM ' . DB_PREFIX . 'app_upload_queue WHERE times<=0');
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_upload_queue ';
		$orderby = ' ORDER BY times DESC, update_time ASC';
		$limit = ' limit 0, 1';
		$queue = $this->db->query_first($sql . $orderby . $limit);
		
		$appinfo = $vodinfo = array();
		if($queue)
		{
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$curl->initPostData();
			$curl->setSubmitType('post');
			$formdata = array(
			'search_field'=>'client_id',
			'flag'=>'application',
			'client_id'=>$queue['client_id'],
			'a'=>'get_specify_settings',
			);
			foreach ($formdata as $key=>$val)
			{
				$curl->addRequestData($key, $val);
			}
			$appinfo =  $curl->request('preferences.php');
			if(!empty($appinfo) && is_array($appinfo[0]))
			{
				$appinfo = $appinfo[0];
				$sql = 'UPDATE ' . DB_PREFIX . 'app_upload_queue SET update_time = '.TIMENOW.' WHERE id = ' . $queue['id'];
			}
			else 
			{
				$sql = 'DELETE FROM ' . DB_PREFIX . 'app_upload_queue  WHERE id = ' . $queue['id'];
			}
			$this->db->query($sql);
			$vodinfo = json_decode($queue['data'],1);
		}
		if($appinfo && $vodinfo)
		{
			$url = $appinfo['admin_settings']['callback_url'];
			$re = $this->curl_post($url,$vodinfo);
			if($re === false)
			{
				//计数失败次数
				$sql = 'UPDATE ' . DB_PREFIX . 'app_upload_queue SET times = times-1 WHERE id = ' . $queue['id'];
			}
			else
			{
				//删除队列数据
				$sql = 'DELETE FROM ' . DB_PREFIX . 'app_upload_queue WHERE id = '.$queue['id'];
		
			}
			$this->db->query($sql);
		}
		
	}
	protected function curl_post($url, $postdatas = array())
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdatas));
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $responce = json_decode(curl_exec($ch),true);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			return false;
		}
		else
		{
	    	return true;
		}
	    curl_close($ch);
	}
}


$out = new app_sync();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>