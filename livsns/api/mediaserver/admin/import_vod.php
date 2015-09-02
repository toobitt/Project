<?php
set_time_limit(0);
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
class importVod extends BaseFrm
{
	private $curl;
	public function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_publishconfig']['host'], $this->settings['App_publishconfig']['dir']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function bak()
	{
		$sql = "update " . DB_PREFIX . "import_queue i left join " . DB_PREFIX . "vodinfo a on a.id=i.new_id set a.isfile=1 where 1 ";
		$this->db->query($sql);
	}

	public function show()
	{
	//s	c = $this->input['id'] ?  $this->input['id'] : '16672,16666,16665,16648,16647,16646,16639,16638,16637,16631';
	$id = 0;
		$vod_url = 'http://vapi.thmz.com/liv_mms/admin/vod.php?_type=1&id=' . $id;
		$vod_data = file_get_contents($vod_url);
		$ret = array();
		if($vod_data)
		{
			$channel_new = array(
				1 => '新闻综合频道',
				2 => '娱乐频道',
				3 => '都市资讯频道',
				4 => '生活频道',
				5 => '经济频道',
				6 => '移动电视',	
				7 => '新闻广播',
				8 => '综合广播',
				9 => '交通频率',
				10 => '故事戏曲广播',
				11 => '汽车音乐频率',
				12 => '江南之声频率',
				13 => '都市生活频率',
				14 => '经济频率',
				20 => '广播新年音乐会',			
			);
			$channel_relation = array(
				1 => 1,
				2 => 3,
				3 => 2,
				4 => 5,
				5 => 4,
				6 => 6,
				7 => 1,
				9 => 7,
				10 => 8,
				11 => 9,
				12 => 10,
				13 => 11,
				14 => 12,
				15 => 13,
				16 => 14,
				17 => 9,
			);
			$sort_new = array(
				'第一网谈' => 29,
				'法院新闻' => 30,
				'活动直播' => 20,
			);
			$vod_info = json_decode($vod_data,true);
			hg_pre($vod_info);exit;
			foreach($vod_info as $k => $v)
			{
				$tmp_duration = explode('’',$v['duration']);
				if(count($tmp_duration) == 2)
				{
					$v['duration'] = (intval($tmp_duration[0])*60+intval($tmp_duration[1]))*1000;
				}
				else
				{
					$v['duration'] = intval($tmp_duration[1])*1000;
				}
				
				$data = array(
					'title'    			=> $v['title'],
					'channel_id' 		=> $channel_relation[$v['channel_id']],
					'source'  	 		=> $channel_new[$channel_relation[$v['channel_id']]],
					'subtitle' 			=> $v['subtitle'],
					'keywords' 			=> $v['keywords'],
					'comment'  			=> $v['comment'],
					'author' 			=> $v['author'],
					'vcr_type'			=> 4,
					'type'				=> $v['type'],
					'duration'			=> $v['duration'],
					'height'			=> $v['height'],
					'start'				=> $v['start'],
					'width'				=> $v['width'],
					'totalsize'			=> $v['totalsize'],
					'vod_sort_id' 		=> $sort_new[$v['vod_sort_id']],
					'is_allow' 			=> $v['is_allow'],
					'column_id' 		=> 0,
					'from_appid'		=> 1,
					'from_appname'		=> 'MCP网页版',
					'user_id'			=> 1,
					'addperson'			=> 'hogesoft',
					'hostwork'			=> defined("TARGET_VIDEO_DOMAIN")?'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'],
					'video_path'		=> hg_num2dir($v['vodid']).$v['vodid'] . '.ssm/',
					'video_filename'	=> $v['vodid'] . '.mp4',
					'source_path'		=> '',
					'source_filename' 	=> '',
					'bitrate' 			=> $v['bitrate'],
					'vod_leixing'		=> 4,
					'create_time'		=> strtotime($v['create_time']),
					'update_time'		=> strtotime($v['update_time']),
					'ip'				=> $v['ip'],
					'pub_time'          => 0,
					'is_allow'			=> 0,
					'is_finish'			=> 0,
					'status'			=> 2,
					'technical_status'	=> 3,
					'audio'				=> $v['audio'],
					'audio_channels'	=> $v['audio_channels'],
					'sampling_rate'		=> $v['sampling_rate'],
					'video'				=> $v['video'],
					'frame_rate'		=> $v['frame_rate'],
					'aspect'			=> $v['aspect'],
				);
				$ret[] = $data;
			}
			hg_pre($ret);
		}
	}

	public function get()
	{
		$column_url = 'http://vapi.thmz.com/workbench/columns.php?fid=' . $this->input['fid'];
		$column_info = json_decode(file_get_contents($column_url),true);
		hg_pre($column_info);
	}	

	public function import()
	{
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$total = 0;
		if(file_exists('../cache/vod_total'))
		{
			$total = file_get_contents('../cache/vod_total');
		}
		$vod_offset_cache = array();
		if(file_exists('../cache/vod_offset_cache'))
		{
			$file_handle = fopen('../cache/vod_offset_cache', "r");
			while (!feof($file_handle)) {
			   $vod_tmp_offset = intval(fgets($file_handle));
				if(!$vod_tmp_offset)
				{
					continue;
				}
				$vod_offset_cache[] = $vod_tmp_offset;
			}
			fclose($file_handle);
			if(!empty($vod_offset_cache))
			{
				$offset = $vod_offset_cache[count($vod_offset_cache)-1];
			}
		}
		
		define('VIDEO_MARK_FILE','.ssm/manifest.f4m');
		$channel_url = 'http://vapi.thmz.com/liv_mms/admin/channel.php';
		$channel_info = json_decode(file_get_contents($channel_url),true);
		$channel_old = array();
		foreach($channel_info as $k => $v)
		{
			$channel_old[$v['id']] = $v['name'];
		}
		
		$channel_new = array(
			1 => '新闻综合频道',
			2 => '娱乐频道',
			3 => '都市资讯频道',
			4 => '生活频道',
			5 => '经济频道',
			6 => '移动电视',	
			7 => '新闻广播',
			8 => '综合广播',
			9 => '交通频率',
			10 => '故事戏曲广播',
			11 => '汽车音乐频率',
			12 => '江南之声频率',
			13 => '都市生活频率',
			14 => '经济频率',
			20 => '广播新年音乐会',			
		);
				
		$channel_relation = array(
			1 => 1,
			2 => 3,
			3 => 2,
			4 => 5,
			5 => 4,
			6 => 6,
			7 => 1,
			9 => 7,
			10 => 8,
			11 => 9,
			12 => 10,
			13 => 11,
			14 => 12,
			15 => 13,
			16 => 14,
			17 => 9,
		);

		/*
			1、频道对应关系
			2、类型对应关系
			3、根据视频id获许视频地址
			4、标注视频所发布的栏目抓取
			5、视频缩略图抓取
		*/
		
		$sort_new = array(
			'第一网谈' => 29,
			'庭审在线' => 30,
			'活动直播' => 20,
		);

		$cache = file_get_contents('../cache/column_cache');
		$cache_array = explode('--------------',$cache);
		$column_cache = array();
		foreach($cache_array as $k => $v)
		{
			if($v)
			{
				$tmp_cache = explode('**',$v);
				$column_cache[$tmp_cache[0]] = $tmp_cache[1];				
			}
		}
	//hg_pre($column_cache);exit;

		$vod_total_url = 'http://vapi.thmz.com/liv_mms/admin/vod.php?_type=1&a=count';
		$vod_total = json_decode(file_get_contents($vod_total_url),true);
		if(empty($offset))
		{
			$offset = $vod_total['total']-$count;
		}
		else
		{
			$offset = $offset-$count;
		}
		$extra_url = '?appid=' . $this->input['appid'] . '&appkey=' . $this->input['appkey'] . '&access_token=' . $this->input['access_token'];
		$limit = "&count=" . $count . '&offset=' . $offset;
		$to_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . $extra_url . $limit;
		
		$vod_url = 'http://vapi.thmz.com/liv_mms/admin/vod.php?_type=1' . $limit;
		$vod_data = file_get_contents($vod_url);

		if($vod_data)
		{
			$vod_info = json_decode($vod_data,true);
			$vod_id = $space = "";
			foreach($vod_info as $k => $v)
			{
				$vod_id .= $space . $v['id'];
				$space = ',';
				$total++;
			}
			//hg_pre($vod_info);
			file_put_contents('../cache/vod_total', $total);
			if($vod_id)
			{
				$sql = "SELECT * FROM ".DB_PREFIX."import_queue WHERE old_id IN(" . $vod_id . ")";
				$q = $this->db->query($sql);
				$vod_info_queue = array();
				while($row = $this->db->fetch_array($q))
				{
					$vod_info_queue[] = $row['old_id'];
				}
				$publish_vod_id = $space = '';
				$vod_info_publish = array();
				$sort_new = array_flip($sort_new);
				foreach($vod_info as $k => $v)
				{
					if(!in_array($v['id'],$vod_info_queue) && in_array($v['vod_sort_id'],$sort_new))
					{
						$v['img'] = $v['img'] ? str_replace('thumb/100X200/','',$v['img']) : '';
						$publish_vod_id .= $space . $v['id'];
						$space = ',';
						$vod_info_publish[] = $v;
					}
				}
				$sort_new = array_flip($sort_new);
				if($publish_vod_id)
				{
					$vod_info = array();
					$vod_info = $vod_info_publish;
					krsort($vod_info);
					$this->curl->setSubmitType('post');
					$this->curl->setReturnFormat('json');
					$this->curl->initPostData();
					$this->curl->addRequestData('a','show');
					$this->curl->addRequestData('site_id',2);
					$this->curl->addRequestData('count',200);
					$this->curl->addRequestData('access_token','e5069162db4e5b411971c48ac21f5829');
					$column_ret = $this->curl->request('column.php');
					$column_new = array();
					foreach($column_ret as $k => $v)
					{
						$column_new[$v['id']] = $v['name'];
					}
				//	hg_pre($column_new);exit;
				
					$publish_url = 'http://vapi.thmz.com/workbench/publish.php?id=' . $publish_vod_id;
					$publish_column = json_decode(file_get_contents($publish_url),true);
				//	hg_pre($publish_column);
				
					$publish_vod_id = array();
					if(!empty($publish_column))
					{
						foreach($publish_column as $k => $v)
						{
							$publish_vod_id[] = $k;
						}
					}
					$import_queue = array();
					$cache_show = array();

//hg_pre($vod_info);exit;


					foreach($vod_info as $k => $v)
					{
						if(in_array($v['id'],$publish_vod_id))
						{
							$tmp_duration = explode('’',$v['duration']);
							if(count($tmp_duration) == 2)
							{
								$v['duration'] = (intval($tmp_duration[0])*60+intval($tmp_duration[1]))*1000;
							}
							else
							{
								$v['duration'] = intval($tmp_duration[1])*1000;
							}
							
							$data = array(
								'title'    			=> $v['title'],
								'channel_id' 		=> $channel_relation[$v['channel_id']],
								'source'  	 		=> $channel_new[$channel_relation[$v['channel_id']]],
								'subtitle' 			=> $v['subtitle'],
								'keywords' 			=> $v['keywords'],
								'comment'  			=> $v['comment'],
								'author' 			=> $v['author'],
								'vcr_type'			=> 4,
								'type'				=> $v['type'],
								'duration'			=> $v['duration'],
								'height'			=> $v['height'],
								'isfile'			=> $v['isfile'],
								'start'				=> $v['start'],
								'width'				=> $v['width'],
								'totalsize'			=> $v['totalsize'],
								'vod_sort_id' 		=> $sort_new[$v['vod_sort_id']],
								'is_allow' 			=> $v['is_allow'],
								'column_id' 		=> 0,
								'from_appid'		=> 1,
								'from_appname'		=> 'MCP网页版',
								'user_id'			=> 1,
								'addperson'			=> 'hogesoft',
								'hostwork'			=> defined("TARGET_VIDEO_DOMAIN")?'http://' . ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['protocol'] . $this->settings['videouploads']['host'],
								'video_path'		=> hg_num2dir($v['vodid']).$v['vodid'] . '.ssm/',
								'video_filename'	=> $v['vodid'] . '.mp4',
								'source_path'		=> '',
								'source_filename' 	=> '',
								'bitrate' 			=> $v['bitrate'],
								'vod_leixing'		=> 4,
								'create_time'		=> strtotime($v['create_time']),
								'update_time'		=> strtotime($v['update_time']),
								'ip'				=> $v['ip'],
								'pub_time'          => 0,
								'is_allow'			=> 0,
								'is_finish'			=> 0,
								'status'			=> 2,
								'technical_status'	=> 3,
								'audio'				=> $v['audio'],
								'audio_channels'	=> $v['audio_channels'],
								'sampling_rate'		=> $v['sampling_rate'],
								'video'				=> $v['video'],
								'frame_rate'		=> $v['frame_rate'],
								'aspect'			=> $v['aspect'],
							);
							
							if($publish_column[$v['id']])
							{
								foreach($publish_column[$v['id']] as $kk => $vv)
								{
									$data['column_id'] = serialize(array($column_cache[$kk] => $column_new[$column_cache[$kk]]));
								}
							}
							$tmp_path = $data['video_path'];
							$data['video_path'] = 'former/' . $data['video_path'];
							
							$sql = " INSERT INTO ".DB_PREFIX."vodinfo SET ";
							foreach ($data AS $ks => $vs)
							{
								$sql .= " {$ks} = '{$vs}',";
							}
							$sql = trim($sql,',');
							$this->db->query($sql);
							$vid = $this->db->insert_id();
							$import_queue[$v['id']] = $vid;
							$pic_path = $v['img'];
							if($pic_path)
							{
								$img_info = $this->image2material($pic_path,$vid);
								$image_info = array(
									'host' => $img_info['host'],
									'dir' => $img_info['dir'],
									'filepath' => $img_info['filepath'],
									'filename' => $img_info['filename'],
								);
								$sql = " UPDATE " . DB_PREFIX . "vodinfo SET video_order_id = {$vid},img_info = '" . serialize($image_info) . "'  WHERE id = {$vid}";
								$this->db->query($sql);
							}
							$sql = "INSERT INTO " . DB_PREFIX . "import_queue(old_id,new_id) VALUES (" . $v['id'] . "," . $vid .  ")";
							$this->db->query($sql);
							
						//	$cache_show[] = $data;
							//复制物理文件	
							$vdir = '/iptvstorage/webroot/vod/' . $tmp_path;
							$idir = '/vstorage/vod/mp4/former/' . $tmp_path;
							if(hg_mkdir($idir))
							{
								@copy($vdir . $v['vodid'] . '.ism',$idir . $v['vodid'] . '.ism');
								@copy($vdir . $v['vodid'] . '.ismv',$idir . $v['vodid'] . '.ismv');
							}
						}
					}
					file_put_contents('../cache/vod_offset_cache', $offset . "\n",FILE_APPEND);						
				}
				else
				{
					file_put_contents('../cache/vod_offset_cache', $offset . "\n",FILE_APPEND);
				}
			}
			else
			{
				file_put_contents('../cache/vod_offset_cache', $offset . "\n",FILE_APPEND);
			}
		}
		else
		{
			file_put_contents('../cache/vod_offset_cache', $offset . "\n",FILE_APPEND);
			echo "总计" . $total . "条，完成导入";
		}
	}
	
	//将图片提交到图片服务器
	private function image2material($path,$cid)
	{
		$material = new material();
    	$img_info = $material->localMaterial($path,$cid);
    	return $img_info[0];
	}

	//1、先创建站点，保存栏目关系
	public function import_column($fid = 0,$new_fid = 0)
	{
		$column_url = 'http://vapi.thmz.com/workbench/columns.php?fid=' . $fid;
		$column_info = json_decode(file_get_contents($column_url),true);

		$fid = $space = '';
		$data = array();
		foreach($column_info as $k => $v)
		{
			$data = array(
				'column_name' => $v['name'],
				'site_id' => 2,
				'column_fid' => $new_fid,
				'fast_add_column' => 1,
			);
			if($v['is_last'])//1
			{
				$tmp = array();
				$tmp = $this->_import_column($data);
				file_put_contents('../cache/column_cache',$v['id'] . '**' . $tmp . '--------------',FILE_APPEND);
				$this->import_column($v['id'],$tmp);
			}
			else
			{
				$tmp = array();
				$tmp = $this->_import_column($data);
				file_put_contents('../cache/column_cache',$v['id'] . '**' . $tmp . '--------------',FILE_APPEND);
			}
		}
	}
	
	private function _import_column($data = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','operate');
		$this->curl->addRequestData('access_token','74d307de65d448824f47dabad0757dfa');//http://vapi.wifiwx.com/mediaserver/admin/
		foreach($data as $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('admin/column.php');
		return $ret[0];
	}
}

$action = $_INPUT['a'];
$out = new importVod();
if(!method_exists($out, $action))
{
	$action = 'import';
}
$out->$action();
?>