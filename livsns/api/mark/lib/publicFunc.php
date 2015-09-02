<?php
class publicFunc extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//获取action_set的分类数据
	public function getSet($acd,$bcd)
	{
		$sql = "select ".$acd." from ".DB_PREFIX."mark_action_setting where 1 and isdel=0".$bcd;
		$kindArr = $this->db->fetch_all($sql);
		return $kindArr;
	}
	
	//获取kind的分类数据
	public function getkind($acd, $bcd)
	{
		$sql = "select ".$acd." from ".DB_PREFIX."mark_kind where 1 ".$bcd;//print_r($sql);exit;
		$kindArr = $this->db->fetch_all($sql);
		return $kindArr;
	}
	
	//检查kind分类是否存在
	public function checkKindExit($acd)
	{
		$stu = false;
		$sql = "select kind_id from ".DB_PREFIX."mark_kind where 1  ".$acd;
		$stu = $this->db->result_first($sql);
		return $stu;

	}
	
	//检查mark是否存在
	public function checkMarkExit($acd)
	{
		$stu = false;
		$sql = "select mark_id from ".DB_PREFIX."mark where 1  ".$acd;
		$stu = $this->db->result_first($sql);
		return $stu;
	
	}
	
	//检查action_set是否存在
	public function checkSetExit($acd)
	{
		$stu = false;
		$sql = "select action_id from ".DB_PREFIX."mark_action_setting where 1 and isdel=0 and ".$acd;
		$stu = $this->db->result_first($sql);
		return $stu;
	}
	
	public function insertMarkKind($avalue)
	{
		$sql =" insert into ".DB_PREFIX."mark_kind set ";//(`kind_name`, `kind_num`, `active`, `type`, `user_id`, `isdel`) VALUES (".$avalue.")";
		$sqla = $spce = '';
		foreach($avalue as $k=>$v)
		{
			$sqla .= $spce.$k."='".$v."'";
			$spce = ',';
		}
		$this->db->query($sql.$sqla);
		return $this->db->insert_id();
	}
	
	public function delMarkKind($avalue)
	{
		$sql = "DELETE FROM `".DB_PREFIX."mark_kind` WHERE 1 ".$avalue;//print_r($sql);exit;
		return $this->db->query($sql);
	}
	
	public function updateMarkKind($akey,$avalue)
	{
		$sql = "UPDATE `".DB_PREFIX."mark_kind` SET ".$akey." where 1 ".$avalue;//print_r($sql);exit;
		return $this->db->query($sql);
	}
	
	public function insertMarkActionSet($avalue)
	{
		$sql =" insert into ".DB_PREFIX."mark_action_setting (`action_name`,`isdel`) VALUES (".$avalue.")";
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function updateMarkActionSet($akey,$avalue)
	{
		$sql = "UPDATE `".DB_PREFIX."mark_action_setting` SET ".$akey." where 1 ".$avalue;//print_r($sql);exit;
		return $this->db->query($sql) ;
	}
	public function getMarkActionSign($akey)
	{
		$sql = '';
		$sql = "select * from ".DB_PREFIX."mark_sign Where 1 ";
		$spce = ' and ';
		foreach ($akey as $k=>$v)
		{
			$sql .= $spce.$k."='".$v."'";
		}
		return $this->db->result_first($sql);
	}
	public function insertMarkActionSign($akey)
	{
		$sql = '';
		$sql = "insert into ".DB_PREFIX."mark_sign SET ";
		$spce = '';
		foreach ($akey as $k=>$v)
		{
			$sql .= $spce.$k."='".$v."'";
			$spce = ',';
		}
		return $this->db->query($sql);
	}
	public function updateMarkActionSign($akey,$val,$num=true)
	{
		$sql = '';
		$sql ="UPDATE `".DB_PREFIX."mark_sign` SET ";
		$spce = '';
		foreach ($akey as $k=>$v)
		{
			$sql .= $spce.$k."=";
			$sql .= $num ? "" :"'";
			$sql .= $v;
			$sql .= $num ? "" :"'";
			$spce = ',';
		}
		$sql .= " WHERE 1 ";
		foreach ($val as $k=>$v)
		{
			$sql .= ' and '.$k."='".$v."'";
		}print_r($sql);
		return $this->db->query($sql);
	}
	public function delMarkActionSign($avalue)
	{
		$sql ="DELETE FROM  `".DB_PREFIX."mark_sign`  WHERE 1 ".$avalue;
		return $this->db->query($sql);
	}
	public function insertMark($avalue)
	{
		$sql = "INSERT INTO `".DB_PREFIX."mark` set ";//(`mark_name`,  `kind_id`, `active`) VALUES (".$avalue.")";
		$sqla = $spce = '';
		foreach($avalue as $k=>$v)
		{
			$sqla .= $spce.$k."='".$v."'";
			$spce = ',';
		}
		$this->db->query($sql.$sqla);
		return $this->db->insert_id();
	}
	public function updateMark($kind_id, $mark_id)
	{
		$sql = " update `".DB_PREFIX."mark` set ".$kind_id." where 1 ". $mark_id;
		return $this->db->query($sql);
	}
	public function getMark($akey,$val,$data_limit)
	{
	 	$sql = "select ".$akey." from `".DB_PREFIX."mark` where 1 ".$val.$data_limit;
 		return $this->db->fetch_all($sql);
	}
	public function delMark($val)
	{
		$sql = "DELETE FROM   `".DB_PREFIX."mark` where 1 ".$val;
		return $this->db->query($sql);
	}
	public function addMarkAction($val)
	{
		$sql = "INSERT INTO `".DB_PREFIX."mark_action` set ";
		$sqla = $spce = '';
		foreach($val as $k=>$v)
		{
			$sqla .= $spce.$k."='".$v."'";
			$spce = ',';
		}
		return $this->db->query($sql.$sqla);
	}
	public function delMarkAction($val)
	{
		$sql = "DELETE FROM `".DB_PREFIX."mark_action` WHERE 1 ";
		$sqla = '';$spce = ' and ';
		foreach ($val as $k=>$v)
		{
			$sqla .= $spce.$k."='".$v."'";
		}
		return $this->db->query($sql.$sqla);
	}
	public function getMarkAction($val)
	{
		$sql = "select * from `".DB_PREFIX."mark_action` where 1 ";
		$sqla = '';$spce = ' and ';
		foreach ($val as $k=>$v)
		{
			$sqla .= $spce.$k."='".$v."'";
		}
		return  $this->db->fetch_all($sql.$sqla);
	}
}
?>