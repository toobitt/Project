<?php
class action extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	//检查文件名是否存在
	public function checkActivityExist($action_name, $act = '1')
	{
		$sql ="select id as action_id from " . DB_PREFIX . "activity where action_name ='".$action_name."' and ".$act;
		$re = $this->db->result_first($sql);
		return $re;
	}
	//检查类型是否存在
	public function checkActivityTypeExist($type_id = 0 ,$type_name = '')
	{
		$sql ="select id  from " . DB_PREFIX . "activity_type where 1 ";
		if($type_id)
		{
			$sql .= " AND id=".$type_id. " ";
		}
		if($type_name)
		{
			$sql .= " AND name='".$type_name. "' ";
		}
		$re = $this->db->result_first($sql);
		return $re;
	}
	//更新类型数目
	public function updateActivityTypeNum($type_id,$num = 1,$type = true)
	{
		$sql = '';
		$sql = "update " . DB_PREFIX . "activity_type set num=num";
		$sql .= $type ? "+" : "-";
		$sql .= $num ." where id=".$type_id;
		return $this->db->query($sql);
	}
	//生成活动信息
	public function create($data)
	{
		$sql = 'INSERT INTO ' . DB_PREFIX . 'activity SET ';
		$space = '';
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ',';
		}
		$this->db->query($sql);
		return  $this->db->insert_id();
	}
	//更新活动信息type:"+"增加/"-"减少/""不操作，act只增加，num数目
	public function update($data, $action_id, $act = true, $type = "")
	{
		$sql = $space = '';
		$sql = 'UPDATE ' . DB_PREFIX . 'activity SET ';
		
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=";
			$sql .= $act ? "" : $key;
			$sql .= $type;
			$sql .= "'".$value . "'";
			$space = ',';
		}
		$sql .= " where 1 and id=".$action_id;
		return  $this->db->query($sql);	
	}
	//更新活动信息
	public function updateStion($data,$action_id)
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'activity SET ';
		$space = '';
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ',';
		}
		$sql .= " where 1 and id=".$action_id;
		return  $this->db->query($sql);
	}
	//获取活动申请条件
	public function applyActivityLimit($action_id, $filds, $cond)
	{
		$ret = array();
		$sql = " select ".$filds." from " . DB_PREFIX . "activity where id =" . $action_id ." ".$cond;
		$ret = $this->db->fetch_all($sql);
		return $ret['0'];
	}
	//确定该用户是否已经申请过
	public function pastApplyActivity($action_id, $user_id)
	{
		$re = array();
		$sql = "select id,apply_status from ". DB_PREFIX . "activity_apply where 1 and is_del=0 and action_id=".
					$action_id." and user_id=".$user_id;
		$re = $this->db->fetch_all($sql);
		return $re['0'];
	}
	//用户申请
	public function applyActivity($data)
	{
		$sql = 'INSERT INTO ' . DB_PREFIX . 'activity_apply SET ';
		$space = '';
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ',';
		}
		$this->db->query($sql);
		return  $this->db->insert_id();
	}
	//获取活动的某项参数
	public function getActivityParams($params,$id)
	{
		$sql = "select ".$params." from ". DB_PREFIX . "activity where  id=".$id;
		$re = $this->db->fetch_all($sql);
		return $re['0'];
	}
	//更新数据审核信息
	public function updateApplyActivity($action_id, $acd, $new = 3)
	{
		$sql = "update ". DB_PREFIX . "activity_apply set apply_status =".$new ." where action_id=".$action_id." ".$acd;
		return $this->db->query($sql);
	}
	
	public function statusSetting($apply_status)
	{
		if($apply_status == 1)
		{
			$msg = '审核中';
		}
		elseif($apply_status == 3)
		{
			$msg = '审核未通过';
		}
		else
		{
			$msg = '已参与';
		}
		return $msg;
	}
}
?>