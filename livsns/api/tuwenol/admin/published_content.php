<?php
define('MOD_UNIQUEID','publised_content');//模块标识
define('SCRIPT_NAME', 'publised_content');
require('./global.php');
require(CUR_CONF_PATH . 'lib/content.class.php');
class publised_content extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function publishlib()
	{
		$c = new content();
		
		$condi = array(
		'client_type'=>2, 
		'need_count'=>1,
		'offset'=>$this->input['offset'],
		'count'=>$this->input['count'],
		'column_id'=>$this->input['col_id'],
		'k'=>urldecode($this->input['k']),
		'site_id'=>intval($this->input['site_id'])
		);
		
		$data = $c->get_published_content($condi);
		if($data)
		{
			$total = $data['total'];
			$data = $data['data'];
			$this->addItem_withkey('total', $total);
			$this->addItem_withkey('data', $data);
		}
		$this->output();
	}
	function publishcol()
	{
		$condition = '';
		if($this->input['fid'])
		{
			$condition .= ' AND fid = ' . intval($this->input['fid']);
		}
		else
		{
			$condition .= ' AND fid = 0';
		}
		if($this->input['site_id'])
		{
			$condition .= ' AND site_id = ' . intval($this->input['site_id']);
		}
		$c = new content();
		$data = ($c->get_published_column($condition));
		if($data)
		{
			foreach($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	function site()
	{
		$c = new content();
		$data = ($c->get_site());
		if($data)
		{
			foreach($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
?>