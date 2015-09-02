<?php
define('MOD_UNIQUEID','activate_code');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/activate_code_mode.php');
class activate_code extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		/*********************************权限*****************************/
		if($this->user['group_type'] > 1)//只有系统用户才可以查看
		{
			$this->errorOutput('你没有权限查看激活码');
		}
		/*********************************权限*****************************/
		$this->mode = new activate_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY id DESC ';
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
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND activate_code  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if(intval($this->input['is_use']) == 1)
		{
			$condition .= " AND is_use = 0 ";//未使用
		}
		else if(intval($this->input['is_use']) == 2)
		{
			$condition .= " AND is_use = 1 ";//已使用
		}
		
		if($this->input['guest_type'])
		{
			$condition .= " AND guest_type = '"  .$this->input['guest_type']. "' ";
		}
		
		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
}

$out = new activate_code();
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