<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function 
*
* $Id: recycle_update.php 21948 2013-05-11 02:14:30Z wangleyuan $
***************************************************************************/
define('MOD_UNIQUEID','recycle');
require('global.php');
class recycleUpdateApi extends adminUpdateBase
{
	/**
	 * 构造函数
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include recycle.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recycle.class.php');
		$this->obj = new recycle();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}	
	
	/**
	 *  插入回收站
	 * @name add_recycle
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function add_recycle()
	{
		if(empty($this->input['title']) || empty($this->input['cid']) || empty($this->input['content']) || empty($this->input['app_mark']) || empty($this->input['module_mark']))
		{
			$ret = array('is_open'=> true,'sucess' => false,'msg' => '传入信息不完整');
		}
		else 
		{
			$ret = $this->obj->add_recycle();
		}
		$this->addItem($ret);
		$this->output();
	}


	/**
	*	从回收站恢复
	*	@name recover_recycle
	*	@access public 
	*	@author wangleyuan
	*	@category hogesoft
	*	@copyright hogesoft
	*/
	public function recover_recycle()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(empty($this->input['id']))
		{
			$this->errorOutput("请传入ID");
		}
		$ret = $this->obj->recover_recycle();
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('恢复失败');
		}
	}

	/**
	 * 根据ID删除回收站（支持批量）
	 * @name delete
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 文章ID
	 * @return $ret int 文章ID
	 */
	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(empty($this->input['id']))
		{
			$this->errorOutput("ID不能为空");
		}
		$ret = $this->obj->delete();
		$this->addItem($ret);
		$this->output();	
	}


	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author wangleyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new recycleUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			