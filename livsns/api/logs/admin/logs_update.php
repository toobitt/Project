<?php
define('MOD_UNIQUEID','logs');
require('global.php');
class logsUpdateApi extends adminUpdateBase
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/logs.class.php');
		$this->obj = new logs();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{	
		if(empty($this->input))
		{
			return false;
		}
		$info = array();
		$operation = array();
		$user_name = '';
		//获取应用标识
		$info['bundle_id'] = $operation['bundle_id'] = urldecode($this->input['bundle_id']);
		//获取模板标识
		$info['moudle_id'] = $operation['moudle_id'] =  urldecode($this->input['moudle_id']);
		//获取操作人id
		$info['user_id'] = $this->user['user_id'];
		//获取操作人
		$info['user_name'] = $user_name = urldecode($this->user['user_name']);
		//获取操作类型
		//$info['operation'] = urldecode($this->input['operation']);
		$operation['op_name'] = urldecode($this->input['operation']);
		//获取操作标识
		$operation['action']   = trim($this->input['action']);
		//获取ip
		$info['ip'] = hg_getip();
		
		if (function_exists('hg_getIpInfo'))
		{
			$info['ip_info'] = hg_getIpInfo($info['ip']);
		}
		//获取操作时间 
		$info['create_time'] = TIMENOW;
		//获取来源
		$info['source'] = urldecode($this->user['display_name']);
		//获取经度
		$info['longtitude'] = $this->user['lon'];
		//获取纬度
		$info['latitude'] = $this->user['lat'];
		//获取内容id
		$info['content_id'] = intval($this->input['content_id']);
		//获取内容id
		$info['sort_id'] = intval($this->input['sort_id']);
		//获取标题
		$info['title'] = urldecode($this->input['title']);
		
		$info['org_id']   = intval($this->user['org_id']);
		
		$sq_ = "SELECT id,action FROM ". DB_PREFIX . "system_log_operation WHERE bundle_id = '" .$operation['bundle_id']."'"." AND moudle_id  = '" .$operation['moudle_id']."'"." AND op_name  = '" .$operation['op_name']."'";
		$q_ = $this->db->query_first($sq_);
		$op_id = $q_['id'];
		if(!$op_id)
		{
			$sql  = 'INSERT INTO '.DB_PREFIX.'system_log_operation SET ';
			foreach ($operation as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			
			$this->db->query($sql);
			
			$op_id = $this->db->insert_id();
		}
		elseif(!$q_['action'] || $q_['action'] != $action)
		{
			$sql  = 'UPDATE '.DB_PREFIX.'system_log_operation SET action = "'.$operation['action'].'" WHERE id = '.$op_id;
			$this->db->query($sql);
		}
		
		//获取操作id
		$info['operation'] = $op_id;
		
		$sq = "SELECT id FROM ". DB_PREFIX . "systerm_log_user WHERE user_name = '" .$user_name."'";
		$q = $this->db->query_first($sq);
		if(!$q['id'])
		{
			$sql_ = "INSERT INTO ".DB_PREFIX."systerm_log_user SET user_name = '" .$user_name."'";
			$this->db->query($sql_);
		}
		$ret = $this->obj->addLogs($info);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		if(empty($this->input))
		{
			return false;
		}
		$info = array();
		//获取应用标识
		$info['bundle_id'] = urldecode($this->input['bundle_id']);
		//获取模板标识
		$info['moudle_id'] = urldecode($this->input['moudle_id']);
		//获取操作类型
		$info['operation'] = urldecode($this->input['operation']);
		//获取内容id
		$info['content_id'] = intval($this->input['content_id']);
		$info['id'] = intval($this->input['id']);
		$ret = $this->obj->updateLogs($info);
		$this->addItem($ret);
		$this->output();
	}	
	
	public function delete()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}		
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请输入需要删除得日志id");
		}
		$ret = $this->obj->delete($ids);
		$this->addItem($ret);
		$this->output();
		
	}
	
	public function delete_select()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}		
		$condition = $this->get_condition();
		if(empty($condition))
		{
			$this->addItem(array('error'=>'请选择需要删除得日志'));
			$this->output();exit;
		}
		$ret = $this->obj->delete_select($condition);
		$this->addItem(array('success'=>'删除成功'));
		$this->output();
		
	}
	
	public function deleteContent()
	{	
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}		
		$ids = urldecode($this->input['content_id']);
		if(empty($ids))
		{
			$this->errorOutput("请输入需要删除得日志id");
		}
		//获取应用标识
		$bundle_id = urldecode($this->input['bundle_id']);
		//获取模板标识
		$moudle_id = urldecode($this->input['moudle_id']);
		$ret = $this->obj->deleteContent($ids,$bundle_id,$moudle_id);
		$this->addItem($ret);
		$this->output();
		
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{		
		$condition = '';
		
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim($this->input['k']).'%"';
		}
		//查询应用分组
		if($this->input['_id']&&$this->input['para']=='app')
		{	
			require_once(ROOT_PATH . 'lib/class/auth.class.php');
			$this->auth = new Auth();
			$apps = $this->auth->get_app();
			if(is_array($apps))
			{
				foreach($apps as $k=>$v)
				{
					$ret[$v['id']] = $v['bundle'];
				}
			}
			$condition .= " AND bundle_id = '". $ret[$this->input['_id']] . "'";
		}
		if($this->input['_id']&&$this->input['para']!='app')
		{	
			$condition .= " AND moudle_id = '". $this->input['para'] . "'";
		}
		//查询操作
		if (urldecode($this->input['ops'])!=''&&(urldecode($this->input['ops'])!= -1))
		{			
			$condition .=" AND operation= '" . urldecode($this->input['ops']) . "'";
		}
		//查询来源
		if (urldecode($this->input['sos'])!=''&&(urldecode($this->input['sos'])!= -1))
		{			
			$condition .=" AND source= '" . urldecode($this->input['sos']) . "'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= ".$end_time;
		}

		if($this->input['create_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['create_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND  create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
		
	}
	 
	public function delete_logs_by_deletedate()
	{
		$deletedate = strtotime(DELETE_DATE);
		
		$ret = $this->obj->delete_logs_by_deletedate($deletedate);
		$this->addItem($ret);
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
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new logsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>