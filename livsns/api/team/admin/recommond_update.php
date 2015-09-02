<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 13202 2012-10-27 12:32:11Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','recommond');//模块标识
require('global.php');
class recommondUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recommond.class.php');
		$this->obj = new recommond();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}

	public function create()
	{	
		if(empty($this->input['column_id']))
		{
			$this->errorOutput("请传入栏目ID！");
		}
		if(empty($this->input[$this->input['source'].'_id']))
		{
			$this->errorOutput("请传入内容ID！");
		}
		if(empty($this->input['source']))
		{
			$this->errorOutput("请传入数据来源！");
		}
		if(empty($this->input['title']))
		{
			$this->errorOutput('请传入标题');
		}
		$column_id = $this->input['column_id'];
		if(is_string($column_id))
		{
			$column_id = explode(',',$column_id);
		}
		$file_name_prex = $replace_column = array();
		foreach($column_id as $k => $v)
		{
			$file_name_prex[] = 'recommond-' . $v . '-';
			$replace_column[] = '';
		}
		
		$dir = RECOMMOND_CACHE;
		$dh = opendir($dir);
		while ($file = readdir($dh))
		{
			$count = 0;
			str_replace($file_name_prex,$replace_column,$file,$count);
			if($count && file_exists($dir . $file))
			{
				@unlink($dir . $file);
			}
		} 
		$ret = $this->obj->create();
		if(empty($ret))
		{
			$this->errorOutput("推荐有误！");
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput("请传入ID！");
		}
		$file_name_prex = 'recommond-';
		$dir = RECOMMOND_CACHE;
		$dh = opendir($dir);
		while ($file = readdir($dh))
		{
			$count = 0;
			str_replace($file_name_prex,'',$file,$count);
			if($count && file_exists($dir . $file))
			{
				@unlink($dir . $file);
			}
		}
		$ret = $this->obj->delete();
		$this->addItem($ret);
		$this->output();
	}
	
	public function order_update()
	{
		$fid = $this->input['fid'];//原有的排序内容ID
		$tid = $this->input['tid'];//新的排序内容ID
		if(empty($fid))
		{
			$this->errorOutput("请传入原来排序的id！");
		}
		if(empty($tid))
		{
			$this->errorOutput("请传入新的排序的id！");
		}
		$ret = $this->obj->order_update($fid,$tid);
		if(empty($ret))
		{
			$this->errorOutput("排序有误！");
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function sort()
	{
		
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new recommondUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	