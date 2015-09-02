<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/ticket_sort.class.php';
define('MOD_UNIQUEID','ticket_sort');//模块标识
class ticketSortApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sort = new ticketSort();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function detail()
	{
		
	}
	function count()
	{
		
	}
	public function show()
	{
		$id 	= trim($this->input['sort_id']);
		$type 	= intval($this->input['show_by_id']);
		
		$data = $this->sort->sort($id,$type);
		
		if (!empty($data))
		{
			foreach ($data as $k=>$v)
			{	
				$this->addItem($v);
			}
		}
		$this->output();
	} 
}
$out = new ticketSortApi();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>