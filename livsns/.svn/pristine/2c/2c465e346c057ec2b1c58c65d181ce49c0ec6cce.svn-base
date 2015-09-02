<?php
/***************************************************************************

* $Id: member.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class invite extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members = new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function insert($setarr)
	{
		if($setarr&&is_array($setarr))
		{
			$this->membersql->create('member_invite', $setarr);
			return true;
		}
		return false;
	}
	/**
	 *
	 * 查询邀请表
	 * @param array OR string $_condition 查询条件
	 * @param bol $isRow 是否只返回第一条数据
	 * @param String $field 需要查询的字段
	 */
	function select($_condition='',$isRow = true,$field = '*',$extend_sql = '',$id = '',$process = '1')
	{
		$condition = '';
		$condition = $this->get_condition($_condition);
		if($condition)
		{
			$sql='SELECT '.$field.' FROM '.DB_PREFIX.'member_invite mi '.$extend_sql.' WHERE 1 '.$condition;			
			$query = $this->db->query($sql);			
			return $this->{select_process.$process}($query,$isRow,$id);
		}
		return false;
	}

	private function select_process1($query,$isRow,$id)
	{
		$ret = array();
		while($row = $this->db->fetch_array($query))
		{
			if($id){
				$key = $row[$id];
				unset($row[$id]);
			}
			$id ? $ret[$key] = $row : $ret[]  = $row;
			if($isRow){
				return $row;
			}
		}		
		return $ret;
	}
	
	private function select_process2($query,$isRow,$id)
	{		
		$ret = array();
		$is_first = true;
		$first_key = '';
		while($row = $this->db->fetch_array($query))
		{
			if($id){
				$key = $row[$id];
				unset($row[$id]);
			}
			$ret[$key][] = $row;
			if($isRow&&$is_first)//为了控制只取第一行数据
			{
				$first_key = $key;
				$is_first = false;
			}
		}
		return $isRow?$ret[$first_key]:$ret;
	}
	
	private function select_process3($query,$isRow,$id)
	{
		$ret = array();
		while($row = $this->db->fetch_array($query))
		{
		  if($id){
				$ret[]  = $row[$id];
			}
			else {
				break;
			}
		}
		return $ret;
	}
	private function get_condition($_condition)
	{
		$condition = '';
		if ($_condition&&is_array($_condition))//如果为数据则转换为正常的sql条件
		{
			foreach ($_condition as $key => $val)
			{
				if (is_string($val)&&(stripos($val, ',')!==false))
				{
					$condition .= ' AND mi.' . $key . ' in (\'' . $val . '\')';
				}
				elseif (is_array($val))
				{
					$condition .= ' AND mi.' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
				elseif(is_string($val)||is_int($val) || is_float($val)||is_numeric($val))
				{
					$condition .= ' AND mi.' . $key . ' = \'' . $val . '\'';
				}
			}
		}
		elseif($_condition)//如果为字符串则直接当作sql条件
		{
			$condition = $_condition;
		}
		return $condition;
	}
	/**
	 *
	 * 通过被邀请人获取邀请人信息
	 */
	function select_fuid_to_uid($fuid,$isRow = true)
	{
		if(($fuid&&is_string($fuid)&&(stripos($fuid, ',')!==false))||is_numeric($fuid)&&$fuid>0&&!is_array($fuid))
		{
			$fuid = explode(',', trim($fuid));
		}
		if ($fuid&&is_array($fuid))
		{
			$fuid=array_filter($fuid,"clean_array_null");
			$fuid=array_filter($fuid,"clean_array_num_max0");
			$fuid=trim(implode(',', $fuid));
			if(is_string($fuid)&&(stripos($fuid, ',')!==false)&&$fuid)
			{
				$isRow = false;
				$where=' AND mi.fuid IN('.$fuid.')';
			}
			else $where=' AND mi.fuid = '.$fuid;
		}
		elseif(empty($fuid)) {
			return false;
		}
		$extend_sql = ' LEFT JOIN '.DB_PREFIX.'member m ON m.member_id = mi.member_id';
		return $this->select($where,$isRow,'m.member_name,mi.fuid',$extend_sql,'fuid');

	}

	/**
	 *
	 * 通过邀请人获取被邀请人信息
	 */
	function select_uid_to_fuid($uid,$field = 'm.member_name,mi.member_id',$id = 'member_id',$process = '2',$isExtend = true,$extend_sql = '')
	{
		$isRow = true;
		if(($uid&&is_string($uid)&&(stripos($uid, ',')!==false))||is_numeric($uid)&&$uid>0&&!is_array($uid))
		{
			$uid = explode(',', trim($uid));
		}
		if ($uid&&is_array($uid))
		{
			$uid=array_filter($uid,"clean_array_null");
			$uid=array_filter($uid,"clean_array_num_max0");
			$uid=trim(implode(',', $uid));
			if(is_string($uid)&&(stripos($uid, ',')!==false)&&$uid)
			{
				$isRow = false;
				$where=' AND mi.member_id IN('.$uid.')';
			}
			else $where=' AND mi.member_id = '.$uid;
		}
		elseif(empty($uid)) {
			return false;
		}
		if($isExtend)
		{
			$extend_sql = ' LEFT JOIN '.DB_PREFIX.'member m ON m.member_id = mi.fuid';
		}
		return $this->select($where,$isRow,$field,$extend_sql,$id,$process);
	}

	/**
	 *
	 * 通过邀请人统计邀请成功人数
	 */
	function select_uid_to_count($uid)
	{
		$isRow = true;
		if(($uid&&is_string($uid)&&(stripos($uid, ',')!==false))||is_numeric($uid)&&$uid>0&&!is_array($uid))
		{
			$uid = explode(',', trim($uid));
		}
		if ($uid&&is_array($uid))
		{
			$uid=array_filter($uid,"clean_array_null");
			$uid=array_filter($uid,"clean_array_num_max0");
			$uid=trim(implode(',', $uid));
			if(is_string($uid)&&(stripos($uid, ',')!==false)&&$uid)
			{
				$isRow = false;
				$where=' AND mi.member_id IN('.$uid.')';
			}
			else $where=' AND mi.member_id = '.$uid;
			$where .= ' AND mi.status = 2 GROUP BY member_id';
		}
		elseif(empty($uid)) {
			return false;
		}
		$extend_sql = '';
		return $this->select($where,$isRow,'member_id,count(member_id) as total',$extend_sql,'member_id');
	}

	/**
	 *
	 * 删除掉已经过期
	 */
	function delete($id)
	{
		if($id)
		{
			$this->membersql->delete('member_invite', array('id'=>$id));
			return true;
		}
		return false;
	}

	function update($setarr,$condition)
	{
		if($setarr&&is_array($setarr))
		{
			$this->membersql->update('member_invite', $setarr,$condition);
			return true;
		}
		return false;
	}
	/**
	 * 邀请规则处理
	 */
	function invite_rules($member_id,$code,$id=0)
	{
		if (empty($member_id))
		{
			return array('status'=>0,'msg'=>array('ErrorCode'=>'0x0005','ErrorText'=>'会员id不能为空'));//会员id不正确
		}
		$member_invite=$this->get_setting();

		if(empty($member_invite['is_on']))
		{
			return array('status'=>-1,'msg'=>array('ErrorCode'=>'0x0111','ErrorText'=>'抱歉，本站目前暂时不允许用户通过邀请注册'));//邀请功能未开启
		}

		$member_info=$this->Members->get_member_info(' AND member_id='.$member_id,'member_name,member_id');//检测被邀请人是否注册成功.

		if(!$member_info)//会员不存在
		{
			return array('status'=>-2,'msg'=>array('ErrorCode'=>'0x0006','ErrorText'=>'会员不存在,请注册后在来拿奖励哦'));
		}
		$no_code=false;
		if(empty($code))
		{
			$no_code=true;
		}
		else {
			if($id)
			{
				$condition=' AND mi.id = \''.$id.'\'';
			}
			elseif ($code)
			{
				$condition=' AND mi.code = \''.$code.'\'';
			}
			$invite=$this->select($condition);
		}
		if($no_code||empty($invite)){
			return array('status'=>-3,'msg'=>array('ErrorCode'=>'0x0108','ErrorText'=>'邀请码不存在'));
		}
		if($invite['fuid'] && $invite['fuid'] != $member_id)
		{
			return array('status'=>-4,'msg'=>array('ErrorCode'=>'0x0109','ErrorText'=>'邀请码已被使用'));
		}
		if($invite['endtime'] && TIMENOW > $invite['endtime'])
		{
			$this->delete($invite['id']);
			return array('status'=>-5,'msg'=>array('ErrorCode'=>'0x0110','ErrorText'=>'邀请码已过期'));
		}
		if($invite['id']) {
			$this->update( array('fuid'=>$member_info['member_id'], 'fusername'=>$member_info['member_name'], 'regdateline' => TIMENOW, 'status' => 2),array('id'=>$invite['id']));
		}
		$this->add_credit($member_info['member_id'],$member_invite['inviteaddcredit'],0);//奖励被邀请人
		if($invite['member_id'])//防止为系统销售,不需要增加邀请人积分
		{
			$this->add_credit($invite['member_id'],$member_invite['invitedaddcredit'],1);//奖励邀请人
		}
		return true;
	}
	/**
	 *
	 * 积分增加函数 ...
	 * @param int $uid
	 * @param array $credits 积分数
	 * @param bol $is_d  区别被邀请用户和邀请用户
	 */
	function add_credit($uid,$credits,$is_d)
	{
		$credit_type=array();
		$credit_type=$this->Members->get_credit_type_field();//获取已启用的积分类型
		if(empty($credit_type)||empty($credits['is_addcredit']))//未设置可用奖励积分或者需要不增加积分.
		{
			return false;
		}
		unset($credits['is_addcredit']);
		if($credits&&is_array($credits))
		{
			$credit_d=array();
			foreach ($credits as $k => $v)
			{
				if($k=='base'&&is_array($v)&&$v)
				{
					foreach ($v as $kk=>$vv)
					{
						if(in_array($kk, $credit_type))
						{
							$credit_num=intval($vv);
							$new_credits[$kk] =$credit_num;
						}
					}
				}
			}
			$credit_log=array(
		'app_uniqueid'=>APP_UNIQUEID,
		'mod_uniqueid'=>'member_invite',
		'method'=>'invite_user',
		'relatedid'=>$uid,
		'title'=>'邀请注册',
		'remark'=>$is_d?'邀请好友注册赠送':'被好友邀请注册赠送',
			);
			$this->Members->credits($new_credits,$uid,$coef=1,true,true,true,null,array(),$credit_log);
			return true;
		}
		return false;
	}

	/**
	 *
	 * 获取应用配置值
	 */
	function get_setting($field='*')
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'invite_set WHERE id=1';
		$setting=$this->db->query_first($sql);
		if($setting['inviteaddcredit'])
		{
			$setting['inviteaddcredit']=maybe_unserialize($setting['inviteaddcredit']);
		}
		if($setting['invitedaddcredit'])
		{
			$setting['invitedaddcredit']=maybe_unserialize($setting['invitedaddcredit']);
		}
		return $setting;
	}
}

?>