<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_video.php 4472 2011-09-09 08:40:59Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_video');

class myVideos extends uiBaseFrm
{
	private $mVideo;
	private $mUser;
	
	function __construct()
	{
		parent::__construct();

		if(!$this->user['id'])
		{
			$this->check_login();
		}
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
		
		
		$this->load_lang('my_video');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$user_id = $this->user['id'];
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
		$hot_video = $this->mVideo->get_video_info(0,0,6,'',2);
		
		/**
		 * 获取用户的所有视频信息
		 */
		$count = 15;
		$page = intval($this->input['pp']) / $count;
				
		$video_info = $this->mVideo->get_all_video_info($page , $count);
								
		if(is_array($video_info))
		{
			$total_nums = $video_info[count($video_info)-1];
			
			unset($video_info[count($video_info)-1]);
			
			$data['totalpages'] = $total_nums;
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$showpages = hg_build_pagelinks($data);
		}

		$album_info = $this->mVideo->get_album_info($id,$page,$count);
		$album_total = $album_info['total'];
								
		$this->page_title = $this->lang['pageTitle'];
		
		$this->settings['nav_menu'][3] = array("name" => "我的视频", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		
		$gScriptName = SCRIPTNAME;
		
		hg_add_head_element('js-c',"
			var PUBLISH_TO_MULTI_GROUPS = " . PUBLISH_TO_MULTI_GROUPS . ";
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'my.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'tvieplayer_new.js');
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		$this->tpl->addVar('video_info', $video_info);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('album_total', $album_total);
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_video');
	}
	
	/**
	 * 编辑视频信息
	 */
	public function update()
	{
		$video_title = trim($this->input['video_title']);
		$video_tag = trim($this->input['video_tag']);
		$video_copyright = intval($this->input['video_copyright']);
		$video_sort = intval($this->input['video_sort']);
		$video_brief = $this->input['video_brief'];
		$video_id = intval($this->input['video_id']);
		
		//更新信息
		$update_info = array(		
			'video_id'  => $video_id ,
			'video_title' => $video_title ,
			'video_tag' => $video_tag ,
			'video_copyright' => $video_copyright ,
			'video_sort' => $video_sort , 
			'video_brief' => $video_brief 		
		);
		
		$info = serialize($update_info);
		$this->mVideo->update_video_info($info);		
	}
	
	
	/**
	 * 更新视频图片
	 */
	public function update_schematic()
	{
		$video_id = intval($this->input['video_id']?$this->input['video_id']:0);
		$schematic = $this->input['schematic']?$this->input['schematic']:'';
		$mInfo = $this->mVideo->update_video_image($_FILES, $video_id,$schematic);	
		
		echo '<script>parent.end_edit_image("' . addslashes(json_encode($mInfo)) . '")</script>';
	}

	
	/**
	 * 删除视频信息
	 */
	public function delete()
	{
		$video_id = intval(trim($this->input['id']));
		$this->mVideo->delete_video($video_id);
	}	
	
	public function add_threads()
	{
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$group = new Group();	
		$title =  $this->input['title']? $this->input['title']:"";
		$link = $this->input['link']? '《<a target="_blank" href="'.$this->input['link'].'">'.$title.'</a>》':"";
		$group_id = $this->input['group_id']? $this->input['group_id']:"";
		$title = $this->user['user_name']." 分享视频 《".$title."》";
		$content = "点击进入观看视频 ".$link;
		$ret = $group->add_new_thread($group_id, $title, $content);
		echo json_encode($ret[0]);
		exit;
	}
	
	public function video_threads()
	{
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		$ret = $this->mVideo->video_threads($video_id);
		echo json_encode($ret);
		exit;
	}
}

$out = new myVideos();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>