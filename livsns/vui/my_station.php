<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_station.php 4412 2011-08-16 08:17:01Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_station');
class myStation extends uiBaseFrm
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
		$this->load_lang('my_station');
		$id = $this->user['id']?$this->user['id']:0;
		if(!$id)
		{
			$this->check_login();
		}
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

		
		$stationInfo = $this->mVideo->get_user_station();
		$this->page_title = $this->lang['pageTitle'];
		$this->settings['nav_menu'][3] = array("name" => "频道设置", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'my_station.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'my.js');
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('stationInfo', $stationInfo);	
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_station');
	}
	
	public function changeProgram()
	{
		$web_station_name = $this->input['web_station_name']?$this->input['web_station_name']:'';
		$brief = $this->input['brief']?$this->input['brief']:'';
		$tags = $this->input['tags']?$this->input['tags']:'';
		$logo = $this->input['logo']?$this->input['logo']:'';
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$logo_o = $this->input['logo_o']?$this->input['logo_o']:"";
		$ret = $this->mVideo->create_station($web_station_name,$tags,$brief,$logo,$sta_id,$logo_o);
		
		/**
		 * 添加创建网台积分
		 */
		$this->mVideo->add_credit_log(CREATE_STATION);
				
		$c = ob_get_contents();
		ob_end_clean();
		echo json_encode($ret);
		exit;
	}
	
	public function uploadpic(){
		$logo = $this->input['logo_o']?$this->input['logo_o']:'';
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$mInfo = $this->mVideo->logo($_FILES,$logo,$sta_id);
		echo '<script>parent.endUploads("' . addslashes(json_encode($mInfo)) . '")</script>';
	}
	
}

$out = new myStation();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>