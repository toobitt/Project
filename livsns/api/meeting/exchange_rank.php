<?php
//签到大屏互动接口
define('MOD_UNIQUEID','exchange_rank');
define('SCRIPT_NAME', 'exchange_rank');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class exchange_rank extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$orderby = ' ORDER BY m.exchange_num DESC ';
		$condition = ' AND m.activate_code_id != 0 ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}
}
include(ROOT_PATH . 'excute.php');

?>