<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: station_config_update.php 17814 2013-02-25 01:47:55Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','station_config');
require('global.php');
class stationConfigUpdateApi extends adminUpdateBase
{
	private $mStationConfig;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/station_config.class.php';
		$this->mStationConfig = new stationConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		$platform = trim(urldecode($this->input['platform']));
		if (!$platform)
		{
			$this->errorOutput('来源标识不能为空');
		}
	
		$ret_platform = $this->mStationConfig->_check_platform_exists($platform);
		if (!empty($ret_platform))
		{
			$this->errorOutput('来源标识已存在');
		}
		
		$callback = trim(urldecode($this->input['callback']));
		if (!$callback)
		{
			$this->errorOutput('回调函数不能为空');
		}
		
		$input_info = array(
			'name' 		=> $name,
			'platform' 	=> $platform,
			'callback' 	=> $callback
		);
		
		$info = $this->mStationConfig->_create($input_info,$this->user);
		
		if (!$info)
		{
			$this->errorOutput('添加失败');
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		$platform = trim(urldecode($this->input['platform']));
		if (!$platform)
		{
			$this->errorOutput('来源标识不能为空');
		}
		
		if ($platform != trim(urldecode($this->input['_platform'])))
		{
			$ret_platform = $this->mStationConfig->_check_platform_exists($platform);
			if (!empty($ret_platform))
			{
				$this->errorOutput('来源标识已存在');
			}
		}
		
		$callback = trim(urldecode($this->input['callback']));
		if (!$callback)
		{
			$this->errorOutput('回调函数不能为空');
		}
		
		$input_info = array(
			'name' 		=> $name,
			'platform' 	=> $platform,
			'callback' 	=> $callback
		);
		
		$info = $this->mStationConfig->_update($id, $input_info);
		
		if (!$info)
		{
			$this->errorOutput('更新失败');
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->mStationConfig->_delete($id);
		
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		
		$this->addItem($info);
		$this->output();
	}

	public function audit()
	{
		$id = trim(urldecode($this->input['id']));
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->mStationConfig->_audit($id, 'station_config', 'status');
		
		$this->addItem($info);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('未定义的空方法');
	}

}

$out = new stationConfigUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>