<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_library');//模块标识

class libraryColumnUpdateApi extends adminUpdateBase
{
	private $obj;
	
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/library.class.php');
		$this->obj = new library();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}
	
	public function create()
	{
		$info = array();
		if(!trim($this->input['name']))
		{
			$this->errorOutput('属性名不为空');
		}
		else
		{
			$info['name'] = trim($this->input['name']);
		}
		
		$data = array();
		if(empty($this->input['channel_id']))
		{
			$this->errorOutput('频道不能为空！');
		}
		else
		{
			$data['channel_id'] = intval($this->input['channel_id']);
			
		}
		
		if(empty($this->input['colunm_name']))
		{
			$this->errorOutput('栏目名不为空');
		}
		else
		{
			$info['colunm_name'] = trim($this->input['colunm_name']);
		}
		
		//logo
		
		
		if(!empty($this->input['intro']))
		{
			$info['intro'] = trim($this->input['intro']);
		}
		
		if(!empty($this->input['bulletin']))
		{
			$info['bulletin'] = trim($this->input['bulletin']);
		}
		
		if(!empty($this->input['dates']))
		{
			$info['dates'] = trim($this->input['dates']);
		}
		else
		{
			$this->errorOutput('日期不为空');
		}
		
		if(!empty($this->input['start_time']))
		{
			$info['start_time'] = trim($this->input['start_time']);
		}
		else
		{
			$this->errorOutput('播放开始时间不能为空');
		}
		
		if(!empty($this->input['start_time']))
		{
			$info['start_time'] = trim($this->input['start_time']);
		}
		else
		{
			$this->errorOutput('播放结束时间不能为空');
		}
		
		if(!empty($this->input['week_day']))
		{
			$info['week_day'] = trim($this->input['week_day']);
		}
		
		$info['user_id'] = $this->user['user_id'];
		$info['user_name'] = $this->user['user_name'];
		$info['appid'] = $this->user['appid'];
		$info['appname'] = $this->user['display_name'];
		$info['create_time'] = TIMENOW;
		$info['ip'] = hg_getip();	
		
		$ret = $this->obj->create_column($info);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function update()
	{
		//暂时没有编辑
		//$this->obj->update_property();
	}
	
	public function delete()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput('未传入属性ID');
		}
		$ret = $this->obj->delete_property($id);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 上传图片
	 */
	public function upload()
	{
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$upload = new material();
		$result = $upload->addMaterial($this->user['user_id'], $_FILES);
		$size = trim($this->input['img_size']);
		$out = array(
			'url' => $result['host'].$result['dir'].$size.'/'.$result['filepath'].$result['filename'],
			'data' => serialize($result)
		);
		echo json_encode($out);
	}
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	/**
	 * 调用不存在的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new libraryColumnUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>