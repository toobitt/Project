<?php
define('MOD_UNIQUEID','member_credit_log');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_log.class.php';
class member_credit_log extends adminReadBase
{
	private $credit_log = null;
	public function __construct()
	{
		parent::__construct();
		$this->credit_log = new credit_log();
		$this->Members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition=$this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$credit_type=$this->Members->get_credit_type();
		$credit_type_field='';
		if($credit_type)
		{
			$credit_type_field=','.implode(',', array_keys($credit_type));
		}
		$field='id,member_id,dateline'.$credit_type_field.',icon,title,remark';
		$info 	= $this->credit_log->show($condition,$offset,$count,$field);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if($credit_type&&is_array($credit_type))
				foreach ($credit_type as $kk=>$vv)
				{
					if($v[$kk]>0)
					{
						$v[$kk] ='+'.$v[$kk].$vv['title'];
					}
					elseif ($v[$kk]<0)
					{
						$v[$kk] .=$vv['title'];
					}
					else 
					{
						$v[$kk] = $vv['title']."未变更";
					}
				}
				$v['dateline'] 	= date('Y-m-d H:i:s', $v['dateline']);
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
	}
	
	public function getCreditLogFromMembers()
	{
		try {
			$this->setAddItemValueType();
			$this->addItem($this->credit_log->getCreditFromMembers(intval($this->input['id']),intval($this->input['page']),intval($this->input['page_num'])));
			$this->output();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "credit_log WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$member_info=$this->Members->get_member_id(trim($this->input['k']),true,true);
			if(is_array($member_info))
			{
				$member_id=array();
				foreach ($member_info as $v)
				{
					$member_id[]=$v;
				}
				if($member_id&&is_array($member_id))
				{
					$member_id=trim(implode(',', $member_id));
					$condition=' AND member_id IN( '.$member_id.')';
				}
			}
			else {
				$condition=' AND member_id = 0 ';
			}
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND dateline >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND dateline <= ".$end_time;
		}
		if($this->input['dateline'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['dateline']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  dateline > ".$yesterday." AND dateline < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  dateline > ".$today." AND dateline < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  dateline > ".$last_threeday." AND dateline < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND dateline > ".$last_sevenday." AND dateline < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}


}

$out = new member_credit_log();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>