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
require('global.php');
class publish_planApi extends BaseFrm
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
		include(CUR_CONF_PATH . 'lib/publish_plan.class.php');
		$this->obj = new publish_plan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$plandata = $this->obj->get_plan($offset,$count,$this->get_condition());
		$this->addItem($plandata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."plan ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		if($action_type = intval($this->input['_id']))
		{
			$condition = " WHERE action_type='".$action_type."'";
		}
		
		return $condition;	
	}
	
	public function insert_queue()
	{
		$data = json_decode(urldecode($this->input['data']),true);
		if(empty($data['from_id']) || empty($data['column_id']) || empty($data['action_type']))
		{
			$result['msg'] = '相关信息未传入';
	    	$result['error'] = '2';
			$this->addItem($result);
			$this->output();
		}
		$queuedata = array(
			'set_id' => $data['set_id'],
			'from_id' => $data['from_id'],
			'class_id' => $data['class_id'],
			'column_id' => $data['column_id'],
			'title' => $data['title'],
			'action_type' => $data['action_type'],
			'publish_time' => $data['publish_time'],
			'next_publish_time' => $data['next_publish_time'],
			'publish_people' => $data['publish_people'],
			'ip' => $data['ip'],
		);
		$this->obj->insert_queue($queuedata);
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

$out = new publish_planApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			