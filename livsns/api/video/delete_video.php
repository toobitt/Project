<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: delete_video.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class deleteVideosInfoApi extends adminBase
{
	private $debug = false;
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function delete()
	{
		$user_info = $this->mUser->verify_credentials();
		
		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$video_id = intval(trim($this->input['video_id']));
		
		if(!$video_id)
		{
			$this->errorOutput(UNKNOW);  //删除出错
		}
		
		//审核删除
		$sql = "SELECT id FROM " . DB_PREFIX . "video WHERE id = " . $video_id . " AND is_recommend = 1";
		
		$q = $this->db->query_first($sql);
		
		if($q) //该删除视频是推荐视频
		{
			$verify_id = $q['id'];
			//进入删除审核
			$sql = "UPDATE " . DB_PREFIX . "video SET is_show = 3 WHERE id = " . $verify_id;
			$this->db->query($sql);			
		}
		else
		{
			/**
			 * 添加删除视频积分(消耗)
			 */
			$this->mUser->add_credit_log(DELETE_VIDEO);

			$sql = "SELECT id FROM " . DB_PREFIX . "video WHERE id = " . $video_id;
			$f = $this->db->query_first($sql);
			
			//删除视频表中信息
			$sql = "DELETE FROM " . DB_PREFIX . "video WHERE id = " . $video_id;
			$this->db->query($sql);
			
			global $config;
			include_once(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
			$tvie_video_api=new TVie_video_api($config);
			$state = $tvie_video_api->delete_video($f['serve_id']); 			//

			/**
			 * 查询该用户的视频数目
			 */
			$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "video WHERE user_id = " . $user_info['id'];
			$r = $this->db->query_first($sql);
			
			$video_count = $r['nums'];  
			
			/**
			 * 用户扩展表中的数目更新
			 */
			$this->mUser->delete_video_count($video_count);
			
			//删除标签-视频关联表中的信息
			$sql = "SELECT * FROM " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$q = $this->db->query($sql);
			
			$t_id = $space = "";
			while($row = $this->db->fetch_array($q))
			{
				if($row['tag_id'])
				{
					$t_id .= $space . $row['tag_id'];
				}
				$space = ",";
			}
			
			$sql = "delete from " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$this->db->query($sql);
			if($t_id)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE id IN (" . $t_id . ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$tag_count = 0;
					if(($row['tag_count']-1)>0)
					{
						$tag_count = $row['tag_count'] - 1;
					}
					
					$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = $tag_count  WHERE id = '" . $row['id'] . "'";
					$this->db->query($sql);
				}
			}
			
			//如果该视频被收藏一并删除
			$sql = "SELECT * FROM " . DB_PREFIX . "collects WHERE cid = " . $video_id . " AND type = 0 AND user_id = " . $user_info['id'];			
			$r = $this->db->query_first($sql);
			
			if(count($r) > 0)
			{
				$sql = "DELETE FROM " . DB_PREFIX . "collects WHERE cid = " . $r['cid'] . " AND user_id = " . $user_info['id'] . " AND type = 0";
				$this->db->query($sql);
			}
							
			//如果这部视频有评论也将评论删除
			$sql = "SELECT * FROM " . DB_PREFIX . "comments WHERE cid = " . $video_id . " AND type = 0";
			$q = $this->db->query($sql);
					
			$nums = $this->db->num_rows($q);
				
			$commment_id = array();
			if($nums > 0)
			{			
				while($row = $this->db->fetch_array($q))
				{
					$comment_id[] = $row['id'];
				}
				
				$ids = implode(',', $comment_id);
				$sql = "DELETE FROM " . DB_PREFIX . "comments WHERE id IN (" . $ids . ") AND type = 0";
				
				$this->db->query($sql);			
			}
			
			//如果删除的视频在节目单中,更新节目单
			//$this->del_progam($video_id);
		} 					
	}
	
	public  function del_progam($video_id)
	{
		//如果删除的视频在节目单中,更新节目单
		$info = array(
			'video_id' => $video_id,
		);
		$sql = "SELECT count(*) as num,sta_id FROM ".DB_PREFIX."network_programme WHERE video_id = ".$info['video_id'];
		$n = $this->db->query_first($sql);
		$sql = "DELETE FROM ".DB_PREFIX."network_programme WHERE video_id = ".$info['video_id'];
		$query = $this->db->query($sql);
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = ".$n['sta_id'];
		
		$query = $this->db->query($sql);
		
		
		
		$gap = $this->input['gap']?$this->input['gap']:5;
		$starttime = 0;
		$sql_pre = "UPDATE ".DB_PREFIX."network_programme SET ";
		while ($array = $this->db->fetch_array($query))
		{
			if($array['id']==$info['id'])
			{
				unset($program[$array['id']]);
			}
			else 
			{
				$array['toff'] = $array['end_time']-$array['start_time'];
				$array['start_time'] = $starttime;
				$array['end_time'] = $starttime + $array['toff'];
				$starttime = $array['end_time'] + $gap;
				$sql =$sql_pre . "start_time=".$array['start_time'].",end_time=".$array['end_time']." WHERE id = ".$array['id'];
				$this->db->query($sql);
				$program[] = $array;
			}
		}
		
		
		
		if($n['num'])
		{
			$sql = "DELETE FROM ".DB_PREFIX."network_programme WHERE video_id = ".$info['video_id'];
			$query = $this->db->query($sql);
			$gap = $this->input['gap']?$this->input['gap']:5;
			$starttime = 0;
			$sql_pre = "UPDATE ".DB_PREFIX."network_programme SET ";
			
			while ($array = $this->db->fetch_array($query))
			{
				$array['toff'] = $array['end_time']-$array['start_time'];
				$array['start_time'] = $starttime;
				$array['end_time'] = $starttime + $array['toff'];
				$starttime = $array['end_time'] + $gap;
				$sql =$sql_pre . "start_time=".$array['start_time'].",end_time=".$array['end_time']." WHERE id = ".$array['id'];
				$this->db->query($sql);
			}
			return 1;
		}		
		return 0;
	}	
}


$out = new deleteVideosInfoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'delete';
}
$out->$action();


?>
