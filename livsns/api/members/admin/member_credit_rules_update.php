<?php
define('MOD_UNIQUEID','member_credit_rules');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
class membercreditrulesUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->creditrules = new creditrules();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 *新增规则
	 * @see adminUpdateBase::create()
	 */
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证标识是否重复
		$checkResult = $this->membersql->verify('credit_rules',array('operation' => $data['operation']));
		if ($checkResult) $this->errorOutput('操作标识重复');
		if($data)
		{
			$result = $this->membersql->create('credit_rules', $data , true);
		}
		$this->addItem($result);
		$this->output();
	}
	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		$info=$this->creditrules->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if (($data['operation'] != $info['operation']) && $data['operation'])  //禁止修改目标识
		{
			$this->errorOutput('禁止修改标识');
		}
		if((empty($data['iscustom'])&&$info['iscustom']))
		{
			$this->creditrules->creditrules_diy_unset($info['operation'],$info['gids'],$info['appids']);
		}
		if ($data)
		{
			$result = $this->membersql->update('credit_rules', $data, array('id' => intval($id)));
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
		$data = $this->creditrules->display($ids,$opened);
		$this->addItem($data);
		$this->output();
	}

	/**
	 * 删除
	 */
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$data = $this->creditrules->delete($ids);
		if($data == -1){
			$this->errorOutput('系统内置积分规则禁止删除');
		}
		$this->addItem($data);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改积分规则排序', '', '', '更改积分规则排序');
		$ret = $this->drag_order('credit_rules', 'order_id');
		$this->addItem($ret);
		$this->output();
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
		$rname = isset($this->input['rname']) ? trim(urldecode($this->input['rname'])) : '';
		$operation = isset($this->input['operation']) ? urldecode($this->input['operation']) : '';
		$cyclelevel = isset($this->input['cyclelevel']) ? intval($this->input['cyclelevel']) : 0;
		$cycletype = isset($this->input['cycletype']) ? intval($this->input['cycletype']) : 0;
		$cycletime=isset($this->input['cycletime']) ? intval($this->input['cycletime']) : 0;
		$rewardnum=isset($this->input['rewardnum']) ? intval($this->input['rewardnum']) : 0;
		$credits=isset($this->input['credits']) ? $this->input['credits'] : array();
		$iscustom=isset($this->input['iscustom']) ? intval($this->input['iscustom']) : 0;
		if (empty($rname)) {
			$this->errorOutput('积分规则名称不能为空');
		}
		if(empty($operation)){
			$this->errorOutput('操作标识禁止为空');
		}
		if(!$cycletype)//如果为一次类型，则生效次数为1，间隔时间为0
		{
			$cycletime = 0;
			$rewardnum = 1;
		}
		else if(($cycletype==1||$cycletype==4)&&$cycletime)//周期为每天或者不限类型,间隔时间为0
		{
			$cycletime = 0;
		}
		if($rewardnum<0)
		{
			$this->errorOutput('奖励次数不允许小于0');
		}
		if($cycletime<0)
		{
			$this->errorOutput('间隔时间不允许小于0');
		}
		$data = array(
			'rname'    => $rname,
			'operation'=> $this->checkFieldFormat($operation),
			'cyclelevel'=>$cyclelevel,
			'cycletype' => $cycletype,
			'cycletime'=>$cycletime,
			'rewardnum'=>$rewardnum,
			'iscustom' => $iscustom
		);
		if(is_array($credits))//处理积分.
		{
			foreach ($credits as $k => $v)
			{
				$data[$k]=intval($v);
			}
		}
		return $data;
	}
	
	private function checkFieldFormat($field)
	{
		if (strpos($field," ")!==false)
		{
			$this->errorOutput('标识禁止含有空格');
		}
		else if (! CheckLengthBetween($field,0,80))
		{
			$this->errorOutput('标识长度最大为80个字符');
		}
		else if(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$field))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		elseif(is_numeric($field))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $field))
		{
			$this->errorOutput('标识禁止使用或者含有汉字');
		}
		return $field;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new membercreditrulesUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>