<?php
/*******************************************************************
 * filename :member_myData.php
 * 我的数据储存数据展示接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','member_myField');//模块标识
require('./global.php');
class member_myFieldAdmin extends adminReadBase
{
	private $memberMyField = null;
	private $memberMyFieldBind = null;
	public function __construct()
	{
		parent::__construct();
		$this->memberMyField = new memberMyField();
		$this->memberMyFieldBind = new memberMyFieldBind();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show()
	{
		$reInfo = array();
		try 
		{
			$this->get_condition();
			$this->memberMyField->setDataFormat(array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i'),'update_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
			$reInfo = $this->memberMyField->setFieldS('myf.mfid as id,fieldmark,fieldname,brief,addstatus,issearch,isrequired,user_id,user_name,create_time,update_time')->setOffsetS((int)$this->input['offset'])->setCountS((int)$this->input['count'])->show();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->setAddItemValueType();
		$this->addItem($reInfo);
		$this->output();
	}
	
	public function detail()
	{
		$myFieldInfo = array();
		try {
			$this->memberMyField->setMfid((int)$this->input['id']);
			$this->memberMyField->setParams('mfid');
			$myFieldInfo = $this->memberMyField->setFieldS('mfid as id,fieldmark,fieldname,brief,isunique,addstatus,issearch,isrequired,defaultsvalue,user_id,user_name,create_time,update_time')->detail();
			$myFieldInfo && $myFieldInfo['bindms'] = $this->memberMyFieldBind->getBindInfoToMfId($this->memberMyField->getMfid());
		}
	    catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
		$this->addItem($myFieldInfo);
		$this->output();
	}
	public function count()
	{
		try {
		 $this->get_condition();
		 $total = $this->memberMyField->count();
		}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
    	$this->addItem_withkey('total', $total);
		$this->output();
	}
	
	public function getMySet()
	{
		try {
		   $id = (int)$this->input['id'];
		   $info = memberMySet::show(array(),0,0,'id as msid,title as mstitle','msid');
		   $id>0 && $this->memberMyFieldBind->setMfId($id);
	       $unsetArr = $this->memberMyFieldBind->notBindMySet();
		}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
       foreach ((array)$info as $k => $v)
       {
       	 if(!in_array($k, $unsetArr))
         {
		   $this->addItem($v);
       	 }
       }
		$this->output();
	}
	
	public function getMySetBind()
	{
		$BindMsid = array();
		try {
		   $BindMsid = $this->memberMyFieldBind->getBindMsid();
		   $info = memberMySet::show($BindMsid,0,0,'id as msid,title as mstitle','msid');
		}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
       foreach ((array)$info as $k => $v)
       {
		   $this->addItem($v);
       }
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
			$condition .= ' AND ' . $binary . ' fieldname like \'%'.$key.'%\'';
			$this->memberMyField->setSql()->where($condition);
			
		}
		if(isset($this->input['msid'])&&($msid = intval($this->input['msid']))&&$msid != -1)
		{
			$this->memberMyField->setJoin(' LEFT JOIN '.DB_PREFIX.'member_myFieldBind as mfb ON myf.mfid = mfb.mfid');
			$msid && $this->memberMyField->setParams('msid',$msid,'mfb');			
		}
		
		if(isset($this->input['issearch'])&&($issearch = intval($this->input['issearch'])) != -1)
		{
			$this->memberMyField->setParams('issearch',$issearch,'myf');
		}
		
		if(isset($this->input['is_unique'])&&($is_unique = intval($this->input['is_unique'])) != -1)
		{
			$this->memberMyField->setParams('isunique',$is_unique,'myf');
		}
		
		if(isset($this->input['addstatus'])&&($addstatus = intval($this->input['addstatus'])) != -1)
		{
			$this->memberMyField->setParams('addstatus',$addstatus,'myf');
		}
		
		if(isset($this->input['isrequired'])&&($isrequired = intval($this->input['isrequired'])) != -1)
		{
			$this->memberMyField->setParams('isrequired',$isrequired,'myf');
		}
		
		if(isset($this->input['isrequired'])&&($isrequired = intval($this->input['isrequired'])) != -1)
		{
			$this->memberMyField->setParams('isrequired',$isrequired,'myf');
		}
		
		if($this->input['user_name'])
		{
			$this->memberMyField->setParams('user_name',trim($this->input['user_name']),'myf');
		}
		
		if($this->input['fieldmark'])
		{
			$this->memberMyField->setParams('fieldmark',trim($this->input['fieldmark']),'myf');
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition = " AND myf.update_time >= ".$start_time;
			$this->memberMyField->setSql()->where($condition);
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition = " AND myf.update_time <= ".$end_time;
			$this->memberMyField->setSql()->where($condition);
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
					$condition = " AND myf.update_time > '".$yesterday."' AND myf.update_time < '".$today."'";
					$this->memberMyField->setSql()->where($condition);
					break;
				case 3://今天的数据
					$condition = " AND myf.update_time > '".$today."' AND myf.update_time < '".$tomorrow."'";
					$this->memberMyField->setSql()->where($condition);
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition = " AND myf.update_time > '".$last_threeday."' AND myf.update_time < '".$tomorrow."'";
					$this->memberMyField->setSql()->where($condition);
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition = " AND myf.update_time > '".$last_sevenday."' AND myf.update_time < '".$tomorrow."'";
					$this->memberMyField->setSql()->where($condition);
					break;
				default://所有时间段
					break;
			}
		}		
		$this->memberMyField->setAs('myf');
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new member_myFieldAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>