<?php
/*******************************************************************
 * filename :member_myData.php
 * 我的数据储存数据展示接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberMyData');//模块标识
require('./global.php');
class memberMyDataAdmin extends adminReadBase
{
	private $memberMyData = null;
	public function __construct()
	{
		parent::__construct();
		$this->memberMyData = new memberMyData();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show()
	{
		$this->get_condition();
		$this->memberMyData->setDataFormat(array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i'),'update_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
		$reInfo = $this->memberMyData->setFieldS('mdid as id,mms.mark,mms.title,member_name,myd.create_time,myd.update_time')->setOffsetS((int)$this->input['offset'])->setCountS((int)$this->input['count'])->show()->getoutputData();
		if(is_array($reInfo))
		foreach ($reInfo as $data)
		$this->addItem($data);
		$this->output();
	}
	public function detail()
	{
		if(($TParams = $this->memberMyData->setMdid((int)$this->input['id'])))
		{
			if($TParams == -9)
			{
				$this->errorOutput(NO_DATA_ID);
			}
			elseif($TParams == -10)
			{
				$this->errorOutput(NO_INPUT_MYDATA_ID);
			}
		}
		$this->memberMyData->setParams('mid');
		$this->addItem($this->memberMyData->detail()->selectDataFormatProcess()->outputProcess());
		$this->output();
	}
	public function count()
	{
		$this->get_condition();
		$this->addItem_withkey('total', $this->memberMyData->count());
		$this->output();
	}
	
	/**
	 *
	 * 获取需要的条件
	 */
	private function get_condition()
	{
		//搜索标签
		if ($this->input['searchtag_id']) {
			$searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
			foreach ((array)$searchtag['tag_val'] as $k => $v) {
				if ( in_array( $k, array('_id') ) )
				{
					//防止左边栏分类搜索无效
					continue;
				}
				$this->input[$k] = $v;
			}
		}
		//搜索标签
		if((isset($this->input['k']) && !empty($this->input['k']))||(trim($this->input['key']) || trim(urldecode($this->input['key']))== '0'))
		{
			if(isset($this->input['k']) && !empty($this->input['k']))//兼容老的搜索
			{
				$key = trim($this->input['k']);
			}
			elseif(trim($this->input['key']) || trim(urldecode($this->input['key']))== '0')
			{
				$key = trim($this->input['key']);
			}
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}
			$condition .= ' AND ' . $binary . ' title like \'%'.$key.'%\'';
			$memberMySet = new memberMySet();
			$markInfo = $memberMySet->show($condition,0,0,'mark','mark','',0);
			$this->memberMyData->setParams('mark',$markInfo,'myd');
		}
		else {
			$this->input['mark'] && $TParams = $this->memberMyData->setMark(trim($this->input['mark']));
			if($TParams==-3)
			{
				$this->errorOutput(PROHIBIT_CN);
			}
			elseif ($TParams == -4)
			{
				$this->errorOutput(MARK_CHARACTER_ILLEGAL);
			}
			elseif($TParams == -5)
			{
				$this->errorOutput(MARK_ERROR);
			}			
			else if (trim($this->input['mark']) == $TParams)
			{
				$this->memberMyData->setParams('mark',null,'myd');
			}
		}
		$this->memberMyData->setJoin(' LEFT JOIN '.DB_PREFIX.'member_myset as mms ON myd.mark=mms.mark');
		if(isset($this->input['member_name'])&&$member_name = trimall($this->input['member_name']))
		{
			$members = new members();
			$member_id = $members->get_member_id($member_name,false);
			$member_id && $this->memberMyData->setParams('member_id',$member_id,'myd');
			!$member_id && $this->errorOutput(NO_MEMBER);			
		}
		$this->memberMyData->setJoin(' LEFT JOIN '.DB_PREFIX.'member as m ON myd.member_id=m.member_id');
		if(isset($this->input['search'])&&$search = $this->input['search'])
		{
			$this->memberMyData->setParamType('search','fuzzy',1,'myd');
			$this->memberMyData->setParams('search',$search,'myd');
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition = " AND myd.create_time >= ".$start_time;
			$this->memberMyData->setSql()->where($condition);
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition = " AND myd.create_time <= ".$end_time;
			$this->memberMyData->setSql()->where($condition);
		}
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition = " AND myd.create_time > '".$yesterday."' AND myd.create_time < '".$today."'";
					$this->memberMyData->setSql()->where($condition);
					break;
				case 3://今天的数据
					$condition = " AND myd.create_time > '".$today."' AND myd.create_time < '".$tomorrow."'";
					$this->memberMyData->setSql()->where($condition);
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition = " AND myd.create_time > '".$last_threeday."' AND myd.create_time < '".$tomorrow."'";
					$this->memberMyData->setSql()->where($condition);
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition = " AND myd.create_time > '".$last_sevenday."' AND myd.create_time < '".$tomorrow."'";
					$this->memberMyData->setSql()->where($condition);
					break;
				default://所有时间段
					break;
			}
		}		
		$this->memberMyData->setAs('myd');
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new memberMyDataAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>