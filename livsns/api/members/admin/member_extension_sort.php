<?php
/***************************************************************************
* $Id: member_extension_field.php 26794 2013-08-01 04:34:02Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_extension_sort');//模块标识
require('./global.php');
class memberextensionsortApi extends adminReadBase
{
	private $mmemberextensionsort;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/member_extension_sort.class.php';
		$this->mmemberextensionsort = new mmemberextensionsort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{	
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$this->showAll($condition);

	}
	
	public function showAll($condition = '')
	{
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->mmemberextensionsort->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$extension_sort_id = trim($this->input['id']);
		$info = $this->mmemberextensionsort->detail($extension_sort_id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mmemberextensionsort->count($condition);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND extension_sort_name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		
		return $condition;
	}

}

$out = new memberextensionsortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>