<?php
define('MOD_UNIQUEID','member_sign');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_sign.class.php';
class member_signApi extends outerReadBase
{
	private $Members;
	public function __construct()
	{
		parent::__construct();
		$this->sign = new sign();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 签到数据列表
	 *
	 *
	 */
	public function show()
	{
		$condition 	= '';
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->sign->show($condition,$offset,$count);

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
	 * 获取会员签到详细数据
	 */
	public function detail()
	{
		$id = intval($this->input['member_id']);
		if(empty($id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$info = $this->sign->detail($id);
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 
	 * 获取签到统计 ...
	 */
	public function get_sign_count()
	{
		$info=$this->sign->get_sign_count();
		$this->addItem($info);
		$this->output();
	}
	/**
	 * 签到会员数量统计
	 * @see outerReadBase::count()
	 */
	public function count()
	{
		$condition = '';
		$sql = "SELECT COUNT(member_id) AS total FROM " . DB_PREFIX . "sign WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new member_signApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>