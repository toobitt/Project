<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_favorites.php 4412 2011-08-16 08:17:01Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_favorites');
class myFavorites extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$this->load_lang('my_favorites');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$id = $this->user['id']?$this->user['id']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id)
		{
			$this->check_login();
		}
		
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
		
		
		$this->pagelink = hg_build_link('' , array('type' => $type));
		$count = 9;
		$page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $count;
		$stationInfo = $this->mVideo->get_user_collect($id,$type,$page,$count);
		if(is_array($stationInfo))
		{
			$data['totalpages'] = $stationInfo['total'];
			unset($stationInfo['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = $this->pagelink;
			$showpages = hg_build_pagelinks($data);
		}
		
		$list = array(
			array('name' => '视频收藏','url' => hg_build_link('my_favorites.php'),),
//			array('name' => '网台收藏','url' => hg_build_link('my_favorites.php', array('type' => 1)),),
//			array('name' => '用户收藏','url' => hg_build_link('my_favorites.php', array('type' => 2)),),
		);

		$album_info = $this->mVideo->get_album_info($id,$page,$count);
		$album_total = $album_info['total'];

		$this->page_title = $this->lang['pageTitle'];
		
		$this->settings['nav_menu'][3] = array("name" => "我的收藏", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('type', $type);
		$this->tpl->addVar('list', $list);
		$this->tpl->addVar('stationInfo', $stationInfo);
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
		$this->tpl->outTemplate('my_favorites');
	}
	
}

$out = new myFavorites();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>