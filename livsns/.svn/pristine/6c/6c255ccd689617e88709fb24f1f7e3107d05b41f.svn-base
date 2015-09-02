<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: badword.php 776 2010-12-17 05:47:15Z yuna $
*/

!defined('IN_UC') && exit('Access Denied');
define("ROOT_PATH","../");
require_once(ROOT_PATH . "lib/func/functions.php");

class control extends adminbase {
	
	var $mUset;
	var $result;
	
	function __construct() {
		$this->control();
	}

	function control() {
		parent::__construct();
		$this->check_priv();
		if(!$this->user['isfounder'] && !$this->user['allowadminbadword']) {
			$this->message('no_permission_for_this_module');
		}
		/*引入接口文件,并初始化*/
		require_once(ROOT_PATH . "lib/class/uset.class.php");
		$this->mUset = new uset();
	}
	
	/*
	 * 
	 * 显示用户设置列表
	 */
	function onls() {
		
		//如果添加用户设置
		if(getgpc('add_user_set','P'))
		{
			$praArr = $_POST;
			$praArr['username'] = $this->user['username'];
			$usetArr = serialize($praArr);
			if(!empty($usetArr))
			{
				$this->mUset->add_user_set($usetArr);
			}
		}
		
		//如果删除
		if(getgpc('del','G'))
		{
			$id = getgpc('id');
			$this->mUset->del_user_set($id);
		}
		
		//如果更新
		if(getgpc('update','G'))
		{
			$updateArr = serialize($_POST);
			$this->mUset->update_user_set($updateArr);
		}
		
		$all_user_set = $this->mUset->get_user_set();//获取所有用户设置
		//$this->view->assign("result",$this->result);
		$this->view->assign("all_user_set",$all_user_set);
		$this->view->display('admin_uset');
		
	}
}

?>