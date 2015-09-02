<?php
/***************************************************************************
 * $Id: register.php 36891 2014-05-12 08:06:30Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_medal');//模块标识
require('./global.php');
class member_medal extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$member_id=$this->user['user_id']?$this->user['user_id']:0;
		$member_medalid=array();
		if($member_id)
		{
			$member_medalid = array_keys($this->Members->get_member_medal(array($member_id),$field='*',0));
		}
		$sql = "SELECT id,name,image,brief,type,limit_num,used_num,start_date,end_date,expiration FROM " . DB_PREFIX . "medal WHERE available=1";
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['image_url']='';
			if($row['image'])
			{
				$row['image']=maybe_unserialize($row['image']);
				$row['image_url']=hg_fetchimgurl($row['image']);
			}
			if(empty($member_id))
			{
				$row['is_apply']=0;
				$row['apply_name']='';
			}
			elseif(in_array($row['id'], $member_medalid))
			{
				$row['is_apply']=0;
				$row['apply_name']='已拥有';
			}
			elseif(empty($row['type']))
			{
				$row['is_apply']=0;
				$row['apply_name']='';
			}
			elseif(($row['used_num']>=$row['limit_num']&&!empty($row['limit_num']))||($row['end_date']<=TIMENOW)&&!empty($row['end_date'])){
				$row['is_apply']=0;
				$row['apply_name']='颁发结束';
			}
			elseif($row['start_date']>=TIMENOW&&!empty($row['start_date'])){
				$row['is_apply']=0;
				$row['apply_name']='未到颁发时间';
			}
			elseif($row['type']==1)  {
				$row['is_apply']=1;
				$row['apply_name']='领取';
			}
			elseif($row['type']==2)  {
				$row['is_apply']=1;
				$row['apply_name']='申请';
			}
			else {
				$row['is_apply']=0;
				$row['apply_name']='';
			}
			if ($row['start_date'])
			{
				$row['start_date']=date('Y.m.d',$row['start_date']);
			}
			if ($row['end_date'])
			{
				$row['end_date']=date('Y.m.d',$row['end_date']);
			}
			if($row['start_date']&&empty($row['end_date']))
			{
				$row['award_date']=$row['start_date'].' - 世界末日';
			}
			if($row['end_date']&&empty($row['start_date']))
			{
				$row['award_date']='现在 - '.$row['end_date'];
			}
			if(empty($row['end_date'])&&empty($row['start_date']))
			{
				$row['award_date']='无限制';
			}
			if(empty($row['limit_num']))
			{
				$row['limit_num']='不限量';
			}
			else
			{
				$row['limit_num']=$row['limit_num'].'枚';
			}
			if(empty($row['used_num']))
			{
				$row['used_num']='未发放';
			}
			else
			{
				$row['used_num']=$row['used_num'].'枚';
			}
			if($row['end_date']&&$row['start_date'])
			{
				$row['award_date']=$row['start_date'].' - '.$row['end_date'];
			}
				if($row['expiration']!=0)
				{
					$row['expiration']=$row['expiration'].'天';
				}
				else
				{
					$row['expiration']='永久有效';
				}
				$row['type_name']=$this->settings['medal_type'][$row['type']];
			$return[] = $row;
		}
		if (!empty($return))
		{
			foreach ($return AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id=$this->input['id']?intval($this->input['id']):0;
		if (empty($id))
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$member_id=$this->user['user_id']?$this->user['user_id']:0;
		$is_apply = 0;
		if($member_id&&!$this->Members->get_member_medal_count($member_id, $id,true))
		{
			$is_apply = 1;
		}
		$sql = "SELECT id,name,image,brief,type,limit_num,used_num,start_date,end_date,expiration FROM " . DB_PREFIX . "medal WHERE available=1 AND id =".$id;
		$q = $this->db->query($sql);
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['image_url']='';
			if($row['image'])
			{
				$row['image']=maybe_unserialize($row['image']);
				$row['image_url']=hg_fetchimgurl($row['image']);
			}
			if(empty($row['type']))
			{
				$row['is_apply']=0;
				$row['apply_name']='';
			}
			elseif(($row['used_num']>=$row['limit_num']&&!empty($row['limit_num']))||($row['end_date']<=TIMENOW)&&!empty($row['end_date'])){
				$row['is_apply']=0;
				$row['apply_name']='颁发结束';
			}
			elseif($row['start_date']>=TIMENOW&&!empty($row['start_date'])){
				$row['is_apply']=0;
				$row['apply_name']='未到颁发时间';
			}
			elseif($row['type']==1)  {
				$row['is_apply']=$is_apply;
				$row['apply_name']='领取';
			}
			elseif($row['type']==2)  {
				$row['is_apply']=$is_apply;
				$row['apply_name']='申请';
			}
			else {
				$row['is_apply'] = 0;
				$row['apply_name']='';
			}
			if ($row['start_date'])
			{
				$row['start_date']=date('Y.m.d',$row['start_date']);
			}
			if ($row['end_date'])
			{
				$row['end_date']=date('Y.m.d',$row['end_date']);
			}
			if($row['start_date']&&empty($row['end_date']))
			{
				$row['award_date']=$row['start_date'].' - 世界末日';
			}
			if($row['end_date']&&empty($row['start_date']))
			{
				$row['award_date']='现在 - '.$row['end_date'];
			}
			if(empty($row['end_date'])&&empty($row['start_date']))
			{
				$row['award_date']='无限制';
			}
			if(empty($row['limit_num']))
			{
				$row['limit_num']='不限量';
			}
			else
			{
				$row['limit_num']=$row['limit_num'].'枚';
			}
			if(empty($row['used_num']))
			{
				$row['used_num']='未发放';
			}
			else
			{
				$row['used_num']=$row['used_num'].'枚';
			}
			if($row['end_date']&&$row['start_date'])
			{
				$row['award_date']=$row['start_date'].' - '.$row['end_date'];
			}
				if($row['expiration']!=0)
				{
					$row['expiration']=$row['expiration'].'天';
				}
				else
				{
					$row['expiration']='永久有效';
				}
				$row['type_name']=$this->settings['medal_type'][$row['type']];
			$return[] = $row;
		}
		if (!empty($return))
		{
			foreach ($return AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function count()
	{
		/**
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "medal WHERE 1 AND available=1 ";
		$info = $this->db->query_first($sql);
		echo json_encode($info);
		*/
	}


}

$out = new member_medal();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>