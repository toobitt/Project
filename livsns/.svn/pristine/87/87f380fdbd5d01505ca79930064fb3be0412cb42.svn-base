<?php
/***************************************************************************
 * $Id: register.php 36891 2014-05-12 08:06:30Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_medallog');//模块标识
require('./global.php');
class member_medallog extends outerReadBase
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
		$condition='';
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 10;
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT member_id,medalid,dateline FROM " . DB_PREFIX . "medallog";
		$sql.= " WHERE type<'2'" . $condition;
		$sql .= " ORDER BY dateline DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		$medal_id=array();
		$member_id=array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['dateline'])
			{
				$row['dateline']=hg_tran_time($row['dateline']);
			}
			$return[] = $row;
			$medal_id[]=$row['medalid'];
			$member_id[]=$row['member_id'];
		}
		$medal_info = $this->Members->get_medal(@array_unique($medal_id),'id,name');
		$member_id=@array_unique($member_id);
		if($member_id&&is_array($member_id))
		{
			$member_info = $this->Members->get_member_info('AND member_id IN ('.implode(',', $member_id).')','m.member_id,m.member_name,m.avatar','','member_id',true);
		}
		if (!empty($return)&&is_array($return))
		{
			foreach ($return AS $v)
			{
				$v['member_name']=$member_info[$v['member_id']]['member_name'];
				$v['avatar']=$member_info[$v['member_id']]['avatar'];
				$v['medal_name']=$medal_info[$v['medalid']]['name'];
				$v['title'] =$v['member_name'].',在'.$v['dateline'].'获得 '.$v['medal_name'].' 勋章';
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$member_id=$this->user['user_id']?$this->user['user_id']:0;
		if (empty($member_id))
		{
			$this->errorOutput(NO_MEMBER_ID);
		}
		$condition=' AND member_id='.$member_id;
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 10;
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$sql = "SELECT medalid,dateline,expiration FROM " . DB_PREFIX . "medallog";
		$sql.= " WHERE 1" . $condition;
		$sql .= " ORDER BY dateline DESC".$limit;
		$q = $this->db->query($sql);
		$return = array();
		$medal_id=array();
		$member_id=array();
		while ($row = $this->db->fetch_array($q))
		{
			if ($row['dateline'])
			{
				$row['dateline']=date('Y-m-d H:i',$row['dateline']);
			}
			if ($row['expiration'])
			{
				$row['expiration']=date('Y-m-d H:i',$row['expiration']);
			}
			$return[] = $row;
			$medal_id[]=$row['medalid'];
			$member_id[]=$row['member_id'];
		}
		$medal_info = $this->Members->get_medal(@array_unique($medal_id),'id,name');
		if (!empty($return)&&is_array($return))
		{
			foreach ($return AS $v)
			{
				$v['medal_name']=$medal_info[$v['medalid']]['name'];
		 		if($v['type']== 2&&$v['type']==3)
		 		{
		 			if($v['type'] == 2)
		 			{
		 				$status='等待审核';
		 			}
		 			elseif ($v['type'] == 3)
		 			{
		 				$status='未通过审核';
		 			}
		 			$v['title']='我在 '.$v['dateline'].' 申请了 '.$v['medal_name'].' 勋章,'.$status;
		 		}
		 		elseif($v['type']!= 2&&$v['type']!=3)
		 		{
		 			if($v['expiration']) { 
		 				$v['expiration']='有效期:' .$v['expiration'];
					} else {
						$v['expiration']='永久有效';
					}
					$v['title']='我在 '.$v['dateline'].' 被授予了 '.$v['medal_name'].' 勋章,'.$v['expiration']; 
		 		}
		 		
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "medallog WHERE type<'2'";
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}


}

$out = new member_medallog();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>