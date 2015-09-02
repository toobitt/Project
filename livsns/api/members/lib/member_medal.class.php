<?php
class medal extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * 统计勋章颁发数量
	 * @param array $ids 勋章id
	 * @param string $math 算数操作符
	 * @param int $num 操作数量
	 */
	public function update_used_num($ids,$math='+',$num=1)
	{
		$where='';
		if ($ids&&is_array($ids))
		{
			$ids=array_filter($ids,"clean_array_null");
			$ids=array_filter($ids,"clean_array_num");
			$ids=trim(implode(',', $ids));
			if(is_string($ids)&&(stripos($ids, ',')!==false))
			{
				 $where=' AND id IN( '.$ids.')';
			}
			else $where=' AND id = '.$ids;
		}
		else return false;
		$sql = 'UPDATE '.DB_PREFIX.'medal SET used_num = used_num'.$math.$num.' WHERE 1'.$where;
		return $this->db->query($sql);
	}
	/**
	 *
	 * 会员勋章修改
	 * @param int $member_id 会员id
	 * @param array $medalid 勋章id
	 */
	public function edit_member_medal($member_id,$medal_id)
	{
		$member_id=$this->Members->checkuser($member_id);
		if(empty($member_id))
		{
			return 0;
		}
		$medalsdel = $medalsadd = $medal_info = $member_medal = $newmedalid = $oldmedalid = array();
		$medal_id && $medal_info = $this->Members->get_medal($medal_id);
		if($medal_info&&is_array($medal_info))
		{
			$newmedalid = array_keys($medal_info);
		}
		$member_medal= $this->Members->get_member_medal(array($member_id),$field='*',0);
		if($member_medal&&is_array($member_medal))
		{
			$oldmedalid = array_keys($member_medal);
		}
		foreach(array_unique(array_merge($newmedalid, $oldmedalid)) as $medalid) {
			if($medalid) {
				$orig = in_array($medalid, $oldmedalid);
				$new = in_array($medalid, $newmedalid);
				if($orig != $new) {
					if($orig && !$new) {
						$medalsdel[] = $medalid;
					} elseif(!$orig && $new) {
						$medalsadd[] = $medalid;
					}
				}
			}
		}
		if(!empty($medal_info)) {
			$i=0;
			foreach($medal_info as $medalid=>$modmedal) {
				if(empty($modmedal['expiration'])) {
					$medalstatus = 0;
				} else {
					$modmedal['expiration'] = TIMENOW + $modmedal['expiration'] * 86400;
					$medalstatus = 1;
				}
				if(in_array($medalid, $medalsadd)) {
					$data = array(
						'member_id' => $member_id,
						'medalid' => $medalid,
						'type' => 0,
						'dateline' => TIMENOW,
						'expiration' => $modmedal['expiration'],
						'status' => $medalstatus,
					);
					$this->membersql->create('medallog', $data);
					$this->membersql->create('member_medal', array('member_id' => $member_id, 'medalid' => $medalid,'expiration'=>$modmedal['expiration']),false,$pk = 'id',true);
					$this->update_used_num(array($medalid));//增加勋章时增加勋章拥有数
				}
			}
		}
		if(!empty($medalsdel)) {
			$this->membersql->update('medallog', array('type'=>4), array('member_id'=>$member_id,'medalid'=>$medalsdel));
			$this->membersql->delete('member_medal', array('member_id'=>$member_id,'medalid'=>$medalsdel));
			$this->update_used_num($medalsdel,'-');//删除勋章时,减少勋章拥有数.
		}
	}
/**
 * 
 * 勋章审核函数 ...
 * @param int $ids_arr 需审核的勋章日志id
 * @param int $type 审核类型,通过为1,拒绝为0
 */
	public function audit_member_medal($ids_arr,$type)
	{
		if(empty($type))
		{
			if($ids_arr&&is_array($ids_arr))
			{
				$this->membersql->update('medallog', array('type' => 3), array('id'=>$ids_arr));
				$this->update_used_num($ids_arr,'-');//拒绝颁发减少勋章申请(拥有)数,因为申请时已经增加申请数
				return true;
			}
				return false;
		}
		elseif ($type==1)
		{
			if(is_array($ids_arr) && !empty($ids_arr)) {
				$sql = "SELECT id,member_id,medalid,dateline,expiration FROM ".DB_PREFIX."medallog
					WHERE id IN (".implode(',',$ids_arr).")";
				$query=$this->db->query($sql);
				while($modmedal = $this->db->fetch_array($query)) {
					$medalstatus = empty($modmedal['expiration']) ? 0 : 1;
					$modmedal['expiration'] = $modmedal['expiration'] ? (TIMENOW + $modmedal['expiration'] - $modmedal['dateline']) : '';
					$this->membersql->update('medallog', array('type' => 1, 'status' => $medalstatus, 'expiration' => $modmedal['expiration']), array('id'=>$modmedal['id']));
					$this->membersql->create('member_medal', array('member_id' => $modmedal['member_id'], 'medalid' => $modmedal['medalid'],'expiration' =>$modmedal['expiration']),$pk='id',true);
				}
				return true;

			}
			return false;
		}
	}
	
/** 
 * 删除会员勋章函数 ...
 * @param string $member_id 会员id
 */
	public function del_member_medal($member_id)
	{
		if($member_id&&is_string($member_id))
		{
			$member_id_arr = explode(',', $member_id);
			$member_id_arr = array_filter($member_id_arr,"clean_array_null");
			$member_id_arr = array_filter($member_id_arr,"clean_array_num");
			$m_medals_info = $this->Members->get_member_medal($member_id_arr,$field='*',2);
		}
		//会员勋章数据表
		$sql = "DELETE FROM " . DB_PREFIX . "member_medal WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);
		//勋章日志表
		$sql = "DELETE FROM " . DB_PREFIX . "medallog WHERE member_id IN (" . $member_id . ")";
		$this->db->query($sql);
		$this->update_used_num(@array_keys($m_medals_info),'-');
		return true;
	}
}

?>