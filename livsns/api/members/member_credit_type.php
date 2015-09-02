<?php
define('MOD_UNIQUEID','member_credit_type');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_credit_type.class.php';
class membercredittypeApi extends outerReadBase
{
	private $Members;
	public function __construct()
	{
		parent::__construct();
		$this->credittype = new credittype();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出积分类型列表
	 *
	 */
	public function show()
	{
		$condition 	= $this->get_condition();

		$info 	= $this->credittype->show($condition);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}
	/**
	 * 取出单个积分类型
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$info = $this->credittype->detail($id);
		$this->addItem($info);
		$this->output();
	}
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "credit_type WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	
	/**
	 * 
	 * 获取已启用的积分类型 ...
	 */
	public function get_credit_type()
	{
		$datas=$this->Members->get_credit_type();
		
		if ($datas&&is_array($datas))
		{
			foreach ($datas as $key=>$data)
			{
				$this->addItem_withkey($key, $data);
			}
		}
		$this->output();
	}
	
	/**
	 * 
	 * 获取允许交易的积分字段(建议使用get_credit_type方法获取,然后写个循环根据is_trans状态判断哪个为交易积分类型同时还可以获得详细的积分配置) ...
	 */
	public function get_trans_credits_type()
	{
		$datas=$this->Members->get_trans_credits_type();		
		$this->addItem_withkey('db_field', $datas);
		$this->output();
	}
	/**
	 * 
	 * 获取升级的积分字段((建议使用get_credit_type方法获取,然后写个循环根据is_update状态判断哪个为交易积分类型同时还可以获得详细的积分配置))
	 */
	public function get_grade_credits_type()
	{
		$datas=$this->Members->get_grade_credits_type();		
		$this->addItem_withkey('db_field', $datas);
		$this->output();
	}
		
	private function get_condition()
	{
		$condition = "";

		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}
			$condition .= ' AND ' . $binary . ' title like \'%'.trim($this->input['k']).'%\'';
		}

		return $condition;
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new membercredittypeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>