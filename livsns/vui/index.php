<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 4467 2011-09-09 03:24:10Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'index');
class index extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		if(!ALLOW_PROGRAME)
		{
			header("Location:" . SNS_VIDEO . "my_video.php");
		}
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		
		$this->mVideo = new video();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$is_my_page = false;
		$user_id = $this->user['id'];
		if(empty($user_id))
		{
			$this->check_login();
		}		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$info = new user();		
		$user_info = $info->getUserById($user_id,"all");
		$user_info = $user_info[0];
		$id = $user_info['id'];
		$hot_station = $this->mVideo->get_station(0,0,0,10);
		if(is_array($hot_station))
		{
			unset($hot_station[count($hot_station)-1]);
		}
		
		$station = $this->mVideo->get_station(0,$id);
		if(is_array($station))
		{
			$sta_id = $station[0]['id'];
		}
		$hot_video = $this->mVideo->get_video_info(0,0,6,'',2);
	
		$album_info = $this->mVideo->get_album_info($id,0,6);
		if(is_array($album_info))
		{
			$album_total = $album_info['total'];
			unset($album_info['total']);
		}
		
		$count = 20;
		$page = intval($this->input['pp']) / $count;
		$con_station = $this->mVideo->get_station_history($sta_id,$page,$count);
		if(is_array($con_station))
		{
			$total_nums = $con_station['total'];
			unset($con_station['total']);
			$data['totalpages'] = $total_nums;
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
//			$data['pagelink'] = $this->input['user_id']?hg_build_link('' , array('user_id' => $this->input['user_id'])):"";
			$showpages = hg_build_pagelinks($data);
		}
		
		$this->settings['nav_menu'][3] = array("name" => "频道首页", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('sta_id', $sta_id);
		$this->tpl->addVar('album_total', $album_total);
		$this->tpl->addVar('album_info', $album_info);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('con_station', $con_station);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->lang['pageTitle']);
		$this->tpl->outTemplate('show');		
	}
	
		
	/**
	* 添加收藏
	* @param $type(0视频、1网台、2用户、3专辑)
	*/
	public function create_collect()
	{
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
					$ret = $this->mVideo->create_station_concern($id, $uid);
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
	* 取消收藏
	*/
	public function del_collect()
	{
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
					$ret = $this->mVideo->del_station_concern($id);
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

$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>