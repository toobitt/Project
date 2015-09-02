<?php
/*
 * 剧集上传callback
 * 
 */
require('global.php');
define('MOD_UNIQUEID','tv_play');//模块标识
require_once(CUR_CONF_PATH . 'lib/tv_play_mode.php');
class tv_episode_upload_callback extends adminBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new TVPlayMode();
	}
	
	public function callBack()
	{
		$video_info = json_decode(html_entity_decode($this->input['data']),1);
		if(!$video_info['id'])
		{
			$this->errorOutput(NO_VIDEO_ID);//没有视频id
		}
		
		if(!$video_info['callback_data'] || !$video_info['callback_data']['tv_play_id'])
		{
			$this->errorOutput(NO_TV_PLAY_ID);//没有电视剧id
		}
		
		$video_id = $video_info['id'];
		$tv_play_id = $video_info['callback_data']['tv_play_id'];
		$img_info = $video_info['img'];
		$title = $video_info['callback_data']['title'];
		$index_num = $video_info['callback_data']['index_num'];
		
		
		//转码成功回调
		if($video_info['callback_data']['after_callback'])
		{
			//更新剧集转码状态
			$sql = "UPDATE " . DB_PREFIX . "tv_episode SET transcode_status = 1 WHERE tv_play_id = " . $tv_play_id . " AND video_id = " . $video_id;
			$this->db->query($sql);
			
			//查询电视剧的信息
			$tv_play_info = $this->mode->get_tv_play_info($tv_play_id);
			$tv_play_info = $tv_play_info[0];
				
			//设置了逐集发布
			if($tv_play_info && $tv_play_info['publish_auto'])
			{
				
				$column_id_arr = array();
				if($tv_play_info['column_id'])
				{
					$column_id_arr = array_keys(unserialize($tv_play_info['column_id']));
				}
				//审核
				if(intval($tv_play_info['status']) == 2 && !empty($column_id_arr))
				{
					if(!empty($tv_play_info['expand_id']))
					{
						$op = "update";	
                        
						publish_insert_query($tv_play_info, $op, $column_id_arr);
						
						$this->insertQueueToLivmediaByVideoID($tv_play_id,$video_id, 'insert',$column_id_arr,$tv_play_info['column_id'],$tv_play_info['pub_time']);
					}
				}
			}
			return true;
		}
		
		
		$data = array(
			'tv_play_id' 	=> $tv_play_id,
			'video_id' 		=> $video_id,
			'title' 		=> $title,
			'index_num' 	=> $index_num,
			'img'			=> serialize($img_info),
			'user_name'		=> $this->user['user_name'],
			'user_id'		=> $this->user['user_id'],
			'org_id'		=> $this->user['org_id'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
		);
		
		//开始绑定剧集
		$ret = $this->mode->createEpisode($data);
		if($ret)
		{
			$data['id'] = $ret['id'];
			$this->addLogs('新增剧集', '', $data,'新增剧集:' . $ret['id']);
			//返回的数据
			$data['img_index'] = hg_fetchimgurl($img_info);
			$data['title'] = $ret['title'];
			$data['index_num'] = $ret['index_num'];
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function insertQueueToLivmediaByVideoID($tv_play_id,$video_id,$op,$column_id,$now_column,$pub_time)
	{
		/*$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$tv_play['id']. "' AND expand_id = 0 ";
		$q = $this->db->query($sql);
		$video_id_arr = array();
		while ($r = $this->db->fetch_array($q))
		{
			$video_id_arr[] = $r['video_id'];
		}*/
		
		if($video_id && $tv_play_id)
		{
			//查询剧集信息
			$sql = "SELECT * FROM " . DB_PREFIX . "tv_episode WHERE tv_play_id = {$tv_play_id} AND video_id = {$video_id}";
			$ep_info = $this->db->query_first($sql);
			
			publish_insert_query($ep_info, 'insert',$column_id,1);
			
			include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
			$livmedia = new livmedia();
			$livmedia->insertQueueToLivmedia($video_id,$op,implode(',',$column_id),$now_column,$pub_time);
		}
	}
	protected function verifyToken(){}
}

$out = new tv_episode_upload_callback();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'callBack';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>