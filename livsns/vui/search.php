<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: search.php 4341 2011-08-05 05:46:36Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_station');
class search extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$this->load_lang('search');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$user_id = $this->input['user_id']?$this->input['user_id']:($this->user['id']?$this->user['id']:0);
		$count = 20;
		$page = intval($this->input['pp']) / $count;
		
		$name = $this->input['k'];
	/*	if (!$this->input['k'])
		{
				if(!preg_match("/^[".chr(0xa1)."-".chr(0xff)."a-za-z0-9_]+$/",$this->input['k'])) 
				{
					$this->input['k'] = iconv('GBK', 'UTF-8', $this->input['k']);
				}
				$name = $this->input['k'];
		}*/

		$video_info = $this->mVideo->video_search($name,$page,$count);
		if(is_array($video_info))
		{
			$data['totalpages'] = $video_info[count($video_info)-1];
			unset($video_info[count($video_info)-1]);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('k' => $name));
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
		$this->settings['nav_menu'][3] = array("name" => "视频检索", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		$this->tpl->addVar('album_info', $album_info);
		$this->tpl->addVar('station', $station);
		$this->tpl->addVar('album_total', $album_total);
		$this->tpl->addVar('name', $name);
		
		$this->tpl->addVar('video_info', $video_info);
		$this->tpl->addVar('showpages', $showpages);
		
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('search');
	}
}

$out = new search();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>