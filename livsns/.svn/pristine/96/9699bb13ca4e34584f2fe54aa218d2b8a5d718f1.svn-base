<?php
/***************************************************************************

* $Id: member.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class memberSpread extends classCore
{
	private $membersql;
	private $memberId = 0;
	private $selectData = array();
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function verify($param)
	{
		return $this->membersql->verify('spread_record',$param);
	}
	
	public function create($data)
	{
		return $this->membersql->create('spread_record', $data);
	}
	
	public function detail()
	{
		$idsArr = array('fuid' => $this->memberId);
		$this->membersql->setTable('spread_record');
		$this->membersql->where($idsArr);
		$this->membersql->setDataFormat(array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
		$this->membersql->detail(array(),'',array(),$this->selectData);
		return $this;
	}
	
	public static function getMemberIdToSpreadCode($memberIds)
	{
		$idsArr = array('fuid' => $memberIds);
		$membersql = new membersql();
		$membersql->setTable('spread_record');
		$membersql->where($idsArr);
		$membersql->setSelectField('fuid,spreadcode');
		$membersql->setType(4);
		$membersql->setKey('fuid');
		$membersql->setOtherKey('spreadcode');
		$row = $membersql->show();
		return $row;
	}
	public function outputData($key)
	{
		$output = array();
		$output = $key&&$this->selectData ? $this->selectData[$key] : $this->selectData;
		return array(
		'status' => $output?1:0,
		'data'	=> $output,
		);
	}
	public function setMemberId($_memberId,$isMust = 1)
	{
		$Members = new members();
		if($_memberId)
		{
			$this->memberId = $_memberId?(int)$_memberId:0;
			if(!$Members->checkuser($this->memberId))
			{
				throw new Exception(NO_MEMBER, 200);
			}
		}
		elseif($isMust)
		{
			throw new Exception (NO_MEMBER_ID,200);
		}
	}
}

?>