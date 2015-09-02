<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|update|delete|delQuestionChecked|voteState|getDefaultSettings|
* 					vote_remove|voteMove|getOtherMore|getOtherOption|optionOtherState|updateOtherTitle|unknow
* @private getVoteSettings
* $Id: vote_update.php 
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','question');//模块标识
class voteUpdateApi extends adminUpdateBase
{
/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include vote.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/vote.class.php');
		$this->obj = new vote();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 问卷添加
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $title string 问卷标题
	 * @param $describes string 描述
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @param $limit_time int 间隔时间
	 * @param $is_ip tyinint 是否限制同一IP (1-是 0-否)
	 * @param $is_userid tyinint 是否限制同一用户ID (1-是 0-否)
	 * @param $is_verify_code tyinint 是否开启验证码 (1-是 0-否)
	 * @param $group_id int 问卷分类ID
	 * @param $state tyinint 是否开启问卷 (1-是 0-否)
	 * @param $is_logo tyinint 是否开启图片 (1-是 0-否)
	 * @param $logo string 图片
	 * @param $admin_id int 用户ID
	 * @param $admin_name string 用户名
	 * @param $create_time int 创建时间
	 * @param $update_time int 更新时间
	 * @param $ip string 创建者IP
	 * @param $order_id int 排序ID
	 * @return $data['id'] int 所添加问卷ID
	 */
	public function create()
	{
		if (!trim(urldecode($this->input['title'])))
		{
			$this->errorOutput('标题不能为空');
		}
		
		if ($this->input['q_title'])
		{
			foreach ($this->input['q_title'] AS $v)
			{
				if (!$v)
				{
					$this->errorOutput('问题不能为空');
				}
			}
			
		}

		if (urldecode($this->input['describes']) == '这里输入描述')
		{
			$this->input['describes'] = '';
		}

		$option_title = array();
		for ($i = 0; $i < count($this->input['q_title']); $i++)
		{
			$option_title[$i] = $this->input['option_title_' . $i];
		}
		
		if ($option_title)
		{
			foreach ($option_title AS $v)
			{
				foreach ($v AS $vv)
				{
					if (!$vv)
					{
						$this->errorOutput('问题选项不能为空');
					}
				}
			}
		}
		
		if (!urldecode($this->input['start_time']))
		{
			$this->errorOutput('开始时间不能为空');
		}
		
		if (!urldecode($this->input['end_time']))
		{
			$this->errorOutput('结束时间不能为空');
		}
		
		if (urldecode($this->input['end_time']) < urldecode($this->input['start_time']))
		{
			$this->errorOutput('结束时间要大于开始时间');
		}
		
		if (!intval($this->input['ip_limit_time']))
		{
		//	$this->errorOutput('IP时间限制不能为空');
		}
		
		if (!intval($this->input['userid_limit_time']))
		{
		//	$this->errorOutput('用户时间限制不能为空');
		}
		
		$data = $this->obj->create();
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'create', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		if (!$data)
		{
			$this->errorOutput('添加失败');
		}
		$this->getVoteSettings();
		$this->setXmlNode('vote', 'data');
		$this->addItem($data['id']);
		$this->output();
	}
	
	/**
	 * 问卷更新
	 * @name update
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 所更新问卷ID
	 * @param $title string 问卷标题
	 * @param $describes string 描述
	 * @param $start_time int 开始时间
	 * @param $end_time int 结束时间
	 * @param $limit_time int 间隔时间
	 * @param $is_ip tyinint 是否限制同一IP (1-是 0-否)
	 * @param $is_userid tyinint 是否限制同一用户ID (1-是 0-否)
	 * @param $is_verify_code tyinint 是否开启验证码 (1-是 0-否)
	 * @param $group_id int 问卷分类ID
	 * @param $state tyinint 是否开启问卷 (1-是 0-否)
	 * @param $is_logo tyinint 是否开启图片 (1-是 0-否)
	 * @param $logo string 图片
	 * @param $admin_id int 用户ID
	 * @param $admin_name string 用户名
	 * @param $update_time int 更新时间
	 * @return $data array 所更新问卷信息
	 */
	public function update()
	{
		if (!intval($this->input['id']))
		{
			$this->errorOutput('未传入ID');
		}
	
		if (!trim(urldecode($this->input['title'])))
		{
			$this->errorOutput('标题不能为空');
		}
	
		if (urldecode($this->input['describes']) == '这里输入描述')
		{
			$this->input['describes'] = '';
		}
	
		if ($this->input['q_title'])
		{
			foreach ($this->input['q_title'] AS $v)
			{
				if (!$v)
				{
					$this->errorOutput('问题不能为空');
				}
			}
			
		}
	
		$option_title = array();
		for ($i = 0; $i < count($this->input['q_title']); $i++)
		{
			$option_title[$i] = $this->input['option_title_' . $i];
		}
		
		if ($option_title)
		{
			foreach ($option_title AS $v)
			{
				foreach ($v AS $vv)
				{
					if (!$vv)
					{
						$this->errorOutput('问题选项不能为空');
					}
				}
			}
		}
		
		if (!urldecode($this->input['start_time']))
		{
			$this->errorOutput('开始时间不能为空');
		}
		
		if (!urldecode($this->input['end_time']))
		{
			$this->errorOutput('结束时间不能为空');
		}
		
		if (urldecode($this->input['end_time']) < urldecode($this->input['start_time']))
		{
			$this->errorOutput('结束时间要大于开始时间');
		}
	
		if (!intval($this->input['ip_limit_time']))
		{
			$this->errorOutput('IP时间限制不能为空');
		}
		
		if (!intval($this->input['userid_limit_time']))
		{
			$this->errorOutput('用户时间限制不能为空');
		}
		
		$data = $this->obj->update();
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		if (!$data)
		{
			$this->errorOutput('更新失败');
		}
		$this->getVoteSettings();
		$this->setXmlNode('vote', 'data');
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 获取问卷设置 
	 * @name getVoteSettings
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $admin_id int 用户ID
	 * @param $is_ip tyinint 是否限制同一IP (1-是 0-否)
	 * @param $is_userid tyinint 是否限制同一用户ID (1-是 0-否)
	 * @param $is_verify_code tyinint 是否开启验证码 (1-是 0-否)
	 * @param $state tyinint 是否开启问卷 (1-是 0-否)
	 * @param $is_logo tyinint 是否开启图片 (1-是 0-否)
	 * @param $admin_name string 用户名
	 * @param $update_time int 更新时间
	 * @param $ip_time string 用户IP
	 */
	private function getVoteSettings()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_settings WHERE is_settings=1 AND admin_id=" . intval($this->user['user_id']);
		$q = $this->db->query_first($sql);
		if ($q)
		{
			$settingsData = array(
				'is_ip' => intval($this->input['is_ip']),
				'is_userid' => intval($this->input['is_userid']),
				'is_verify_code' => intval($this->input['is_verify_code']),
				'state' => intval($this->input['state']),
				'is_logo' => intval($this->input['is_logo']),
				'is_settings' => 1,
				'update_time' => TIMENOW,
			);
			$sql = "UPDATE " . DB_PREFIX . "vote_settings SET ";
			$space = "";
			foreach ($settingsData AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$sql .= " WHERE id=" . $q['id'];
			$this->db->query($sql);
		}
		else 
		{
			$settingsData = array(
				'is_ip' => intval($this->input['is_ip']),
				'is_userid' => intval($this->input['is_userid']),
				'is_verify_code' => intval($this->input['is_verify_code']),
				'state' => intval($this->input['state']),
				'is_logo' => intval($this->input['is_logo']),
				'is_settings' => 1,
				'admin_id' => intval($this->user['user_id']),
				'admin_name' => urldecode($this->user['user_name']),
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip' => hg_getip()
			);
			$sql = "INSERT INTO " . DB_PREFIX . "vote_settings SET ";
			$space = "";
			foreach ($settingsData AS $key => $value)
			{
				$sql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$this->db->query($sql);
		}
	}
	/**
	 * 删除问卷 (所属问卷信息)
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @return $id int 被删除问卷ID
	 */
	public function delete()
	{
		if (!urldecode($this->input['id']))
		{
			$this->errorOutput('未传入ID');
		}
		
		$id = $this->obj->delete();
		if($id)
		{
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束	
			$this->addItem($id);
		}
		$this->output();
	}
	public function delete_comp()
	{
		$id = $this->obj->delete_comp();
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete_comp', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$this->addItem($id);
		$this->output();
	}
	public function recover()
	{
		
		//$data = $this->obj->recover();
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'recover', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 删除问卷之前对问卷选项的验证
	 * @name delQuestionChecked
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @return $tip string 该问卷下的选项
	 */
	public function delQuestionChecked()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE vote_id=" . $id . " ORDER BY id ASC";
		$q = $this->db->query($sql);
		$vote_question_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$vote_question_info[$row['id']]= $row['title'];
		}
		if ($vote_question_info)
		{
			$tip = implode(',', $vote_question_info);
		}
		else 
		{
			$tip = 0;
		}
		
		$this->addItem($tip);
		$this->output();
	}
	/**
	 * 问卷状态
	 * @name voteState
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @return $tip int 问卷状态 (1-开启 0-关闭)
	 */
	public function voteState()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT state FROM " . DB_PREFIX . "vote WHERE id=" . $id;
		$vote_info = $this->db->query_first($sql);
		$state = $vote_info['state'];
		
		$tip = '';
		
		if ($state)
		{
			$sql = "UPDATE " . DB_PREFIX . "vote SET state=0 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 0;
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "vote SET state=1 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 1;
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'voteState', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$this->addItem($tip);
		$this->output();
	}
	
	/**
	 * 获取默认设置
	 * @name getDefaultSettings
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $return array 问卷默认设置信息
	 */
	public function getDefaultSettings()
	{
		$admin_id = intval($this->user['user_id']);
		if ($admin_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "vote_settings WHERE is_settings=1 AND admin_id=" . $admin_id;
			$r = $this->db->query_first($sql);
   			$return['is_ip'] = $r['is_ip'];
   			$return['is_userid'] = $r['is_userid'];
   			$return['is_verify_code'] = $r['is_verify_code'];
   			$return['state'] = $r['state'];
   			$return['is_logo'] = $r['is_logo'];
		}
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 移动操作
	 * @name vote_remove
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @param $group_id int 分类ID
	 * @return $tip array 
	 */
	public function vote_remove()
	{
		$id = $this->input['id'];
		$group_id = $this->input['group_id'];
		$tip = array('id'=>$id, 'group_id'=>$group_id);
		$this->addItem($tip);
		$this->output();
	}

	public function voteMoveForm()
	{
		$id = $this->input['id'];
		$node_id = $this->input['node_id'];
		$tip = array('id'=>$id, 'node_id'=>$node_id);
		$this->addItem($tip);
		$this->output();
	}
	/**
	 * 移动数据
	 * @name voteMove
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 问卷ID
	 * @param $group_id int 分类ID
	 * @return $tip array 
	 */
	public function voteMove()
	{
		$id = $this->input['id'];
		$group_id = $this->input['group_id'];
		if ($group_id)
		{
			$sql = "SELECT name FROM " . DB_PREFIX . "group WHERE id=" . $group_id;
			$group = $this->db->query_first($sql);
		}
		$sql = "UPDATE " . DB_PREFIX . "vote SET group_id =" . $group_id . " WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'voteMove', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$tip = array('id'=>$id, 'name'=>$group['name']);
		$this->addItem($tip);
		$this->output();
	}
	
	/**
	 * 获取投票其他更多选项
	 * @name getOtherMore
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vote_question_id int 投票ID
	 * @param $offset int 查询起始数
	 * @param $count int 查询长度
	 * @return $info array 用户填写选项信息
	 */
	public function getOtherMore()
	{
		$vote_question_id = $this->input['vote_question_id'];
		$offset = $this->input['offset'];
		$count = $this->settings['other_option_count'];
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE is_other=1 AND vote_question_id=" . $vote_question_id;
		$sql .= " ORDER BY id ASC ";
		$sql .= " LIMIT " . $offset . ", " . $count;
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 获取其他选项
	 * @name getOtherOption
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vote_question_id int 投票ID
	 * @param $offset int 查询起始数
	 * @param $count int 查询长度
	 * @return $info array 用户填写选项信息
	 */
	public function getOtherOption()
	{
		$id = $this->input['vote_question_id'];
	
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id=" . $id;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('vote_question' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['question_img'] = hg_get_images($row['pictures'], UPLOAD_URL . QUESTION_IMG_DIR, $this->settings['question_img_size']);
			
			$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN(" . $id . ") ORDER BY id ASC";
			$q = $this->db->query($sql);
			$row['option_title'] = $row['other_option_title'] =  array();
			while ($r = $this->db->fetch_array($q))
			{
				if (!$r['is_other'])
				{
					$r['option_img'] = hg_get_images($r['pictures'], UPLOAD_URL . OPTION_IMG_DIR, $this->settings['option_img_size']);
					$row['option_title'][] = $r;
				}
				else 
				{
					$row['other_option_title'][] = $r;
				}
			}
			if ($row['option_title'])
			{
				$row['vote_total'] = "";
				foreach ($row['option_title'] AS $vv)
				{
					$row['vote_total'] = $vv['single_total'] + $row['vote_total'];
				}
			}
			if ($row['other_option_title'])
			{
				$row['other_vote_total'] = "";
				foreach ($row['other_option_title'] AS $vv)
				{
					$row['other_vote_total'] = $vv['single_total'] + $row['other_vote_total'];
				}
			}
			$row['other_option_num'] = count($row['other_option_title']);
			$row['question_total'] = $row['vote_total'] + $row['other_vote_total'];
			
		//hg_pre($row);
			$this->addItem($row);
			$this->output();
		}
	}
	
	/**
	 * 其他选项审核
	 * @name optionOtherState
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 选项ID
	 * @return $tip int (1-已审核 0-待审核)
	 */
	public function optionOtherState()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT state FROM " . DB_PREFIX . "question_option WHERE id=" . $id;
		$option_info = $this->db->query_first($sql);
		$state = $option_info['state'];
		
		$tip = '';
		
		if ($state)
		{
			$sql = "UPDATE " . DB_PREFIX . "question_option SET state=0 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 0;
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "question_option SET state=1 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 1;
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'optionOtherState', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->addItem($tip);
		$this->output();
	}
	
	/**
	 * 其他选项编辑
	 * @name updateOtherTitle
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $other_title array 其他选项
	 * @param $hiddenFlag array 是否被修改过 (0-否 1-修改)
	 * @return $title array 
	 */
	public function updateOtherTitle()
	{
		$title = $this->obj->updateOtherTitle();
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'updateOtherTitle', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->addItem($title);
		$this->output();
	}
	
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new voteUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>