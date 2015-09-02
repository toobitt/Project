<?php
define('MOD_UNIQUEID','member_invite_set');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_invite_set.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_inviteUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->inviteset = new inviteset();
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

		$info=$this->inviteset->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if ($data)
		{
			$result = $this->membersql->update('invite_set', $data, array('id' => intval($id)));
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
		$data = $this->inviteset->display($ids,$opened);
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
		$invitedaddcredit=array();
		$inviteaddcredit=array();
		$invitedaddcredit['is_addcredit'] = isset($this->input['is_invitedaddcredit']) ? $this->input['is_invitedaddcredit'] : 0;
		$invitedaddcredit_base = isset($this->input['invitedaddcredit_base']) ? $this->input['invitedaddcredit_base'] :array();
		$inviteaddcredit['is_addcredit'] = isset($this->input['is_inviteaddcredit']) ? $this->input['is_inviteaddcredit'] : 0;
		$inviteaddcredit_base = isset($this->input['inviteaddcredit_base']) ? $this->input['inviteaddcredit_base'] : array();
		$invite_endtime = $this->input['is_invite_endtime'] ? (isset($this->input['invite_endtime'])?intval($this->input['invite_endtime']):0) : 0;
		$credit_field=$this->Members->get_credit_type_field();
		$new_credits=array();
			if($invitedaddcredit_base&&is_array($invitedaddcredit_base))//邀请人基础奖励
			{
				foreach ($invitedaddcredit_base as $k => $v)
				{
					if(in_array($k, $credit_field))
					{
						$invitedaddcredit['base'][$k]=intval($v);
					}
				}
			}
			if($inviteaddcredit_base&&is_array($inviteaddcredit_base))//被邀请人基础奖励
			{
				foreach ($inviteaddcredit_base as $k => $v)
				{
					if(in_array($k, $credit_field))
					{
						$inviteaddcredit['base'][$k]=intval($v);
					}
				}
			}
		$data = array(
		'invite_endtime'=>$invite_endtime,
		'inviteaddcredit'=>maybe_serialize($inviteaddcredit),
		'invitedaddcredit'=>maybe_serialize($invitedaddcredit),

		);
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_inviteUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>