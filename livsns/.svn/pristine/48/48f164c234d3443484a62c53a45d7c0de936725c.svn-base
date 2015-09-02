<?php
//日志系统的数据库操作
class logs extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	//根据操作进行日志存储
	public function addLogs($info)
	{	
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."system_log SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		$log_id = $this->db->insert_id();
		
		//获取操作之前数据
		if($this->input['pre_data'])
		{
			$data['pre_data'] = addslashes(json_encode($this->input['pre_data']));
		}
		//获取操作之后数据
		if($this->input['up_data'])
		{
			$data['up_data'] = addslashes(json_encode($this->input['up_data']));
		}
		//获取日志id
		$data['log_id'] = $log_id;
		//更新时间
		$data['create_time'] = TIMENOW;;
		$sql_ = "INSERT INTO " . DB_PREFIX ."system_log_content SET ";
		$sql_extra_ = $space ='';
		foreach($data as $k=>$v)
		{
			$sql_extra_ .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql_ .=$sql_extra_;
		$this->db->query($sql_);
		return $log_id;
	}
	
	public function updateLogs($info = array())
	{
		
		if($info && !$info['id'])
		{	
			$sql = "SELECT id FROM " . DB_PREFIX ."system_log WHERE bundle_id='" . $info['bundle_id'] . "' AND moudle_id='" . $info['moudle_id'] . "' AND content_id='" . $info['content_id'] ."'";
			$f = $this->db->query_first($sql);
			if(!empty($f))
			{
				$info['id'] = $f['id'];
				if($info['operation'])
				{
					$sql = "UPDATE " . DB_PREFIX ."system_log SET operation='" . $info['operation'] . "' WHERE id=" . $info['id'];
					$this->db->query($sql);
				}
				$sql = "UPDATE " . DB_PREFIX ."system_log_content SET ";
				$cond = $space = "";
				
				//获取操作之前数据
				if($this->input['pre_data'])
				{
					$cond .= $space . " pre_data='" . addslashes(json_encode($this->input['pre_data'])) . "'";
					$space = ",";
				}
				//获取操作之后数据
				if($this->input['up_data'])
				{
					$cond .= $space . " up_data='" . addslashes(json_encode($this->input['up_data'])) . "'";
					$space = ",";
				}
				if($cond)
				{
					$sql .= $cond . " WHERE log_id = " . $info['id'];
					$this->db->query($sql);				
				}
				return true;
			}
		}		
	}
	
	//根据操作进行日志存储
	public function queryLogs($condition,$orderby,$offset,$count)
	{	
		$limit = " limit {$offset}, {$count}";
		$sql = "SELECT a.*,b.pre_data,b.up_data
				FROM  " . DB_PREFIX ."system_log a LEFT JOIN " . DB_PREFIX .
				"system_log_content b ON a.id = b.log_id WHERE 1 " .$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			if($row['pre_data'])
			{
				$row['pre_data'] = json_decode($row['pre_data']);
			}
			if($row['up_data'])
			{
				$row['up_data'] = json_decode($row['up_data']);
			}
			$logsInfos[] = $row;
		}
		return $logsInfos;
	}
	
	//根据操作进行日志存储
	public function showCount($condition)
	{	
		$sql = "SELECT count(*) AS total
				FROM  " . DB_PREFIX ."system_log a LEFT JOIN " . DB_PREFIX .
				"system_log_content b ON a.id = b.log_id WHERE 1" .$condition;
		$logsCount = $this->db->query_first($sql);
		return $logsCount;
	}
	
	//删除日志
	public function delete($ids)
	{	
		//删除日志
		if(empty($ids))
		{
			return false;
		}
		$sql = "DELETE FROM " . DB_PREFIX . "system_log  WHERE id IN(" . $ids . ")";
		$this->db->query($sql);	
		
		$sql_ = "DELETE FROM " . DB_PREFIX . "system_log_content  WHERE log_id IN(" . $ids . ")";
		$this->db->query($sql_);
		return $ids;
	}	
	
	//删除日志
	public function delete_select($condition)
	{	
		$s = "SELECT id FROM " . DB_PREFIX . "system_log  WHERE 1".$condition;
		$q = $this->db->query($s);
		while($row = $this->db->fetch_array($q))
		{	
			$id[] = $row['id'];
		}
		$ids  = implode(',',$id);
		$sql = "DELETE FROM " . DB_PREFIX . "system_log  WHERE id IN(" . $ids . ")";
		$this->db->query($sql);	
		
		$sql_ = "DELETE FROM " . DB_PREFIX . "system_log_content  WHERE log_id IN(" . $ids . ")";
		$this->db->query($sql_);
		//return $ids;
	}	
	
	//删除日志
	public function deleteContent($ids,$bundle_id,$moudle_id)
	{	
		//删除日志
		if(empty($ids) || empty($bundle_id) || empty($moudle_id))
		{
			return false;
		}
		$sql = "SELECT id FROM " . DB_PREFIX . "system_log WHERE content_id IN(" . $ids . ") AND bundle_id='" . $bundle_id . "' AND moudle_id='" . $moudle_id . "'";
		$q = $this->db->query($sql);
		$log_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			$log_id .= $space . $row['id'];
			$space = ',';
		}
		if($log_id)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "system_log  WHERE id IN(" . $log_id . ")";
			$this->db->query($sql);	
			
			$sql = "DELETE FROM " . DB_PREFIX . "system_log_content  WHERE log_id IN(" . $log_id . ")";
			$this->db->query($sql);			
		}
		return $log_id;
	}
	
	//根据条件查询日志
	public function showLogs($cond,$limit,$con ="")	
	{	
		//根据条件查询日志
		$sql = "SELECT * 
				FROM  " . DB_PREFIX ."system_log 
				WHERE 1" .$cond." ORDER BY id DESC".$limit;
		$q = $this->db->query($sql);
		$op = array();
		$so = array();
		$operation = array();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		$apps = $this->auth->get_app('bundle,name');
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$apps_arr[$v['bundle']] = $v['name'];
			}
		}
		
		$modules = $this->auth->get_module('mod_uniqueid,name');
		if(is_array($modules))
		{
			foreach($modules as $k=>$v)
			{
				$modules_arr[$v['mod_uniqueid']] = $v['name'];
			}
		}
		
		$sq = "SELECT * 
			   FROM  " . DB_PREFIX ."system_log_operation 
			   WHERE 1".$con;
		$qq = $this->db->query($sq);
		while($re = $this->db->fetch_array($qq))
		{
			$operation[$re['id']] = $re['op_name'];
		}
		
		while($row = $this->db->fetch_array($q))
		{	
			$so[$row['source']] = $row['source'];
			//$mo[$row['moudle_id']] = $row['moudle_id'];
			$nsql = "SELECT count(*) 
					 FROM  " . DB_PREFIX ."system_log 
					 WHERE 1" .$cond.$limit;
			$num = $this->db->query_first($nsql);
			$row['num'] = $num['count(*)'];
			$row['cre_time'] = date("Y-m-d H:i:s",$row['create_time']);
			$row['operation'] = $operation[$row['operation']];
			$row['bundle_id'] = $apps_arr[$row['bundle_id']];
			$row['moudle_id'] = $modules_arr[$row['moudle_id']];
			$row['title'] = "[".date("Y-m-d H:i:s",$row['create_time'])."]".$row['user_name'].$row['operation'].":".$row['title'];
			$ret[] = $row;
		}
	
		$info[] = $ret;
		if($con)
		{
			$info[] = $operation;
		}
		else
		{
			$info[] = array();
		}
		$info[] = array_unique($so);		
		//$info[] = $modules_arr;
		return $info;
	}
	
	public function getLogsById($id)
	{
		if(empty($id))
		{
			return false;
		}
		$sql = "SELECT a.*,b.pre_data,b.up_data FROM  " . DB_PREFIX ."system_log a LEFT JOIN " . DB_PREFIX . "system_log_content b ON a.id = b.log_id WHERE 1 AND a.id IN(" . $id . ")";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{		
			if($row['pre_data'])
			{
				$row['pre_data'] = json_decode($row['pre_data']);
			}
			if($row['up_data'])
			{
				$row['up_data'] = json_decode($row['up_data']);
			}
			$info[] = $row;
		}
		return $info;
	}
	
	public function get_app()
	{
		$apps = $this->auth->get_app();
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$ret[$v['id']] = $v['bundle'];
			}
		}
		$sq = "SELECT DITINCT(bundle_id) 
			   FROM  " . DB_PREFIX ."system_log 
			   WHERE 1";
		$qq = $this->db->query($sq);
		while($re = $this->db->fetch_array($qq))
		{
			$operation[$re['id']] = $re['op_name'];
		}
		return $ret;
	}
	
	public function delete_logs_by_deletedate($date)
	{
		//删除日志
		$sql = "DELETE FROM " . DB_PREFIX . "system_log  WHERE create_time <= ".$date;
		$this->db->query($sql);	
		
		$sql = "DELETE FROM " . DB_PREFIX . "system_log_content  WHERE  create_time <= ".$date;
		$this->db->query($sql);
	}
}
?>