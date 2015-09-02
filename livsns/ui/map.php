<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: map.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');

class map extends uiBaseFrm
{	 
	function __construct()
	{		
		parent::__construct();
		$this->check_login();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->load_lang("map");	
	}

	function __destruct()
	{
		parent::__destruct();
	} 

	public function show()
	{
	
		$map_flag = 1;

		
		$info = new user();
		$member_id = $this->user['id'];
		$user_location = $info->getUserById($member_id);//获取用户的所在地  
		
		$user_location = $user_location[0]['location'];
		$js_c = '';
		 
		//如果用户的所在地为空，就判断用户标注过的位置数组是否为空
		$location_array = $info->get_location($member_id);//获取用户标注过的地点数组
		
		$location_array = $location_array[0];//等处理好后此句删掉
		
		if($location_array)
		{
			//如果用户标注过的位置不为空，就取所有标注过的位置的中心位置为地图的中心点
			$point_array = array();
			$map_data_js = 'var markers = [';
			$split = '';
			foreach ($location_array as $user_id => $locations)
			{
				foreach($locations as $marked_location)
				{
					$tmp = explode(";",$marked_location);
					$point_array[] = $tmp[1];
					$tmp_latlng = explode('X',$tmp[1]);
					$map_data_js .=  $split . '{"name":"' . $tmp[0] . '","latitude":' . $tmp_latlng[0] .',"longitude":' . $tmp_latlng[1] .'}' ."\r\n"; 
					$split = ',';
					
				}
				
			}
			$map_data_js .= '];'; 
			$cnt = count($point_array);
			
			$xx = $yy = 0.0;
			if(!$user_location)
			{
				foreach($point_array as $key => $value)
				{
					$val = explode("X",$value);
					$xx += $val[0];
					$yy += $val[1];
				}
				
				 $xx = $xx/$cnt;
				 $yy = $yy/$cnt;
				
				hg_add_head_element('js-c',"var userAddress = '' ; var MAP_CENTER_POINT = '" . $xx . 'X' . $yy . "';" . $map_data_js);
			}
			else
			{
				hg_add_head_element('js-c',"var userAddress = '" . $user_location . "';" . "\r\n" . "var MAP_CENTER_POINT = '" . MAP_CENTER_POINT . "';" . $map_data_js);
			}
				
			
		}
		else
		{
			$map_data_js = 'var markers;' . "\r\n" ;
			hg_add_head_element('js-c',"var MAP_CENTER_POINT = '" . MAP_CENTER_POINT . "';" . $map_data_js);
		}
		hg_add_head_element("js","http://ditu.google.cn/maps?file=api&amp;v=2&amp;key=" . MAP_KEY . "&sensor=false");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/map.js'); 
		$html_body_attr = ' onload="initialize()"  onunload="GUnload()"';
		$this->page_title = $this->lang['pageTitle'];
		//include hg_load_template('map');
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('map');
	}
	
	//添加用户标注信息
	function create()
	{
		$info = new user();   
		$result = $info->add_location($this->input['user_location'],$this->input['description'],$this->input['user_defined']); 
		//$result = $result[0];
		 
		
		if($result['ErrorText'])
		{
			echo $result['ErrorText'];
		}
		else
		{
			//$result['lang'] = $this->lang['mark_success'];	
			//echo $result;
			echo json_encode($result);
		}
		  
	}
	
	//获取用户标注的地址位置信息
	function getMemberLocation()
	{
		$info = new user(); 
		$member_id = $this->user['id'];
		$result = $info->get_location($member_id); 
		$result = $result[0];
		return $result;
	//	echo $result;
	}
	
	function delUserLocation()
	{
		$info = new user();
		$member_id = $this->user['id'];
		$location = $this->input['latlng'];
		$result = $info->del_location($member_id,$location);
		//$result = $result[0];
		//var_dump($result);
		if($result['ErrorText'])
		{
			echo $result['ErrorText'];
		}
		else
		{
			echo $this->lang['del_sucess']; 
		}
	}
	
}

$out = new map(); 

$action = $_INPUT['a']; 

if (!method_exists($out,$action))
{
	$action = 'show';
}
 
$out->$action();
?>