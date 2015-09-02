<?php 
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class CreditRulesDiy extends InitFrm
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

	public function show($condition,$offset = 0,$count = 0,$field='*',$isGetOther = true)
	{
		$limit = '';
		if($count){
			$limit 	 = " LIMIT " . $offset . " , " . $count;
		}
		$sql = "SELECT $field FROM " . DB_PREFIX . "credit_rules_custom_app ";
		$sql.= " WHERE 1 " . $condition.$limit;
		$q = $this->db->query($sql);
		$return = array();
		$appid =array();
		$operation = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['rules']&&$row['rules']=maybe_unserialize($row['rules']);
			$isGetOther&&$appid[] = $row['appid'];
			$isGetOther&&$operation[] = $row['operation'];
			$return[] = $row;
		}
		$appInfo = array();
		if($appid)
		$appInfo = $this->getApp($appid);
		$operationInfo = array();
		if($operation)
		$operationInfo = $this->Members->getcreditrule($operation,true);
		foreach ($return as $k => $v)
		{
			$appInfo&&$return[$k]['appname'] = $appInfo[$v['appid']]['appname'];
			$operationInfo&&$return[$k]['rulename'] = $operationInfo[$v['operation']]['rname'];
			$operationInfo&&$return[$k]['opened'] =   $operationInfo[$v['operation']]['opened'];
		}
		return $return;
	}
	
	public function getApp($appid = array())
	{
		return $this->Members->getApp(implode(',', $appid));
	}
	
	/**
	 * 
	 * 获取已经自定义积分规则的应用标识 ...
	 */
	public function getSetDiyRulesField($field)
	{
		$sql = "SELECT $field FROM " . DB_PREFIX . "credit_rules_custom_app";
		$q = $this->db->query($sql);
		$diyInfo = array();
		while ($row = $this->db->fetch_array($q))
		{
			$diyInfo[] = $row[$field];
		}
		return $diyInfo;
	}
	
	public function detail($id)
	{

		$condition = " WHERE id = " . $id;		
		$sql = "SELECT * FROM " . DB_PREFIX . "credit_rules_custom_app " . $condition;		
		$row = $this->db->query_first($sql);
		if(is_array($row) && $row)
		{
			$appInfo = $this->Members->getApp($row['appid']);
			$operationInfo = $this->Members->getcreditrule($row['operation'],true);
			$row['rules'] = maybe_unserialize($row['rules']);
			$row['appname'] = $appInfo[$row['appid']]['appname'];
			$row['rulename'] = $operationInfo[$row['operation']]['rname'];
			$row['opened'] = $operationInfo[$row['operation']]['opened'];
			if(isset($row['rules']['rname'])&&$row['rules']['rname'])
			{
				$row['isdiyname'] = 1;
			}else {
				$row['isdiyname'] = 0;
			}
			if(isset($row['rules']['opened']))
			{
				$row['isopened'] = 1;
			}else {
				$row['isopened'] = 0;
			}
			if(isset($row['rules']['cyclelevel'])&&$row['rules']['cyclelevel']>=0)
			{
				$row['isdiylevel'] = 1;
			}else {
				$row['isdiylevel'] = 0;
			}
			if(isset($row['rules']['cycletype'])&&$row['rules']['cycletype']>=0)
			{
				$row['isdiycycletype'] = 1;
			}else {
				$row['isdiycycletype'] = 0;
			}
			if(isset($row['rules']['credit1']))
			{
				$row['isdiycredit1'] = 1;
			}else {
				$row['isdiycredit1'] = 0;
			}
			if(isset($row['rules']['credit2']))
			{
				$row['isdiycredit2'] = 1;
			}else {
				$row['isdiycredit2'] = 0;
			}
			
			return $row;
		}
		return false;
	}
	
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "credit_rules_custom_app WHERE 1 " . $condition;
		return  $this->db->query_first($sql);
	}
	
	public function delete($id)
	{
		include CUR_CONF_PATH . 'lib/member_credit_rules.class.php';
		$memberCreditRules = new creditrules();
		$condition = '';
		if(($id&&is_string($id)&&(stripos($id, ',')!==false))||is_numeric($id)&&$id>0&&!is_array($id))
		{
			$id = explode(',', $id);
		}
		if ($id&&is_array($id))
		{
			$id=array_filter($id,"clean_array_null");
			$id=array_filter($id,"clean_array_num_max0");
			$id=trim(implode(',', $id));
			if(is_string($id)&&(stripos($id, ',')!==false)&&$id)
			{
				$condition=' AND id IN('.$id.')';
			}
			else
			{
				$condition=' AND id = '.$id;
			}
		}
		elseif(empty($id)) {
			return 0;
		}
		$DiyInfo = $this->show($condition,0,0,'appid,operation',false);
		$delData = array();
		if($DiyInfo&&is_array($DiyInfo))
		foreach ($DiyInfo as $v)
		{
			$delData[$v['operation']][] = $v['appid'];
		}
		$reDel = array();
		foreach ($delData as $k => $v)
		{
			$reDel = $memberCreditRules->creditrules_diy_unset($k,'',$v);
		}
		return $reDel?$id:0;
	}
}

?>