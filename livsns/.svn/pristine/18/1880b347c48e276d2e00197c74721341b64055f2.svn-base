<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_library');//模块标识
class libraryColumnApi extends adminReadBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/library.class.php');
		$this->obj = new library();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if($this->mNeedCheckIn && !$this->prms['show'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(intval($this->input['count']) < 0)
		{
			$data_limit = '';
		}
		else
		{			
			$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
			$count = $this->input['count'] ? intval($this->input['count']) : 20;
			$data_limit = " LIMIT " . $offset . " , " . $count;
		}
		$condition = $this->get_condition();
		$ret = $this->obj->show_property($condition . $data_limit);
		$this->addItem($ret);
		$this->output();
	}
	
	public function detail()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : 0;
		$ret = $this->obj->detail_property($id);
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$total = $this->obj->count_property();
		$this->addItem($ret);
		$this->output();
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
}

$out = new libraryColumnApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>