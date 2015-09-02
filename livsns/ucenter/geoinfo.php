<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: geoinfo.php 4216 2011-07-27 09:43:47Z zhoujiafei $
***************************************************************************/
define('ROOT_DIR','../');
define('SCRIPTNAME', 'geoinfo');
require('./global.php');
class geoInfo extends uiBaseFrm
{
	var $mUser,$mGroup;
	function __construct()
	{
		
		parent::__construct();
		
		$this->check_login(); 	
		include_once(ROOT_PATH . 'lib/user/user.class.php');	
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$this->mGroup = new Group();
		$this->mUser = new user();  
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{ 
		 $location = array(); 
		 $location = $this->mUser->getUserByName($this->user['username']);
		 $location = $location[0];
		 if(!empty($location))
		 {
		 	$default_gid = $location['group_id'];
		 	$default_lat = $location['lat'];
		 	$default_lng = $location['lng'];
		 	$default_gname = $location['group_name'];
		 }
 
		 
		$center = ($default_lat && $default_lng) ? $default_lat . 'X' . $default_lng : MAP_CENTER_POINT;
		hg_add_head_element("js-c","var MAP_CENTER_POINT = '" . $center . "';"  . "\r\t\n" 
							. " window.onload = function(){initialize();}");  
		if(!MAP_USING_TYPE){
			hg_add_head_element("js","http://ditu.google.cn/maps?file=api&amp;v=2&amp;key=" . MAP_KEY . "&sensor=false");
			hg_add_head_element('js', RESOURCE_DIR . 'scripts/map/' .  'map.js');
			$html_body_attr = ' onreload="GUnload()" onunload="GUnload()" onload="initialize()" ';
		}else{
			hg_add_head_element("js","http://api.map.baidu.com/api?v=1.2&services=false");
			hg_add_head_element('js', RESOURCE_DIR . 'scripts/map/' .  'b_map.js');
			$html_body_attr = ' onload="initialize()" ';
		}
		
		$gScriptName = SCRIPTNAME;
		$this->page_title = '地理信息';
		$this->tpl->addVar('default_gname', $default_gname);
		$this->tpl->addVar('default_lng', $default_lng);
		$this->tpl->addVar('default_lat', $default_lat);
		$this->tpl->addVar('default_gid', $default_gid);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('geoinfo');
	}
	
	//拖拽地图或缩放后查询当前经纬度范围内的讨论区
	public function get_bounds()
	{
		$min_lat = $this->input['min_lat'];
		$min_lng = $this->input['min_lng'];
		$max_lat = $this->input['max_lat'];			 
		$max_lng = $this->input['max_lng'];
		
		$bounds = array();
		$bounds = $this->mGroup->get_bounds($min_lat,$min_lng,$max_lat,$max_lng);
		if($bounds)
		{
			$bounds = $bounds[0];
			echo json_encode($bounds);
		}
					 
	}
	public function save_data()
	{  
		 
		$location = $this->mUser->getUserByName($this->user['username']);
		$location = $location[0];
		$old_group_id = intval($location['group_id']);
		$group_id = intval($this->input['gid']);
		$rr = $this->mGroup->join_group($group_id,$old_group_id);//关注该讨论区
		 
		$gname = $this->input['gname'];
		$glat = $this->input['glat'];
		$glng = $this->input['glng'];
		$location = array();
		$location = $this->mUser->add_location($group_id,$gname,$glat,$glng); 
		if(!empty($location))
		{
			echo '保存成功！';
		} 
	}
}

$geoInfo = new geoInfo();
$action = $_INPUT['a'];
if (!method_exists($geoInfo,$action))
{
	$action = 'show';
}
$geoInfo->$action();