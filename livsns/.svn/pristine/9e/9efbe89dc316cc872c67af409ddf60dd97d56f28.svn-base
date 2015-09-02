<?php
define('MOD_UNIQUEID','market_message');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_message_mode.php');
class market_message extends outerReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_message_mode();
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
		$orderby = '  ORDER BY order_id DESC,id DESC ';
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
		if($this->input['market_id'])
		{
			$condition .= " AND market_id = '".$this->input['market_id']."'";
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
	
	//根据会员id查询出与该会员相关的消息
	public function getMessageByMemberId()
	{
		//这是会员中心的id
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NOT_LOGIN);
		}
		
		//查消息必须指明查哪个超市的消息
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->getMessageByMemberId($this->input['market_id'],$this->user['user_id'],$this->input['device']);
		if(!$ret)
		{
			$this->errorOutput(NO_DATA);
		}
		echo json_encode($ret);
	}
}

$out = new market_message();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'getMessageByMemberId';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>