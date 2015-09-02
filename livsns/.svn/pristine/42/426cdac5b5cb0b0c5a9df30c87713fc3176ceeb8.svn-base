<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class group_request_info extends BaseFrm
{
   public function __construct()
	{
	   parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
    
	/**
	* 根据地盘ID检索地盘信息
	* @name show_opration
	* @access public 
	* @author wangleyuan
	* @category hogesoft
	* @copyright hogesoft
	* @param id int 地盘ID
	* @return array $return 文章信息
	*/
	public function show_opration()
	{
		if(!$this->input['group_id'])
		{
			$this->errorOutput('未传入地盘ID');
		}
		$sql="SELECT g.*, t.* 
				FROM " . DB_PREFIX."group g 
				LEFT JOIN " .DB_PREFIX ."group_type t 
				ON g.group_type = t.typeid where g.group_id=" . intval($this->input['group_id']);
		$return=$this->db->query_first($sql);
		if(!$return['group_id'])
		{
			$this->errorOutput('文章不存在或已被删除');
		}
		$return['logo'] = $this->get_group_logo($return['group_id'],70);

	    //记录页面的所处的类型与类别
		if($this->input['frame_type'])
		{
			$return['frame_type'] = intval($this->input['frame_type']);
		}
		else
		{
			$return['frame_type'] = '';
		}
		
		if($this->input['frame_sort'])
		{
			$return['frame_sort'] = intval($this->input['frame_sort']);
		}
		else
		{
			$return['frame_sort'] = '';
		}
        $return['create_time']=date('Y-m-d H:i',$return['create_time']);
		$return['update_time']=date('Y-m-d H:i',$return['update_time']);
		$this->addItem($return);
		$this->output();
	}
	function get_group_logo($group_id, $size = '')
	{
		$path = 'group/oth/70/';
		if ($size) 
		{
			$size = ' width="' . $size . '"';
		}
		$src  = $path . 'logo_' . $group_id . '.jpg';
		return $src = $this->settings['livime_upload_url'] . $src; 
	}
}

$out=new group_request_info();
if(!method_exists($out,$_INPUT['a']))
{
	$action='show_opration';
}
else
{
	$action=$_INPUT['a'];
}
$out->$action();
?>