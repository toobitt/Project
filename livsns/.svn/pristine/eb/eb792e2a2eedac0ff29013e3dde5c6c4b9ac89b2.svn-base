<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_update.php 8264 2012-07-23 08:54:13Z hanwenbin $
***************************************************************************/
define('MOD_UNIQUEID', 'cp_group_m');//模块标识
require('./global.php');
require('../lib/group.class.php');

class groupUpdateApi extends BaseFrm
{
	var $group;
	
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		$this->group = new group();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//更新圈子数据
	public function update()
	{
		$group_id = $this->input['hid_gid'] ? intval($this->input['hid_gid']) : -1;
		if($group_id <= 0) $this->errorOutput(OBJECT_NULL);
		$res = $this->group->check_group_exists($group_id);
		if (!$res) $this->errorOutput(OBJECT_NULL);
		$group_name = trim(urldecode($this->input['gname']));
		$group_domain = trim(urldecode($this->input['domain']));
		$desc = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '暂无描述...';
		$fatherid = isset($this->input['fatherid']) ? intval($this->input['fatherid']) : 0;
		$group_type = isset($this->input['group_type']) ? intval($this->input['group_type']) : 0;
		$group_tag = trim(urldecode($this->input['tags']));
		$permission = isset($this->input['permission']) ? intval($this->input['permission']) : 6;
		//$lat = isset($this->input['hid_lat']) ? trim(urldecode($this->input['hid_lat'])) : 0;
		//$lng = isset($this->input['hid_lng']) ? trim(urldecode($this->input['hid_lng'])) : 0;
		//$group_addr = isset($this->input['hid_addr']) ? trim(urldecode($this->input['hid_addr'])) : '';
		if (empty($group_name)) $this->errorOutput(OBJECT_NULL);
		$update_field = array(
			'name' => $group_name,
			'group_domain' => $group_domain,
			'description' => $desc,
			'fatherid' => $fatherid,
		    'group_type' => $group_type,
			'permission' => $permission,
		    //'lat' 		  => $lat,
			//'lng' 		  => $lng,
		    //'group_addr'  => $group_addr,
		    'column_id' => $this->input['column_id'],
		);
		$result = $this->group->update($update_field, $group_id);
		if ($group_tag && $result)
		{
			include_once(ROOT_PATH . 'lib/class/mark.class.php');
			$mark = new mark();
			$mark->updateMarkByNames($group_tag, $group_id, 1);
		}
		$this->addItem($result);
		$this->output(); 
	}
	
	//圈子数据批量审核
	public function audit()
	{
		$id = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : 0;
		if (!$id) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->audit($id);
		$this->addItem($result);
		$this->output(); 
	}
	
	//圈子数据批量打回
	public function back()
	{
		$id = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : 0;
		if (!$id) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->back($id);
		$this->addItem($result);
		$this->output(); 
	}
	
	//圈子数据批量删除
	public function delete()
	{
		$id = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : 0;
		if (!$id) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->delete($id);
		$this->addItem($result);
		$this->output();
	}
	
	//方法不存在的时候调用的方法
	public function none()
	{
		$this->errorOutput('调用的方法不存在');	
	}
	
	/**
	 * 即时发布
	 * @param id  int   
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		$ret = $this->group->publish();
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
		else 
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
}

/**
 *  程序入口
 */
$out = new groupUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>