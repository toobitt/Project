<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user_album_video.php 6050 2012-03-09 01:16:59Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_station');
class userAlbumVideo extends uiBaseFrm
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
		$this->load_lang('user_album');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$user_id = $this->input['user_id']?$this->input['user_id']:($this->user['id']?$this->user['id']:0);
		$id = $this->input['id']?$this->input['id']:0;
		
		/**
		 * 获取用户的专辑
		 */
		
		$is_my_page = false;
		if(empty($user_id))
		{
			$this->check_login();
		}
		
		if(empty($id))
		{
			header("Location:" . SNS_VIDEO);
		}		
		
		$this->tpl->addVar('user_id', $user_id);	
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('is_my_page', $is_my_page);	
		
		
		$count = 20;
		$page = intval($this->input['pp']) / $count;
		$album_video = $this->mVideo->get_album_video($id,$page,$count);
		if(is_array($album_video['video']))
		{
			$total_nums = $album_video['video']['total'];
			unset($album_video['video']['total']);
			$data['totalpages'] = $total_nums;
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = $this->input['user_id']?hg_build_link('' , array('id' => $this->input['id'],'user_id' => $this->input['user_id'])):"";
			$showpages = hg_build_pagelinks($data);
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
		
//		hg_pre($album_video);
		
		
		$this->page_title = $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('user_id', $user_id);	
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('is_my_page', $is_my_page);	
		$this->tpl->addVar('album_video', $album_video);	
		$this->tpl->addVar('total_nums', $total_nums);
		$this->tpl->addVar('showpages', $showpages);	
		$this->tpl->addVar('user_info', $user_info);	
		$this->tpl->addVar('hot_station', $hot_station);	
		$this->tpl->addVar('station', $station);	
		$this->tpl->addVar('hot_video', $hot_video);	
		$this->tpl->addVar('sta_id', $sta_id);	
		$this->tpl->addVar('album_info', $album_info);	
		$this->tpl->addVar('album_total', $album_total);	
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->lang['pageTitle']);
		$this->tpl->outTemplate('user_album_video');
	}
}

$out = new userAlbumVideo();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>