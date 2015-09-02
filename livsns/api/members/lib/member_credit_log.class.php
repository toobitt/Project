<?php
/***************************************************************************

* $Id: group 26794 2013-08-01 04:34:02Z purview $

***************************************************************************/
class credit_log extends InitFrm
{
	private $params = array();
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

	public function show($condition,$offset,$count,$field='*')
	{
		$member_id = array();
		$return = $this->ShowAll($condition,$offset,$count,$field='*',$member_id);
		$member_name=$this->Members->get_member_name($member_id);
		if($return&&is_array($return))
		{
			foreach ($return as $k=>$v)
			{
				if($member_id)
				{
					$v['member_name']=$member_name[$v['member_id']]?$member_name[$v['member_id']]:'此会员不存在或已被删除';
				}
				$return[$k]=$v;
			}
			return $return;
		}
		return false;
	}
	
	public function ShowAll($condition,$offset,$count,$field='*',& $memberId = null)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "credit_log ";
		$sql.= " WHERE 1 " . $condition;
		$sql .= " ORDER BY dateline DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		$member_id=array();
		while ($row = $this->db->fetch_array($q))
		{
			if($row['member_id'])
			{				
				is_array($memberId) && $memberId[] = $row['member_id'];
			}
			if($row['icon'])
			{
				$row['icon']=maybe_unserialize($row['icon']);
			}else {
				$row['icon'] = array();
			}
			$return[] = $row;
		}
		return $return;
	}
	
	/**
	 * 
	 * 更新日志冻结字段 ...
	 * @param unknown_type $id
	 * @param unknown_type $isFrozen
	 */
	public function updateCreditLogByIsFrozen($logid,$isFrozen)
	{
		$where = array('id'=>$logid);
		$this->membersql->update('credit_log',array('isFrozen'=>$isFrozen?1:0),$where);
	}
	public function count()
	{
		$condition = $this->params;
		$this->membersql->where($condition);
		return $this->membersql->count(array(), 'credit_log');
	}
	
	public function setParams($key,$val = null,$asname='',$diykey = null)
	{	
		!$diykey && $diykey = $key;		
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if(isset($val[$k]))
				{
					$this->setParams($v, $val[$k],$asname,$diykey[$k]);
				}
				else {
					$this->setParams($v,null,$asname,$diykey[$k]);
				}
			}
		}
		elseif($key&&isset($val)) {			
			$this->params[($asname?$asname.'.':'').$key] = $val;
		}
		elseif($key)
		{
			$this->$key&&$this->params[($asname?$asname.'.':'').$key] = $this->$diykey;
		}
		return $this->params[($asname?$asname.'.':'').$key];
	}
	public function getCreditFromMembers($memberId,$page,$page_num)
	{
		if(!$memberId)
		{
			throw new Exception(NO_MEMBER_ID, 200);
		}
		$page = $page?$page:1;
		$page_num = $page_num?$page_num:10;
		$this->setParams('member_id',$memberId);
	    $total_num = $this->count();
	    $count = $page_num;
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$page_info['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$page_info['total_page']    = intval($total_num/$count) + 1;
		}
		$page_info['total_num'] = $total_num;//总的记录数
		$page_info['page_num'] = $count;//每页显示的个数
		$pp = $page;//如果没有传第几页，默认是第一页
		if($pp > $page_info['total_page'])
		{
			$pp = $page_info['total_page'];
		}
		$page_info['current_page']  = $pp;//当前页码
		$offset = intval(($pp - 1)*$count) > 0 ? intval(($pp - 1)*$count) : 0;
		$return['page_info'] = $page_info;
		$creditTypeInfo = $this->Members->get_credit_type();
		$credit_type_field='';
		if($creditTypeInfo)
		{
			$credit_type_field = ','.implode(',', array_keys($creditTypeInfo));
		}
		else 
		{
			$return['info'] = array();
			return $return;
		}
		$field = 'title,remark'.$credit_type_field.',icon,dateline';
		$creditLogInfo = $this->showAll($this->membersql->where($this->params), $offset, $count,$field);
		foreach ($creditLogInfo as $k => $v)
		{
			isset($v['credit1']) && $creditLogInfo[$k]['credit1'] = $v['credit1'].$creditTypeInfo['credit1']['title'];
			isset($v['credit2']) && $creditLogInfo[$k]['credit2'] = $v['credit2'].$creditTypeInfo['credit2']['title'];
			$creditLogInfo[$k]['dateline'] 	= date('Y-m-d H:i:s', $v['dateline']);
		}
		$return['info'] = $creditLogInfo;
		return $return;
	}
	



}

?>