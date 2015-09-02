<?php
/*******************************************************************
 * filename :member_myData.php
 * 我的数据输出接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberMyData');//模块标识
require('./global.php');
class memberMyDataApi extends outerReadBase
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
	
	public function show()
	{
		try {
		$this->get_condition();
		$TParams = $this->memberMyData->setMark(trim($this->input['mark']));
		$this->memberMyData->setManyMdid(trim($this->input['manymdid']),false);
		$this->memberMyData->setParams('mdid');
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
		elseif($TParams == -6)
		{
			$this->errorOutput(NO_MARK_ERROR);
		}
		$this->addItem($this->memberMyData->paramProcess('show')->setFieldS('mdid,mydata')->setOffsetS((int)$this->input['offset'])->setCountS((int)$this->input['count'])->show()->selectDataFormatProcess()->outputProcess());
		$this->output();
	  }
	  catch (Exception $e)
	  {
	  		$this->errorOutput($e->getMessage(),$e->getCode());
	  }
	}
	public function detail()
	{
		$this->get_condition();
		if(($TParams = $this->memberMyData->setMdid((int)$this->input['mdid'])))
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
		if(!$this->memberMyData->checkMeData(1))
		{
			$this->errorOutput(NOT_SELECT_OTHERS_DATA);
		}
		$this->addItem($this->memberMyData->paramProcess('detail')->detail()->selectDataFormatProcess()->outputProcess());
		$this->output();
	}
	public function count()
	{
		$this->get_condition();
		$TParams = $this->memberMyData->setMark(trim($this->input['mark']));
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
		elseif($TParams == -6)
		{
			$this->errorOutput(NO_MARK_ERROR);
		}
		$this->addItem_withkey('total', $this->memberMyData->paramProcess('count')->count());
		$this->output();
	}
	
	/**
	 *
	 * 获取需要的条件
	 */
	private function get_condition()
	{
		$TParams = $this->memberMyData->setMemberId($this->user['user_id']);
		if ($TParams == -1)
		{
			$this->errorOutput(NO_MEMBER);
		}
		elseif ($TParams ==-2)
		{
			$this->errorOutput(NO_MEMBER_ID);
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

$out = new memberMyDataApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>