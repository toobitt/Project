<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_screen_update.php 6070 2012-03-12 03:18:13Z repheal $
***************************************************************************/
require('global.php');
class programScreenUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_screen.class.php');
		$this->obj = new programScreen();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示屏蔽节目单
	 */
	public function create()
	{
		if(empty($this->input['channel_id']))
		{
			$this->errorOutput("未传入频道ID");
		}

		if(empty($this->input['channel_id_back']))
		{
			$this->errorOutput("未传入替换的视频ID");
		}

		if(empty($this->input['dates']))
		{
			$this->errorOutput("未传入日期");
		}

		if(empty($this->input['start_time']))
		{
			$this->errorOutput("未传入开始时间");
		}
		
		if(empty($this->input['end_time']))
		{
			$this->errorOutput("未传入结束时间");
		}

		$start_time = strtotime($this->input['dates'] . " " . urldecode($this->input['start_time']));
		$end_time = strtotime($this->input['dates'] . " " . urldecode($this->input['end_time']));
		if($start_time >= $end_time)
		{
			$this->errorOutput("开始时间应小于结束时间");
		}
		$res = $this->obj->verify($this->input['channel_id'],$start_time,$end_time,$this->input['week_day']);
		
		if(!$res)
		{
			$this->errorOutput("该时间段已有屏蔽节目");
		}
		$ret = $this->obj->create();
		if(empty($ret))
		{
			$this->errorOutput("创建失败");
		}
		//清除缓存
		$cache_path = ROOT_PATH . 'api/cache/screen';
		hg_clear_cache($cache_path);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * Enter description here ...
	 */
	public function update()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("未传入当前屏蔽ID");
		}

		if(empty($this->input['channel_id']))
		{
			$this->errorOutput("未传入频道ID");
		}

		if(empty($this->input['channel_id_back']))
		{
			$this->errorOutput("未传入替换的视频ID");
		}
		if(empty($this->input['dates']))
		{
			$this->errorOutput("未传入日期");
		}

		if(empty($this->input['start_time']))
		{
			$this->errorOutput("未传入开始时间");
		}
		
		if(empty($this->input['end_time']))
		{
			$this->errorOutput("未传入结束时间");
		}

		$start_time = strtotime($this->input['dates'] . " " . urldecode($this->input['start_time']));
		$end_time = strtotime($this->input['dates'] . " " . urldecode($this->input['end_time']));
		if($start_time >= $end_time)
		{
			$this->errorOutput("开始时间应小于结束时间");
		}
			
		$res = $this->obj->verify($this->input['channel_id'],$start_time,$end_time,$this->input['week_day'],$this->input['id']);
		if($res == false)
		{		
			$this->errorOutput("该时间段已有屏蔽节目");
		}

		$ret = $this->obj->update();
		if(empty($ret))
		{
			$this->errorOutput("更新失败");
		}
		//清除缓存
		$cache_path = ROOT_PATH . 'api/cache/screen';
		hg_clear_cache($cache_path);
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入ID");
		}
		$ret = $this->obj->delete();
		if(empty($ret))
		{
			$this->errorOutput("删除失败！");
		}
		//清除缓存
		$cache_path = ROOT_PATH . 'api/cache/screen';
		hg_clear_cache($cache_path);
		$this->addItem($ret);
		$this->output();
	}

	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入ID");
		}
		$ret = $this->obj->audit();
		//清除缓存
		$cache_path = ROOT_PATH . 'api/cache/screen';
		hg_clear_cache($cache_path);
		$this->addItem($ret);
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
		$cache_path = ROOT_PATH . 'api/cache/screen';
		hg_clear_cache($cache_path);
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programScreenUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>