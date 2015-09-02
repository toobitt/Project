<?php
define('ROOT_DIR', '../');
require('./global.php');
require_once(ROOT_PATH . 'lib/class/groups.class.php');

class myGroup extends uiBaseFrm
{
	var $mGroup;
	function __construct()
	{
		parent::__construct();
		$this->mGroup = new Group();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function getMyGroups()
	{
		$groups = array(); 
		$groups = $this->mGroup->get_my_groups($this->user['id']); 
		if(!$groups)
		{
			echo 0;
		}
		else
		{
			$ul = '<ul class="group_list">';
			foreach($groups as $key => $value)
			{ 
				$ul .= '<li class=" " onmouseover="changeCss(this);" onmouseout="changeCss(this);" onclick="choose_groups(' . $value['group_id'] . ',\''.$value['name'].'\')" >';
				$ul .= $value['name'] . '</li>';
				 
			}
			$ul .= '</ul>';
			$ul .= '<div style="text-align:right;margin-top:10px;"><input style="height:25px;width:64px;font-size:12px;margin-right:5px;" type="button" id="pub_to_btnYES" value="确定" onclick="confirm_groups()" />&nbsp;&nbsp;<input id="pub_to_btnNO" type="button" value="取消" style="height:25px;width:64px;font-size:12px;margin-right:15px;" onclick="cancle_choice();" /></div>';
			$id = $this->input['id']? $this->input['id']:0; 
			if($id)
			{
				$link = hg_build_link(SNS_VIDEO."video_play.php",array('id'=>$id));
				$ul .= '<input type="hidden" id="video_id" value="'.$id.'"/><input type="hidden" id="video_info" value="'.$link.'"/><input type="hidden" id="video_name" value="'.$this->input['title'].'"/>';
			}
			echo $ul;
		}
	}
}

$out = new myGroup();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'getMyGroups';
}
$out->$action();