<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','publishplan_publish');//模块标识
require('global.php');
class plan_logApi extends adminBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		include(CUR_CONF_PATH . 'lib/plan_log.class.php');
		$this->obj = new plan_log();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
//		$condition = $this->get_condition();
//		$data = array(
//			'operation'  =>  $condition['action_type'],	//操作类型
//			'orderby'	 =>  ' ORDER BY a.id DESC ',		//排序  $orderby = 'ORDER BY a.id DESC';
//			'offset'	 =>  $offset,		//偏移量
//			'count'		 =>  $count,		//数量
//			'sort_id'		 =>  $condition['set_id'],		//数量
//		);
//		$logdata = $this->logs->queryLogs($data);
//		foreach($logdata as $k=>$v)
//		{
//			$plandata[] = $v['up_data']?$v['up_data']:$v['pre_data'];
//		}
		
		$plandata = $this->obj->get_log($offset,$count,$this->get_condition());
		
//		print_r($plandata);exit;
		$this->addItem($plandata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX."plan_log WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
		
//		$condition = $this->get_condition();
//		$data = array(
//			'operation'  =>  $condition['action_type'],	//操作类型
//			'orderby'	 =>  ' ORDER BY a.id DESC ',		//排序  $orderby = 'ORDER BY a.id DESC';
//			'sort_id'		 =>  $condition['set_id'],		//数量
//		);
//		$logdata = $this->logs->showCount($data);
//		echo json_encode($logdata);
	}

	private function get_condition()
	{
//		$action_type = urldecode($this->input['client_type']);
//		$set_id = intval($this->input['set_id']);
//		if($action_type && $action_type!='-1')
//		{
//			$action_type = urldecode($this->input['client_type']);
//		}
//		else
//		{
//			$action_type = '';
//		}
//		$condition['action_type'] = $action_type;
//		$condition['set_id'] = $set_id;
		$condition = '';
		if($this->input['client_type'] && $this->input['client_type']!=-1)
		{
			$condition .= " AND action_type='".$this->input['client_type']."'";
		}
		$condition .= " ORDER BY id DESC ";
		return $condition;
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new plan_logApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			