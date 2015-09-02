<?php
require('./global.php');
define('MOD_UNIQUEID','distr');//模块标识
class distr extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '数据分发',	 
			'brief' => '数据分发',
			'space' => '3',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//删除重试次数<=0
		$this->db->query('DELETE FROM ' . DB_PREFIX . 'distr_app_queue WHERE times<=0');
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'distr_app_queue ';
		$orderby = ' ORDER BY level DESC, update_time ASC';
		$limit = ' limit 0, 3';
		$query = $this->db->query($sql . $orderby . $limit);
		$queue = $appid = $vodid = $user_id = array();
		while($row = $this->db->fetch_array($query))
		{
			$queue[] = $row;
			$appid[] = $row['app_id'];
			$vodid[] = $row['vid'];
		}
		$appinfo = array();
		if($appid)
		{
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$curl->initPostData();
			$curl->setSubmitType('post');
			$curl->addRequestData('id', implode(',', array_unique($appid)));
			$appinfo =  $curl->request('preferences.php');
		}
		$vodinfo = array();
		if($vodid)
		{
			$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
			$curl->initPostData();
			$curl->setSubmitType('post');
			$curl->addRequestData('id', implode(',', $vodid));
			$vodinfo = $curl->request('vod.php');
		}
		if($queue && $appinfo)
		{
			foreach($queue as $val)
			{
				$_app = $_vod = array();
				foreach($appinfo as $x)
				{
					if($val['app_id'] == $x['id'])
					{
						$_app = $x;
						break;
					}
				}
				foreach($vodinfo as $y)
				{
					if($val['vid'] == $y['id'])
					{
						$_vod = $y;
						break;
					}
				}
				if($_app && $_vod)
				{
					$postdata = array(
					'title' => $_vod['title'],
					'subtitle'=> $_vod['subtitle'],
					'chain_m3u8'=>$_vod['video_m3u8'],
					'keywords'=>$_vod['keywords'],
					'index_pic'=>$_vod['img_info']['host'].$_vod['img_info']['dir'].$_vod['img_info']['filepath'].$_vod['img_info']['filename'],
					'comment'=>$_vod['comment'],
					'author'=>$_vod['addperson'],
					'vod_sort_id'=>$_vod['vod_sort_id'],
					'duration'=>$_vod['duration'],
					'bitrate'=>$_vod['bitrate'],
					'id'=>$_vod['id'],
					);
					$url = $_app['admin_settings']['callback_url'];
					$re = $this->curl_post($url,$postdata);
					if($re === false)
					{
						//计数失败次数
						$sql = 'UPDATE ' . DB_PREFIX . 'distr_app_queue SET times = times-1, update_time='.TIMENOW.' WHERE id = ' . $val['id'];
					}
					else
					{
						//删除队列数据
						$sql = 'DELETE FROM ' . DB_PREFIX . 'distr_app_queue WHERE id = '.$val['id'];
						
					}
					$this->db->query($sql);
				}
			}
		}
		
	}
	protected function curl_post($url, $postdatas = array())
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $responce = json_decode(curl_exec($ch),true);
		$head_info = curl_getinfo($ch);
		if($head_info['http_code']!= 200)
		{
			return false;
		}
		else
		{
	    	return $responce;
		}
	    curl_close($ch);
	}
}


$out = new distr();
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