<?php
!defined('IN_UC') && exit('Access Denied');

class control extends adminbase {

	var $status;
	
	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminapp']) {
			$this->message('no_permission_for_this_module');
		}
		$this->load('ugroup');
	}

	/**
	 * 获取用户组信息
	 */
	function onls() {
		$a = getgpc('a');
		$ugrouplist = $_ENV['ugroup']->get_ugroups();

		$this->view->assign('a',$a);
		if(isset($this->status))
		{
			$this->view->assign('status', $this->status);
		}
		$this->view->assign('ugrouplist', $ugrouplist);
		$this->view->display("admin_ugroup");
	}
	
	/**
	 * 添加用户组设置
	 */
	function onadd() 
	{
		$ug_name = getgpc('ug_name');
		$ug_desc = getgpc("ug_descr");
		$ug_order = getgpc("ug_order");
		if($ug_desc && $ug_name && $ug_order)
		{
			$_ENV['ugroup']->add_ug($ug_name,$ug_desc,$ug_order);
		}
		else
		{
			$this->status = 'N';
		}
		
		$this->onls();
	}
	
	/**
	 * 删除用户组
	 */
	function ondel() 
	{
		$group_id = intval(getgpc("group_id"));
		$this->status = $_ENV['ugroup']->delete_ug($group_id);
		$this->onls();
	}
	
	/**
	 * 编辑用户组设置信息
	 */
	function onedit() 
	{
		
		$a = getgpc('a');
		if(isset($a))
		{
			$a = "edit";//为了更新用户时调用
		}
		$group_id = getgpc("group_id");
		$ugroupArr = $_ENV['ugroup']->edit_ug($group_id);
		if(is_array($ugroupArr))
		{
			if(isset($this->status))
			{
				$this->view->assign('status', $this->status);//更新的页面提示
			}
			$this->view->assign('a',$a);
			$this->view->assign('ugroupArr',$ugroupArr);
			$this->view->display("admin_ugroup");
		}
		else
		{
			$this->status = $ugroupArr;
			$this->onls();
		}
	}
	
	/**
	 * 更新用户租
	 */
	function onupdate()
	{
		$ug_id = getgpc('group_id');
		$ug_name = getgpc('group_name');
		$ug_desc = getgpc("group_desc");
		$ug_order = getgpc("group_order");
		if($ug_desc && $ug_name && $ug_order !=null )
		{
			$this->status = $_ENV['ugroup']->update_ug($ug_id,$ug_name,$ug_desc,$ug_order);
			$this->onls();
		}
		else 
		{
			$this->status = 'N';
			$this->onedit();
		}
	}
}

?>