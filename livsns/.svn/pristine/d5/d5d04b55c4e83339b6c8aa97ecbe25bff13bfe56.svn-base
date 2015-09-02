<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_send.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagesend.class.php';
define('MOD_UNIQUEID', 'message_send'); //模块标识

class messagesendApi extends adminReadBase
{
	private $messagesend;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagesend = new messagesendClass();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagesend);
	}
	
	public function index()
	{
		
	}
	
	/**
	 * 信息列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$messagesend_info = array();
		$messagesend_info = $this->messagesend->show($offset, $count, $condition);
		$this->setXmlNode('messagesend_info', 'messagesend');
		if ($messagesend_info)
		{	
			foreach($messagesend_info as $messagesend){
				$this->addItem($messagesend);
			}
		}
		$this->output();
	}
	
	/**
	 * 信息数据总数
	 */
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->messagesend->count($condition);
		echo json_encode($info);
	}

	/**
	**	信息编辑
	**/
	public function detail()
	{
		$id = trim($this->input['id']);
		if(!$id){
			$this->errorOutput(OBJECT_NULL);
		}
		
		$info = array();		
		$info = $this->messagesend->detail($id);	
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 * @param Array $data
	 */
	private function get_condition()
	{
		//关键字查询 received_phone
		if(trim(urldecode($this->input['key'])))
		{
			$condition .= ' AND  received_phone  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		//审核状态查询
		if($this->input['audio_status'])
		{
			$audio_status = (int)$this->input['audio_status'];
			if($audio_status!=3){
				$condition .= " AND status = ".$audio_status;
			}
		}
		elseif(($this->input['audio_status']) == '0')  //此处为了区分状态0的情况与传过来的值为空的情况，为空的时候查出所有
		{
			$condition .= " AND status = 0 ";
		}
		
		//信息发送状态查询
		if($this->input['back_status'])
		{
			$back_status = (int)$this->input['back_status'];
			if($back_status!=4){
				$condition .= " AND backstatus = ".$back_status;
			}
		}
		
		//自定义时间查询
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$start_time = (int)$start_time;
			$condition .= " AND create_time >= ".$start_time;
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$end_time = (int)$end_time;
			$condition .= " AND create_time <= ".$end_time;
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
		
	}
}
$out = new messagesendApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();

?>