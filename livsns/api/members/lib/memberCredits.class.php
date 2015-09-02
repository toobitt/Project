<?php 
class memberCredits extends publicCore
{
	public function __construct()
	{
		parent::__construct();
		parent::newSql();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition)
	{
		parent::getSql()->where($condition);
		parent::getSql()->setTable('member_count');
		parent::getSql()->setKey('u_id');
		return parent::getSql()->show();	
	}
	public function MemberSshow($condition = '')
	{
		$member_id = array();
		$info = $this->show($condition);
		$info && is_array($info) && $member_id = array_keys($info);
		$memberInfo = members::get_member_name($member_id);
		foreach ($info as $uid => $credits)
		{
			if($member_name = $memberInfo[$uid])
			{ 
				$info[$uid]['member_name'] = (string)$member_name;
			}
			else {
				$info[$uid]['u_id'] = 0;
				$info[$uid]['member_name'] = '用户不存在或已被删除';
			}
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		parent::getSql()->where($condition);
		parent::getSql()->setTable('member_count');
		return parent::getSql()->count();
	}
}

?>