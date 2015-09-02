<?php
/***************************************************************************
* $Id: schedule_auto.php 21634 2013-05-07 02:43:19Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','rebuile_fileurl');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");

class rebuile_fileurl extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '重建文件流',	 
			'brief' => '',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function show()
	{		
		$server_info = $this->settings['server_info'];
		$host 		 = $server_info['host'];
		$input_dir 	 = $server_info['input_dir'];
		include_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$this->mLivMedia = new livmedia();
		$sql = 'SELECT change2_id, id, change2_name, dates, type, file_id, url FROM ' . DB_PREFIX . 'schedule WHERE url IN ("", "/filestream/playlist.m3u8")  AND start_time>' . time() . ' ORDER BY start_time DESC limit 10';
		$q = $this->db->query($sql);
		$vod_id = array();
		$schecule = array();
		while($r = $this->db->fetch_array($q))
		{
			$vod_id[$r['id']] = $r['change2_id'];
			$schecule[$r['id']] = $r;
		}
		
		if (!$vod_id)
		{
			$sql = 'SELECT change2_id, id, change2_name, dates, type, file_id, url FROM ' . DB_PREFIX . 'schedule WHERE url !="" AND type=2 AND start_time>' . time() . ' AND file_id=0 ORDER BY start_time ASC limit 10';
			$q = $this->db->query($sql);

			while($r = $this->db->fetch_array($q))
			{
				$url = $r['url'];
				//创建文件流
				$callback = $this->settings['App_schedule']['protocol'] . $this->settings['App_schedule']['host'] . '/' . $this->settings['App_schedule']['dir'] . 'admin/callback.php?a=backup_callback&id=' . $r['id'] . '&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);
				$file_data = array(
					'action'	=> 'insert',
					'url'		=> $url,
					'callback'	=> urlencode($callback),
				);
				
				$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
				print_r($ret_file);
				if ($ret_file['result'] && $ret_file['file']['id'])
				{
					$file_id = $ret_file['file']['id'];
				}
				if ($file_id)
				{
					$sql = 'UPDATE ' . DB_PREFIX . 'schedule SET  file_id="' . $file_id . '" WHERE id=' . $r['id'];
					$this->db->query($sql);
				}
			}
			return;
		}
		
		$vod_ids = implode(',', $vod_id);
		/*
		$this->mMediaserver = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		
		$this->mMediaserver->setReturnFormat('str');
		$this->mMediaserver->setSubmitType('post');
		$this->mMediaserver->initPostData();
		$this->mMediaserver->addRequestData('a','vod2livets');
		*/
		$vod_info = $this->mLivMedia->getVodInfoById($vod_ids);
		$vod = array();
		if (!empty($vod_info))
		{
			foreach ($vod_info AS $v)
			{
				$vod[$v['id']] = $v;
			}
		}
		foreach ($vod_id AS $scid => $id)
		{
			echo $schecule[$scid]['change2_name'] . '(' . $schecule[$scid]['dates'] . ') --- ';
			$file_id = $schecule[$scid]['file_id'];
			if ($vod[$id]['video_filename'])
			{
				/*
				$this->mMediaserver->addRequestData('path',$vod[$id]['video_path']);
				$this->mMediaserver->addRequestData('targetpath',$vod[$id]['video_path']);
				$this->mMediaserver->addRequestData('filename', str_replace('.mp4', '', $vod[$id]['video_filename']));
				$this->mMediaserver->request('split_ts.php');
				
				$url 	 	= $vod[$id]['hostwork'] . '/filestream/' . $vod[$id]['video_path'] . 'playlist.m3u8';
				*/
				if ($schecule[$scid]['type'] == 2)
				{
					$url = $vod[$id]['hostwork'] . '/' . $vod[$id]['video_path'] . $vod[$id]['video_filename'];
					//创建文件流
					if (!$file_id)
					{
						$callback = $this->settings['App_schedule']['protocol'] . $this->settings['App_schedule']['host'] . '/' . $this->settings['App_schedule']['dir'] . 'admin/callback.php?a=backup_callback&id=' . $scid . '&appid=' . intval($this->input['appid']) . '&appkey=' . trim($this->input['appkey']);
						
						if (!$schecule[$scid]['file_id'])
						{
							$file_data = array(
								'action'	=> 'insert',
								'url'		=> $url,
								'callback'	=> urlencode($callback),
							);
							
							$ret_file = $this->mLivemms->inputFileOperate($host, $input_dir, $file_data);
							print_r($ret_file);
							if ($ret_file['result'] && $ret_file['file']['id'])
							{
								$file_id = $ret_file['file']['id'];
							}
						}
					}
				}
				echo $url . '<br />';
				$sql = 'UPDATE ' . DB_PREFIX . 'schedule SET url="' . $url . '", file_id="' . $file_id . '" WHERE id=' . $scid;
				$this->db->query($sql);
			}
		}
	}
}


$out = new rebuile_fileurl();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>