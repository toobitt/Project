<?php
define('MOD_UNIQUEID','member_qdxq');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member_qdxq.class.php';
class member_qdxqApi extends outerReadBase
{
	private $qdxq;
	public function __construct()
	{
		parent::__construct();
		$this->qdxq = new qdxq();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 签到心情展示 ...
	 */
	public function show()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->qdxq->show($condition,$offset,$count);

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
	}

	public function count()
	{

	}
	
	private function get_condition()
	{
		$condition = 'AND is_sys=0';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if ($this->input['id'])
		{
			$condition .= " AND id = " . intval($this->input['id']);
		}
		return $condition;
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new member_qdxqApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>