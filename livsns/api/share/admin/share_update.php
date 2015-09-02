<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','share');//模块标识
class shareUpdateApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function add_share_new()
	{
		$ret = $this->obj->get_account_by_last();
		if($ret)
		{
			foreach($ret as $k=>$v)
			{
				if($ret[$k]['picurl'])
				{
					$ret[$k]['picurl'] = UPLOAD_THUMB_URL.$ret[$k]['picurl'];
				}
			}
		}
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function detail()
	{
		$ret = array();
		if($id = $this->input['id'])
		{
			$ret = $this->obj->get_account_by_id($id);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{	
		if(!$ret = $this->obj->delete_by_id())
		{
			$this->errorOutput('删除失败');
		}
	}
	
	public function update()
	{
		if($id = intval($this->input['id']))
		{
			$pic_data = array();
			$pic_data = $this->insert_pic();
			if($info = $this->obj->update_by_id($id,$pic_data))
			{
				$this->addItem($info);
				$this->output();
			}
			else
			{
				$this->errorOutput('更新失败');
			}
		}
		else
		{
			$this->errorOutput('更新失败');
		}
		
	}
	
	public function insert_pic()
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		$result = array();
		if ($_FILES['pic_files'])
		{
			$file['Filedata'] = $_FILES['pic_files'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['picurl']['id'] = $default['id'];
			$result['picurl']['host'] = $default['host'];
			$result['picurl']['dir'] = $default['dir'];
			$result['picurl']['filepath'] = $default['filepath'];
			$result['picurl']['filename'] = $default['filename'];
		}
		if ($_FILES['pic_login'])
		{
			$default = array();
			$file['Filedata'] = $_FILES['pic_login'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['pic_login']['id'] = $default['id'];
			$result['pic_login']['host'] = $default['host'];
			$result['pic_login']['dir'] = $default['dir'];
			$result['pic_login']['filepath'] = $default['filepath'];
			$result['pic_login']['filename'] = $default['filename'];
		}
		if ($_FILES['pic_share'])
		{
			$default = array();
			$file['Filedata'] = $_FILES['pic_share'];
			$default = $this->mMaterial->addMaterial($file, '');
			$result['pic_share']['id'] = $default['id'];
			$result['pic_share']['host'] = $default['host'];
			$result['pic_share']['dir'] = $default['dir'];
			$result['pic_share']['filepath'] = $default['filepath'];
			$result['pic_share']['filename'] = $default['filename'];
		}
		return $result;
	}
	
	public function show_opration()
	{
		if($id = intval($this->input['id']))
		{
			$ret = $this->obj->get_account_by_id($id);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new shareUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			