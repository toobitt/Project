<?php
//输出大会嘉宾接口
define('MOD_UNIQUEID','get_sign_members');
define('SCRIPT_NAME', 'get_sign_members');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class get_sign_members extends outerReadBase
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
	
	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 500;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id ASC,id ASC ';
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
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['is_sign'])
		{
			$condition .= " AND is_sign = 1 ";
		}
		
		return $condition;
	}
}

include(ROOT_PATH . 'excute.php');

?>