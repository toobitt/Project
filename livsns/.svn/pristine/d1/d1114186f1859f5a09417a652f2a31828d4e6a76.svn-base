<?php
/*******************************************************************
 * filename :member_medal_update.php
 * 申请勋章
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','memberMy');//模块标识
require('./global.php');
class memberMyUpdateApi extends appCommonFrm
{
	private $member_id;//会员id
	private $memberMy;
	private $mark;//数据类型标识
	private $math = array();//算术操作符
	private $total;//数据数量
	private $retData = array();

	public function __construct()
	{
		parent::__construct();
		$this->memberMy = new memberMy();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$this->get_condition();//获取必要参数处理
		$this->dataProcess();//数据处理
		$this->memberMy->cache($this->member_id);//冗余处理,更新至主表字段
		$this->outProcess();//输出更新状态
	}

	private function dataProcess()
	{
		$data = array();
		$param = array(
			'member_id'=>$this->member_id,
			'mark'		=>$this->mark,
		);
		$data = $param;
		$data['total'] = $this->total;
		$data['totalsum'] = $this->totalsum;
		if($this->memberMy->verify($param))
		{
			$this->retData = $this->memberMy->update($data, $param, (bool)$this->math, $this->math);
		}
		else{
			$this->retData = $this->memberMy->create($data);
		}
	}
	private function outProcess()
	{
		if(($this->retData))
		{
			$this->addItem_withkey('status', 1);
			$this->addItem_withkey('response', '更新成功');
		}
		else
		{
			$this->addItem_withkey('status', 0);
			$this->addItem_withkey('response', '更新失败');
		}
		$this->output();
	}
	/**
	 *
	 * 获取需要的条件
	 */
	private function get_condition()
	{
		$Members = new members();
		if($this->user['user_id']||$this->input['member_id'])
		{
			$this->member_id = $this->input['member_id']?intval($this->input['member_id']):($this->user['user_id']?$this->user['user_id']:0);
			if(!$Members->checkuser($this->member_id))
			{
				$this->errorOutput(NO_MEMBER);
			}
		}
		elseif(!$this->member_id)
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		if($this->input['mark'])
		{
			$this->mark = trim($this->input['mark']);
			$memberMySet = new memberMySet();
			if(!$memberMySet->count(array('mark'=>$this->mark)))
			{
				$this->errorOutput(MARK_ERROR);
			}
		}
		else {
			$this->errorOutput(NO_MARK_ERROR);
		}
		if($this->input['total'])//新数量
		{
			$this->total = trim($this->input['total']);
			if(!is_numeric($this->total))
			{
				$this->errorOutput(TOTAL_ERROR);
			}
			$this->total = (int)$this->total;
		}

		if($this->input['math'])
		{
			if(intval($this->input['math'])==1)
			{
				$this->math[total] = '+';
			}
			elseif (intval($this->input['math'])==2)
			{
				$this->math[total] = '-';
			}
		}

		if($this->input['totalsum'])//总数量
		{
			$this->totalsum = trim($this->input['totalsum']);
			if(!is_numeric($this->totalsum))
			{
				$this->errorOutput(TOTAL_ERROR);
			}
			$this->totalsum = (int)$this->totalsum;
		}

		if(intval($this->input['summath'])==1)
		{
			$this->math[totalsum] = '+';
		}
		elseif (intval($this->input['summath'])==2)
		{
			$this->math[totalsum] = '-';
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

$out = new memberMyUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>