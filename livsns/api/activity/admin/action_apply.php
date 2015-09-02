<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_apply.php 4658 2011-10-10 01:35:46Z repheal $
***************************************************************************/
define('MOD_UNIQUEID','cp_activity_m');//模块标识
require('./global.php');

class activityApplyShowApi extends adminBase
{
	var $group;
	
	public function __construct()
	{
		parent::__construct();
		require_once  '../lib/activity.class.php';
		$this->libactivity = new activityLib();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->libactivity);
	}
	
	//获取所有申请参加活动数据
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		
		$data_limit = ' LIMIT ' . $offset. ' , ' . $count;
		$sql = "select g.action_name,a.* from ".DB_PREFIX . "activity_apply  a
		LEFT JOIN " . DB_PREFIX . "activity g ON g.id = a.action_id 
		LEFT JOIN dev_member." . DB_PREFIX . "member m ON a.user_id = m.id 
		where 1 ORDER BY a.`apply_time` DESC ".$data_limit;
		$query = $this->db->query($sql);
		$info = array();
		$this->setXmlNode('apply_list' , 'activity');
		while($row = $this->db->fetch_array($query))
		{
			$row['apply_time'] = date("Y-m-d H:i:s", $row['apply_time']);
			$this->addItem($row);
		}
		$this->output();
	}
	
	//获取参加活动总数
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "activity_apply WHERE 1 ";
		echo json_encode($this->db->query_first($sql));
	}
	
	/**
	 * 改变审核状态
	 */
	public function audit()
	{
		$id = isset($this->input['id']) ? trim($this->input['id']) : "-1";
		$type = 2;
		if($type == "-1" || $id == "-1")
		{
			$this->errorOutput("参数错误");
		}
		$sql = "select id,action_id,apply_status from " . DB_PREFIX . "activity_apply where id in (".$id.")";
		$result = $this->db->fetch_all($sql);
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				$preStats = $v['apply_status'];
				$action_id = $v['action_id'];
				$sql = "update " . DB_PREFIX . "activity_apply set apply_status=".$type." where id =".$v['id'];
				$res = $this->db->query($sql);
				if($res)
				{
					//修改对应状态位
					if( ($preStats ==0 || $preStats == 2) && ($type==1 || $type==3))
					{
						$this->db->query("update " . DB_PREFIX . "activity set yet_join=yet_join-1 where id=".$action_id);
					}
					elseif (($preStats==1 || $preStats==3)  && ($type==0 || $type==2))
					{
						$this->db->query("update " . DB_PREFIX . "activity set yet_join=yet_join+1 where id=".$action_id);
					}
					else 
					{
						//TODO
					}	
				}
			}
			$this->addItem(true);
		}
		else 
		{
			$this->addItem(false);
		}
		$this->output();
	}
	
	/**
	 * 改变审核状态
	 */
	public function back()
	{
		$id = isset($this->input['id']) ? trim($this->input['id']) : "-1";
		$type = 3;
		if($type == "-1" || $id == "-1")
		{
			$this->errorOutput("参数错误");
		}
		$sql = "select id,action_id,apply_status from " . DB_PREFIX . "activity_apply where id in (".$id.")";
		$result = $this->db->fetch_all($sql);
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				$preStats = $v['apply_status'];
				$action_id = $v['action_id'];
				$sql = "update " . DB_PREFIX . "activity_apply set apply_status=".$type." where id =".$v['id'];
				$res = $this->db->query($sql);
				if($res)
				{
					//修改对应状态位
					if( ($preStats ==0 || $preStats == 2) && ($type==1 || $type==3))
					{
						$this->db->query("update ” . DB_PREFIX . “activity set yet_join=yet_join-1 where id=".$action_id);
					}
					elseif (($preStats==1 || $preStats==3)  && ($type==0 || $type==2))
					{
						$this->db->query("update " . DB_PREFIX . "activity set yet_join=yet_join+1 where id=".$action_id);
					}
					else 
					{
						//TODO
					}	
				}
			}
			$this->addItem(true);
		}
		else 
		{
			$this->addItem(false);
		}
		$this->output();
	}
	
	public function change_state()
	{
		$user_id = $this->input['user_id'];
		$action_id = $this->input['action_id'];
		$state = $this->input['state'];
		if(!$user_id || !$action_id)
		{
			$this->errorOutput("参数错误");
		}
		$sql = "select id,user_id,apply_status from ". DB_PREFIX . "activity_apply where is_del =0 and action_id=".$action_id." and user_id in(".$user_id.")";
		
		$result = $this->db->fetch_all($sql);

		if($result)
		{
			foreach ($result as $k=>$v)
			{
				$this->db->query("update " . DB_PREFIX . "activity_apply set apply_status=".$state." where id=".$v['id']);
				
				if(($v['apply_status']==1 ||$v['apply_status']==3)  && ($state==2 || $state==0))
				{
					$this->db->query("update ". DB_PREFIX . "activity set yet_join=yet_join+1 where id=".$action_id);
				}
				else if(($v['apply_status']==0 ||$v['apply_status']==2)  && ($state==1 || $state==3))
				{
					$this->db->query("update ". DB_PREFIX . "activity set yet_join=yet_join-1 where id=".$action_id);
				}
				else 
				{
					//TODO
				}
			}
			$this->addItem(array('staus'=>true));
		}
		else 
		{
			$this->addItem(array('staus'=>false));
		}
		
		$this->output();
	}
	
	/*
	 * 设置明星用户
	 * action_id :活动id
	 * type: 0为id，1为user_id
	 * params:对应参数
	 */

	function updateStar()
	{
		$action_id = $this->input['_action_id'];
		$type = $this->input['_type'];
		$params = trim($this->input['_params']);
		if(!$params || !$action_id)
		{
			$this->errorOutput("缺少参数ID");
		}
		$sql = "select apply_star from " . DB_PREFIX."activity where state=1 and id=".$action_id;
		$num = $this->db->query_first($sql);
		if($num['apply_star'])
		{
			$sql = "select count(*) as total from " . DB_PREFIX . "activity_apply where apply_star=1 and is_del=0 and apply_status in(0,2) and action_id=".$action_id;
			$total = $this->db->query_first($sql);
			if($total['total'] >= $num['apply_star'])
			{
				$this->errorOutput("明星用户设置过多");
			}
			$doing =  1;
			if(strpos($params, ','))
			{
				$doing =  substr_count($params,',')+1;
			}
			if(($total['total']+$doing)>$num['apply_star'])
			{
				$this->errorOutput("明星用户设置过多");
			}
		}
		$sql = '';
		$sql = "update " . DB_PREFIX . "activity_apply set apply_star=1 where is_del=0 and apply_status in(0,2) "." and action_id=".$action_id;
		if(!$type)
		{
			$sql .= " and user_id in(".$params.")";
		}
		else
		{
			$sql .= " and id in(".$params.")";
		}
		$ret = $this->db->query($sql);
		$this->addItem(array('staus'=>$ret));
		$this->output();
	}
	
	/*
	 * 删除明星用户
	 * action_id :活动id
	 * type: 0为id，1为user_id
	 * params:对应参数
	 */
	public function deleteStar()
	{
		$action_id = $this->input['_action_id'];
		$type = $this->input['_type'];
		$params = trim($this->input['_params']);
		if(!$params || !$action_id)
		{
			$this->errorOutput("缺少参数ID");
		}
		$sql = '';
		$sql = "update " . DB_PREFIX . "activity_apply set apply_star=0 where is_del=0 and apply_status in(0,2) "." and action_id=".$action_id;
		if(!$type)
		{
			$sql .= " and user_id in(".$params.")";
		}
		else
		{
			$sql .= " and id in(".$params.")";
		}
		$ret = $this->db->query($sql);
		$this->addItem(array('staus'=>$ret));
		$this->output();
	}
	
	/**
	 * 设置用户权限在某个讨论组的级别
	 * levl:级别0普通1管理员2创建者
	 * mat:0，id；1:user_id
	 * params:参数
	 **/
	public function setUserLevlToActionId()
	{
		$action_id = trim($this->input['action_id']);
		$levl = trim($this->input['levl']);
		$mat = trim($this->input['mat']);
		$params = trim($this->input['params']);
		if(!$action_id || !$params)
		{
			$this->errorOutput("缺少参数ID");
		}
		if($mat)
		{
			$mat = 'user_id';
		}
		else 
		{
			$mat = 'id';
		}
		//参数个数
		$doing =  1;
		if(strpos($params, ','))
		{
			$doing =  substr_count($params,',')+1;
		}
		if($levl)
		{		
			$sql = "select connection_user,user_id,user_name  from " . DB_PREFIX . "activity where state=1 and id=".$action_id;
			$infos = $this->db->fetch_all($sql);
			$sql = "select count(id) as total,user_id,user_name  from " . DB_PREFIX . "activity_apply where levl=".$levl." and is_del=0 and apply_status in(0,2) and action_id=".$action_id;
			$preInfos = $this->db->fetch_all($sql);
			//无限
			if($infos['0']['connection_user'])
			{
				if($levl == 2)
				{
					//创建者
					if(($preInfos['0']['total']+$doing) > 1)
					{
						$this->errorOutput("创建者过多");
					}
				}
				else 
				{
					//管理员
					if(($preInfos['0']['total']+$doing) > $infos['0']['connection_user'])
					{
						$this->errorOutput("管理者过多");
					}
				}
			}
		}
		$sql = "update " . DB_PREFIX . "activity_apply set levl=".$levl." where  "."  action_id=".$action_id." and ".$mat." in (".$params.") and  is_del=0 and apply_status in(0,2)";
		$ret = $this->db->query($sql);
		$this->addItem(array('staus'=>$ret));
		$this->output();
	} 
}

/**
 *  程序入口
 */
$out = new activityApplyShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>