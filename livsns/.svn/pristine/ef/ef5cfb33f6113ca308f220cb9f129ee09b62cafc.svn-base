<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: trade.php 7586 2013-04-15 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/trade.class.php';
define('MOD_UNIQUEID', 'trade');  //模块标识

class tradeApi extends adminReadBase
{
	private $trade;
	
	public function __construct()
	{
		parent::__construct();
		$this->trade = new trade();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->trade);
	}
	
	public function index() {}
	
	/**
	 * 获取行业信息
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->filter_data();
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $condition
		);
		$trade_info = $this->trade->show($data);
		$this->setXmlNode('trade_info', 'trade');
		if ($trade_info) {
			foreach ($trade_info as $trade)
			{
				$this->addItem($trade);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取行业总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->trade->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单个行业信息
	 */
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$trade_info = $this->trade->detail($id);
		$this->addItem($trade_info);
		$this->output();
	}
	
	/**
	 * 获取根级别的行业信息
	 */
	public function getTopTrade()
	{
		$condition = array(
			'count' => -1,
			'condition' => array('pid' => 0)
		);
		$trade_info = $this->trade->show($condition);
		$this->setXmlNode('trade_info', 'trade');
		if ($trade_info)
		{
			foreach ($trade_info as $trade)
			{
				$this->addItem($trade);
			}
		}
		$this->output();
	}
	
	//获取所有二级行业信息
	public function getAllSubTrade()
	{
		$condition = array(
			'count' => -1,
			'condition' => array('where' => 'pid != 0')
		);
		$trade_info = $this->trade->show($condition);
		$this->setXmlNode('trade_info', 'trade');
		if ($trade_info)
		{
			foreach ($trade_info as $trade)
			{
				$this->addItem($trade);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取角色信息
	 */
	public function getAllRole()
	{
		$role_info = $this->trade->get_role();
		$this->setXmlNode('role_info', 'role');
		if ($role_info)
		{
			foreach ($role_info as $role)
			{
				$this->addItem($role);
			}
		}
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$pid = isset($this->input['pid']) ? intval($this->input['pid']) : '';
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$role_id = isset($this->input['roleId']) ? intval($this->input['roleId']) : '';
		$data = array();
		if ($pid > -1) $data['pid'] = $pid;
		if (!empty($name)) $data['keyword'] = $name;
		if ($role_id > -1) $data['role_id'] = $role_id;
		return $data;
	}
}

$out = new tradeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>