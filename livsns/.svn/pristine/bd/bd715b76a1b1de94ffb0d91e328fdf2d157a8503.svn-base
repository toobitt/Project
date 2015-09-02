<?php
/*******************************************************************
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','identifierUserSystem');//模块标识
require('./global.php');
class identifierUserSystemAdmin extends adminReadBase
{
	private $identifierUserSystem;
	public function __construct()
	{
		parent::__construct();
		$this->identifierUserSystem = new identifierUserSystem();
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
			$this->identifierUserSystem->setDataFormat(array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i'),'update_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
			$reInfo = $this->identifierUserSystem->setLimit((int)$this->input['offset'],(int)$this->input['count'])->show();
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
		try {
			$this->identifierUserSystem->setParams('iusid',(int)$this->input['id']);
			$data = $this->identifierUserSystem->setFieldS('iusid,iusname,brief,opened')->detail();
		}
	    catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		try {
		 $this->get_condition();
		 $total = $this->identifierUserSystem->count();
		}
		catch (Exception $e)
    	{
    		$this->errorOutput($e->getMessage(),$e->getCode());
    	}
    	$this->addItem_withkey('total', $total);
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
			$condition .= ' AND ' . $binary . ' iusname like \'%'.$key.'%\'';
			$this->identifierUserSystem->setWhere($condition);
			
		}
		if(isset($this->input['identifier']) && $this->input['identifier']!=='')
		{
			$this->identifierUserSystem->setParams('identifier',(int)$this->input['identifier']);			
		}
		
		if(isset($this->input['opened'])&&$this->input['opened'] != -1)
		{
			$this->identifierUserSystem->setParams('opened',(int)$this->input['opened']);			
		}
		
		if($this->input['user_name'])
		{
			$this->identifierUserSystem->setParams('user_name',trim($this->input['user_name']));
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition = " AND update_time >= ".$start_time;
			$this->identifierUserSystem->setWhere($condition);
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition = " AND update_time <= ".$end_time;
			$this->identifierUserSystem->setWhere($condition);
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
					$condition = " AND update_time > '".$yesterday."' AND update_time < '".$today."'";
					$this->identifierUserSystem->setWhere($condition);
					break;
				case 3://今天的数据
					$condition = " AND update_time > '".$today."' AND update_time < '".$tomorrow."'";
					$this->identifierUserSystem->setWhere($condition);
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition = " AND update_time > '".$last_threeday."' AND update_time < '".$tomorrow."'";
					$this->identifierUserSystem->setWhere($condition);
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition = " AND update_time > '".$last_sevenday."' AND update_time < '".$tomorrow."'";
					$this->identifierUserSystem->setWhere($condition);
					break;
				default://所有时间段
					break;
			}
		}		
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new identifierUserSystemAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>