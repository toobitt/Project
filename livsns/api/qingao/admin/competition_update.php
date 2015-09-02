<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|unknow
* 
* $Id: competition_update.php 6611 2012-05-02 07:07:57Z lijiaying $
***************************************************************************/
require('global.php');
class competitonUpdateApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include competiton.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/competiton.class.php');
		$this->obj = new competiton();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 参赛作品流程添加
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function create()
	{
		if (!$this->input['uid'])
		{
			$this->errorOutput('未传入用户ID');
		}
		
		if (!$this->input['aid'])
		{
			$this->errorOutput('请选择赛区');
		}
		
		if (!$this->input['cid'])
		{
			$this->errorOutput('请选择院校');
		}
		
		if (!$this->input['tid'])
		{
			$this->errorOutput('请确定选题类型');
		}
		
		if (!$this->input['opus_name'])
		{
			$this->errorOutput('作品名称不能为空');
		}
		
		if (!$this->input['entry_name'])
		{
			$this->errorOutput('参赛名不能为空');
		}
		
		if (!$this->input['papers_type'])
		{
			$this->errorOutput('请选择证件类型');
		}
		
		if (!$this->input['papers_num'])
		{
			$this->errorOutput('证件号不能为空');
		}
		
		if (!$this->input['sign_time'])
		{
			$this->errorOutput('签署时间不能为空');
		}
		
		$data = $this->obj->create();
		if (!$data)
		{
			$this->errorOutput('添加失败');
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	
}

$out = new competitonUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>