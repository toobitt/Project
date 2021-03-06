<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_comments.php 4412 2011-08-16 08:17:01Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_comments');
class myComments extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$this->load_lang('my_comments');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$user_id = $this->user['id']?$this->user['id']:0;
		$type = $this->input['type']?$this->input['type']:0;
		$state = $this->input['state']?$this->input['state']:0;
		
		if(!$user_id)
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
		$hot_video = $this->mVideo->get_video_info(0,0,6,'',2);
		
		$list = array(
			array('name' => '已收到的','url' => hg_build_link('my_comments.php'),),
			array('name' => '已发表的','url' => hg_build_link('my_comments.php', array('state' => 1)),),
//			array('name' => '已删除的','url' => hg_build_link('my_comments.php', array('state' => 2)),),
		);
		
		$menu = array(
			array('name' => '视频评论','url' => hg_build_link('my_comments.php',array('state' => $state)),),
			array('name' => '频道留言','url' => hg_build_link('my_comments.php',array('state' => $state,'type' => 1,)),),
//			array('name' => '用户留言','url' => hg_build_link('my_comments.php',array('state' => $state,'type' => 2,)),),
		);
		
		switch($type)
		{
			case 0:
				$video = $this->mVideo->get_video_info($user_id);
				$cid = "";
				$space = " ";
				if(count($video)>1)
				{
					foreach($video as $key=>$value)
					{
						$cid .= $space.$value['id'];
						$space = ",";
					}
				}	
				break;
			case 1:
				$station = $this->mVideo->get_station(0,$user_id);
				$cid = "";
				$space = " ";
				if(is_array($station))
				{
					foreach($station as $key=>$value)
					{
						$cid .= $space.$value['id'];
						$space = ",";
					}
				}
				break;
			case 2:
				$cid = $user_id;
				break;
			default:
				break;
		}
		
		$count = 8;
		$this->pagelink = hg_build_link('' , array('type' => $type,'state' => $state,));
		$page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $count;
		switch($state)
		{
			case 0://我收到的
					$stationInfo = $this->mVideo->get_comment_list($user_id,$cid,$type,1,$page,$count);
				break;
			case 1://我发的
					$stationInfo = $this->mVideo->get_user_comments($user_id,$type,1,$page,$count);
				break;
			case 2://已删除的
				$stationInfo = array();
				$stationInfo_1 = $this->mVideo->get_comment_list($user_id,$cid,$type,0,$page,$count);
				$stationInfo_2 = $this->mVideo->get_user_comments($user_id,$type,0,$page,$count);
				$total = $stationInfo_1['total']+$stationInfo_2['total'];
				unset($stationInfo_1['total'],$stationInfo_2['total']);
				$stationInfo =array_intersect($stationInfo_1,$stationInfo_2); 
				$stationInfo_1 = array_diff($stationInfo_1,$stationInfo);
				$stationInfo =array_merge($stationInfo_1,$stationInfo_2);
				$stationInfo['total'] = count($stationInfo);
//				arsort($stationInfo);
				break;
			default:
				break;	
		}
		if(is_array($stationInfo))
		{
			$data['totalpages'] = $stationInfo['total'];
			unset($stationInfo['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = $this->pagelink;
			$showpages = hg_build_pagelinks($data);
		}

		$album_info = $this->mVideo->get_album_info($id,$page,$count);
		$album_total = $album_info['total'];

		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'comment.js');
		$this->page_title = $this->lang['pageTitle'];
		$this->settings['nav_menu'][3] = array("name" => "我的评论", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('list', $list);
		$this->tpl->addVar('menu', $menu);
		$this->tpl->addVar('cid', $cid);
		$this->tpl->addVar('state', $state);
		$this->tpl->addVar('type', $type);
		
		$this->tpl->addVar('stationInfo', $stationInfo);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('album_total', $album_total);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_comments');
	}
	
}

$out = new myComments();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>