<?php
require ('global.php');
define('MOD_UNIQUEID','announcement');
define('SCRIPT_NAME', 'get_announcement_by_district');
require_once(CUR_CONF_PATH . 'lib/announcement_mode.php');
//外部调用接口获取公告
class get_announcement_by_district extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new announcement_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}	
	
	public function show()
	{
		if(!$this->input['district_id'])
		{
			$this->errorOutput(NOID);
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		
		//类型id
		if($this->input['type_id'])
		{
			$orderby = ' AND type_id = ' . $this->input['type_id'];
		}
		
		$orderby .= ' ORDER BY a.order_id DESC,a.id DESC ';
		$ret = $this->mode->get_announcement_by_district($this->input['district_id'],$orderby,$limit);
		if($ret)
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		else 
		{
			$this->addItem(array());
		}
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>