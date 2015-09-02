<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_apply.php 4658 2011-10-10 01:35:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID','cp_group_m');//模块标识

class groupApplyShowApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取所有地盘数据
	 */
	public function show()
	{
		//分页参数设置
		$page = $this->input['pp'] ? $this->input['pp'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$offset = $page * $count;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;

		
		$sql = 'SELECT g.name as group_name ,g.group_id,a.user_id,a.apply_time,a.accept_time,a.is_agree,u.user_name 
				FROM ' . DB_PREFIX . 'user_apply a 
				LEFT JOIN ' . DB_PREFIX . 'group g ON a.group_id = g.group_id 
				LEFT JOIN ' . DB_PREFIX .'user u ON a.user_id = u.user_id 
				ORDER BY a.apply_time DESC ' . $data_limit;
		$qid = $this->db->query($sql);
		$grands = array();
		$this->setXmlNode('group_info' , 'group');
		while(false != ($r = $this->db->fetch_array($qid)))
		{
			$r['apply_time'] = date("Y-m-d H:i:s",$r['apply_time']);
			$grands[$r['group_id']][$r['user_id']] = $r;
		}
//		hg_pre($grands);
		$this->addItem($grands);
		$this->output();
	}
	
	public function check()
	{
		$user_id = $this->input['user_id'];
		$group_id = $this->input['group_id'];
		$type = $this->input['type'];
		
		$info = array();
		$qid = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'user WHERE user_id = ' . intval($user_id));
		if(!$qid)
		{
			$this->errorOutput("该用户不存在或已注销");
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'group_members WHERE group_id = ' . $group_id . ' and user_id = ' . intval($user_id) . ' and user_level = 2';
		

		$query = $this->db->query_first($sql);
		
		$num = $this->check_usergrand_num($user_id);
		
		$this->setXmlNode('group_info' , 'group');
		if($type)
		{
			if(!is_array($query))
			{
				//如果用户目前不是该地盘的地主，就判断当前用户做的地主数目是否达到系统设置上限
				if($num >= $this->settings['user_grands_num'])
				{
					$info['msg'] = "该用户已经是" . $num . "个地盘的地主，达到系统设定的上限";
					$info['tips'] = 0;
				} 
				else 
				{
					$info = $this->do_agree($user_id, $group_id);
				}
			}
			else
			{
				$info['msg'] = "该用户已经是该地盘的地主！";
				$info['tips'] = 0;
			}
		}
		else 
		{
			$info = $this->del_grand($group_id);
		}
		
		$this->addItem($info);
		$this->output();
	}
	
//删除地主
	private function del_grand($group_id)
	{
		$query = $this->db->query_first('select * from ' . DB_PREFIX . 'user_apply where group_id = ' . $group_id);
		
		if(!$query)
		{
			$qq = $this->db->query_first('select user_id,user_name from ' . DB_PREFIX . 'group where group_id = ' . $group_id);
			if($qq)
			{
				$this->db->query('update ' . DB_PREFIX . 'group set user_id = 0,user_name = "0" where group_id = ' . $group_id);
				$member_sql = 'update ' . DB_PREFIX . 'group_members set user_level = 0 where group_id = ' . $group_id . ' and user_id = ' . $qq['user_id'];
				$this->db->query($member_sql);
			}
			$info['msg'] = "操作成功";
			$info['tips'] = 1;
		}
		else
		{
			$sql = 'update ' . DB_PREFIX . 'user_apply set is_agree = 0, accept_time = ' . time() . ' where group_id = ' . $group_id;
			$this->db->query($sql);
			
			$member_sql = 'update ' . DB_PREFIX . 'group_members set user_level = 0 where group_id = ' . $group_id . ' and user_id = ' . $query['user_id'];
			$this->db->query($member_sql);

			$group_sql = 'update ' . DB_PREFIX . 'group set user_id = 0,user_name = "" where group_id = ' . $group_id;
			$this->db->query($group_sql);
			
			$info['msg'] = "操作成功";
			$info['tips'] = 1;
		}
		return $info;
	}

	private function do_agree($userid,$groupid)
	{
		$userid = intval($userid);
		$groupid = intval($groupid);
		$num = $this->check_usergrand_num($userid);
		$user = $this->db->query_first('select user_name  from ' . DB_PREFIX . 'user where user_id = ' . $userid);
		if(!$user)
		{
			$this->errorOutput("该用户不存在或已注销");
		}
		else if($num >= $this->settings['user_grands_num'])
		{
			$info['msg'] = "该用户已经是" . $num . "个地盘的地主，达到系统设定的上限";
			$info['tips'] = 0;
		}
		else
		{
			$query = $this->db->query_first('select * from ' . DB_PREFIX . 'group_members where group_id=' . $groupid . ' and user_id = ' . $userid);
			if($query)
			{
				$sql = 'update ' . DB_PREFIX . 'group_members set user_level = case when user_id = ' . $userid . ' then 2 else 0 end where group_id = ' . $groupid ;
			}
			else
			{
				//如果申请地主的人不是该讨论区的会员，就将他的数据插入group_members表中，并更新group表中人数字段
				$sql = 'insert into ' . DB_PREFIX . 'group_members(group_id,user_id,user_name,join_time,user_level) values("' . $groupid . '","' . $userid . '","' . $user['user_name'] . '","' . time() . '",2)';
				$this->db->query('update ' . DB_PREFIX . 'group set group_member_count = group_member_count + 1 where group_id = ' . $groupid);
			}
			$this->db->query($sql);
			$this->db->query('update ' . DB_PREFIX . 'user_apply set is_agree= case when user_id = '.$userid.' then 1 else 0 end ,accept_time ="'.time(). '" where group_id=' . $groupid);
			$this->db->query('update ' . DB_PREFIX . 'group set user_id = ' . $userid . ' , user_name = "' . $user['user_name'] . '" where group_id = ' . $groupid);
		
			$info['msg'] = "确认成功！";
			$info['tips'] = 1;
		}
		
		return $info;
			
	}
	
	private function check_usergrand_num($userid)
	{
		$sql = 'SELECT count(group_id) as num FROM ' . DB_PREFIX . 'group_members gm WHERE 1 AND gm.user_level =2 AND gm.user_id = ' . $userid;
		$query = $this->db->query_first($sql);
		return $query['num'];
	}
	
	/**
	 * @param $group_id
	 * return $info
	 * 
	 * 
	 * 根据地盘ID查询地盘详情
	 */
	public function more()
	{
		$this->setXmlNode('group_info' , 'group');
		$this->addItem($group);
		$this->output();
	}
	
	/**
	 * 获取地盘总数
	 * 默认为全部地盘的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_apply WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		echo json_encode($r);
	}
	
	/**
	 * 获取单条地盘信息
	 */
	public function detail()
	{		 		
	}
	
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
	}
}

/**
 *  程序入口
 */
$out = new groupApplyShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>



	