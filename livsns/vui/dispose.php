<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: dispose.php 4043 2011-06-07 07:26:20Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
require(ROOT_PATH . 'lib/class/status.class.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');

class dispose extends uiBaseFrm
{	
	private $info;
	private $status;
	function __construct()
	{
		parent::__construct();
//		$this->load_lang('global');
		$this->status = new status();
		$this->info = new user();		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	* 发布，转发点滴的处理方法
	* 
	*/	
	public function update()
	{
		if($this->user['id'])
		{
			$text = $this->input['status']?$this->input['status']:"";
			$ret = $this->status->verifystatus();
			if($ret['total']&&$ret['text'] === $text&&!$ret['reply_status_id'])
			{
				echo json_encode($ret);
				exit;
			}
			$source = $this->input['source']?$this->input['source']:"";
			$id = $this->input['status_id']? $this->input['status_id']:0;
			$type = $this->input['type']?$this->input['type']:"";  
			$ret = $this->status->update($text,$source,$id,0,$type); 
			 
			echo json_encode($ret);
		}
		else
		{
			echo json_encode('false');
		}
		
		
	}
	
	//同步发布点滴信息到讨论区
	public function pub_to_group()
	{
		$group_ids = $this->input['group_ids'];
		$status_info = $this->status->show(intval($this->input['status_id']));
		$status_info = $status_info[0]; 
		$content = $status_info['text'];
		if(!empty($status_info['medias']))
		{
			$mediaInfo = $status_info['medias'];
			foreach($status_info['medias'] as $key => $mediaInfo)
			{
				$type = $mediaInfo['type']; 
				$content .= ($media_str = ($type > 0) ? '[flash]'. $mediaInfo['link'] . '[/flash]<br/>' : '<img src="'.$mediaInfo['ori'].'" /><br/>');
			}
				
		}
		 
		$title = mb_substr($status_info['text'],0,30,'utf-8');
		$group_ids = explode(',',$group_ids);
		$group_ids = array_filter($group_ids);
		include_once (ROOT_PATH . 'lib/class/groups.class.php');
		$groups = new Group();
		 
		foreach($group_ids as $gid)
		{
			$rr = $groups->add_new_thread($gid,$title,$content,0);
		}
		if(empty($rr))
		{
			echo json_encode('false');
		}
		else
		{
			echo json_encode('true');
		}
	}
	
	/**
	* 增加关注话题
	* 
	*/	
	public function addTopicFollow()
	{
		if(!$this->input['topic'])
		{
			echo json_encode('null');
		}
		else
		{
			$topic = trim($this->input['topic']);
			$topic_follow = $this->status->getTopicFollow();
			if(!count($topic_follow))
			{
				$info = $this->status->addTopicFollow($topic);
				echo json_encode($info);
			}
			else 
			{
				foreach($topic_follow as $value)
				{
					$topicTitle[] = $value['title']; 
				}
				if(in_array($topic,$topicTitle))
				{
					echo json_encode('false');
				}
				else
				{
					$info = $this->status->addTopicFollow($topic);
					echo json_encode($info);
				}
			}
		}
	}

	/**
	* 删除关注话题
	* 
	*/	
	public function delTopicFollow()
	{
		$topic = trim($this->input['topic']);
		$info = $this->status->delTopicFollow($topic);
		echo json_encode($info);
	}
	
	/**
	* 添加收藏
	* @param $type(0视频、1网台、2用户、3专辑)
	*/
	public function create_concern()
	{
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$id = $this->input['id']?$this->input['id']:0;
		$uid = $this->input['uid']?$this->input['uid']:0;
		$type = $this->input['type'];
		if(!$uid)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			include_once (ROOT_PATH . 'lib/favorites/favorites.class.php');
			$fa = new favorites();
			switch ($type)
			{
				case 0:
					if(!$id)
					{
						echo json_encode('');
						exit;
					}
					$video = $this->mVideo->get_single_video($id);
					$info = array(
						'title' => $video['title'],
						'cid' => $id,
						'type_id' => 1,
						'link' => "video_play.php?id=".$id,
						'schematic' => $video['schematic'],
					);
					$this->mVideo->video_favorite_count($id);
					$ret = $this->mVideo->create_collect($id, $type,$uid);
					$fa->create($info['title'], $info['cid'], $info['type_id'], $info['link'], $info['schematic'],$ret['id']);
					break;
				case 1:
					if(!$id)
					{
						echo json_encode($uid);
						exit;
					}
					$station = $this->mVideo->get_station($id);
					$station = $station[0];
					$info = array(
						'title' => $station['web_station_name'],
						'cid' => $id,
						'type_id' => 4,
						'link' => "user_station.php?user_id=".$station['user_id'],
						'schematic' => $station['small'],
					);
					$this->mVideo->station_favorite_count($id);
					$relation = $this->get_relation($this->user['id'] , $uid);
					$ret = $this->mVideo->create_station_concern($id, $uid);
					$ret['relation'] = $relation;
					break;
				case 2:
					$user = $this->mVideo->getUserById($id);
					$info = array(
						'title' => $user['username'],
						'cid' => $id,
						'type_id' => 5,
						'link' => "user.php?user_id=".$user['id'],
						'schematic' => $user['middle_avatar'],
					);
					$this->mVideo->user_favorite_count($id);
					break;
				case 3:
					if(!$id)
					{
						echo json_encode('');
						exit;
					}
					$album = $this->mVideo->get_album($id);
					$info = array(
						'title' => $album['username'],
						'cid' => $id,
						'type_id' => 6,
						'link' => "user_album.php?id=".$album['id']."&user_id=".$album['user_id'],
						'schematic' => $album['middle_avatar'],
					);
					$this->mVideo->album_favorite_count($id);
					$ret = $this->mVideo->create_collect($id, $type,$uid);
					$fa->create($info['title'], $info['cid'], $info['type_id'], $info['link'], $info['schematic'],$ret['id']);
					break;
				default:
					break;
			}
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	/**
	 * 获取两个用户的关系
	 */
	private function get_relation($user_id , $id)
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$add_obj = new friendShips();	
		$result = $add_obj->show($user_id , $id);
		return $result;		
	}
	
	/**
	* 取消收藏
	*/
	public function del_collect()
	{
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$id = $this->input['id']?$this->input['id']:0;
		$cid = $this->input['cid']?$this->input['cid']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id&&!$cid)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			include_once (ROOT_PATH . 'lib/favorites/favorites.class.php');
			$fa = new favorites();
			$fa->del($id);
			switch ($type)
			{
				case 0:
					$this->mVideo->video_favorite_count($cid,0);
					$ret = $this->mVideo->del_collect($id,$cid,$type);
					break;
				case 1:
					$this->mVideo->station_favorite_count($cid,0);
					$uid = $this->input['uid'];
					$ret = $this->mVideo->del_station_concern($id,$uid);
					$ret['relation'] = $this->get_relation($this->user['id'] , $uid);
					break;
				case 2:
					$this->mVideo->user_favorite_count($cid,0);
					break;
				case 3:
					$this->mVideo->album_favorite_count($cid,0);
					$ret = $this->mVideo->del_collect($id,$cid,$type);
					break;
				default:
					break;
			}
			
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
/**
	* 添加举报
	* 举报内容类型：1：帖子，2：视频，3：微博评论，4：相册，5：视频评论，6：相册评论，7：帖子回复
	*/
	
	public function add_report()
	{
		if(!$this->user['id'])
		{
			echo json_encode('login');
			exit;
		}
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		$cid = $this->input['cid'];
		$uid = $this->input['uid'];
		$url = $this->input['url'];
		$type = $this->input['type'];
		
		/*include_once(ROOT_PATH . 'lib/class/shorturl.class.php');
		$shorturl = new shorturl($url);
		$url = $shorturl->shorturl($url);*/
		$content = trim($this->input['content'])?trim($this->input['content']):'我对这条记录有异议，特向你报告';
		if($cid&&$uid)
		{
			$ret = $this->status->create_report($cid,$uid,$type,$url,$content);
			echo json_encode($ret);
		}
		else 
		{
			echo json_encode('');
		}
	}
	

}
$out = new dispose();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>