<?php
define('MOD_UNIQUEID','member_sign_set');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_sign_set.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_signUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->signset = new signset();
		$this->Members=new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{

	}

	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		$info=$this->signset->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if ($data)
		{
			$result = $this->membersql->update('sign_set', $data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}

	//开关
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$opened = intval($this->input['is_on']);
		$opened = ($opened ==1) ? $opened : 0;
		$data = $this->signset->display($ids,$opened);
		$this->addItem($data);
		$this->output();
	}


	/**
	 * 删除
	 */
	public function delete()
	{

	}

	public function audit()
	{
		//
	}
	public function sort()
	{
	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$is_lastedop = isset($this->input['is_lastedop']) ? intval($this->input['is_lastedop']) : 0;
		$is_qdxq = isset($this->input['is_qdxq']) ? intval($this->input['is_qdxq']) : 0;
		$is_todaysay = isset($this->input['is_todaysay']) ? intval($this->input['is_todaysay']) : 0;
		$is_todaysayxt = isset($this->input['is_todaysayxt']) ? intval($this->input['is_todaysayxt']) : 0;
		$is_timeopen = isset($this->input['is_timeopen']) ? intval($this->input['is_timeopen']) : 0;		
		$lastedop   =  0;
		$clsmsg = isset($this->input['clsmsg']) ? trim(urldecode($this->input['clsmsg'])) : '';
		$notice = isset($this->input['notice']) ? trim(urldecode($this->input['notice'])) : '';
		$credits_base = isset($this->input['credits_base']) ? $this->input['credits_base'] : array();
		$credits_lastedop = isset($this->input['credits_lastedop']) ? $this->input['credits_lastedop'] : array();	
		$credits_final = isset($this->input['credits_final']) ? $this->input['credits_final'] : array();
		$limit_time = isset($this->input['limit_time']) ? $this->input['limit_time'] : array();
		$credit_field=$this->Members->get_credit_type_field();
		//$credits=array('base'=>array('credit1'=>'100','credit2'=>'50'),'lastedop'=>array('credit1'=>array('10','20','30','40'),'credit2'=>array('5','10','15','20')),'final'=>array('credit1'=>array('min'=>'100','max'=>'200'),'credit2'=>array('min'=>'50','max'=>'100')));
		$new_credits=array();
			$new_credits['base']=array();
			if($credits_base&&is_array($credits_base))//基础积分处理
			{
				foreach ($credits_base as $k => $v)
				{
					if(in_array($k, $credit_field))
					{
						$new_credits['base'][$k]=intval($v);
					}
				}
			}
			$new_credits['lastedop']=array();
			$new_credits['final']=array();
			if($is_lastedop&&$credits_lastedop&&is_array($credits_lastedop))//连续签到奖励积分处理
			{
				foreach ($credits_lastedop as $k => $v)//k为积分类型db_field;v为该类型下的数据;
				{
					$goto=true;//是否进入处理
					if(!in_array($k, $credit_field))
					{
						$goto=false;//告知此数据已无效
					}
					if($v&&is_array($v)&&$goto)
					{
						$day=0;
						foreach ($v as $kk => $vv)//kk为第几天(实际提交过来的为0起始下标);vv为分数
						{	
						$day=$kk+1;
						$new_credits['lastedop'][$day][$k]=intval($vv);
						}
					}
				}
				$lastedop=count($new_credits['lastedop']);
				//最终天数处理
				if($credits_final&&is_array($credits_final))
				{
					foreach ($credits_final as $k => $v)
					{
						if(in_array($k, $credit_field))
						{
							$min=intval($v['min']);
							$new_credits['final'][$k]['min']=$min;
							$max=intval($v['max']);
							$new_credits['final'][$k]['max']=$max;
						}
					}
				}
			}
		$data = array(
		'is_lastedop'=>$is_lastedop,
		'is_qdxq'=>$is_qdxq,
		'is_todaysay'=>$is_todaysay,
		'is_todaysayxt'=>$is_todaysayxt,
		'is_timeopen'=>$is_timeopen,
		'lastedop'=>$lastedop,
		'notice'=>$notice,
		'clsmsg'=>$clsmsg,
		'limit_time'=>maybe_serialize($limit_time),
		'credits'    => maybe_serialize($new_credits),
		);
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_signUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>