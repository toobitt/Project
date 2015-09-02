<?php
define('MOD_UNIQUEID','update_carpark_station');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/CryptAes.class.php';
require_once CUR_CONF_PATH.'lib/functions.php';
define('SCRIPT_NAME', 'update_carpark_station');
class update_carpark_station extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	
			'name' => '停车场站点数据更新',	 
			'brief' => '更新站点可停车位',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 0,		//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{	
	
		$data_api = array();
		include_once CUR_CONF_PATH . 'data/get_carpark.php';
		
		$data_api = get_carpark();		
		//hg_pre($data_api);
		
		if(empty($data_api))
		{
			return false;
		}
		
		$sql = "SELECT id FROM " .DB_PREFIX. "carpark_type WHERE need_update=1";
		$q = $this->db->query_first($sql);
		
		$type_id = $q['id'];
		$data_local = array();
		if($type_id)
		{
			$sql = "SELECT id,parking_num FROM " . DB_PREFIX . "carpark WHERE status = 2 AND type_id = " . $type_id;
			
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$data_local[$r['parking_num']] = $r['id'];
			}
		}
		
		//hg_pre($data_local,0);
		
		
		
		$table = 'carpark';
		
		$add_arr = $del_arr = $upd_arr = array();
		
		$add_arr = array_diff_key($data_api, $data_local);
		$del_arr = array_diff_key($data_local, $data_api);
		$upd_arr = array_intersect_key($data_api,$data_local);
		
		
		//hg_pre($del_arr,0);
		//hg_pre($upd_arr,0);
		//hg_pre($add_arr,0);
		
		if(!empty($upd_arr) && count($upd_arr))
		{
			foreach ($upd_arr as $v)
			{
				$data = array(
					//'name'				=> $v['name'],
					//'parking_num'		=> $v['code'],
					//'address' 			=> $v['address'],
					'parking_space'		=> $v['bwzs'],
					'empty_space'		=> $v['sybws'],	
					//'baidu_longitude'	=> $v['baidu_longitude'],
					//'baidu_latitude'	=> $v['baidu_latitude'],
					'update_time'		=> TIMENOW,
					//'user_name'			=> $this->user['user_name'],
				);
				
				$where = ' AND parking_num = ' . $v['code'];
				$this->_upd($data,$table,$where);
			}
		}
		
		
		if(!empty($del_arr))
		{
			$del_id = array();
			foreach ($del_arr as $v)
			{
				$del_id[] = $v;
			}
			
			
			if(!empty($del_id))
			{
				$del_ids = implode(',', $del_id);
				
				
				$this->_del($del_ids, $table);
			}
		}
							
							
		//exit();
		
		if(!empty($add_arr) && count($add_arr))
		{
			foreach ($add_arr as $v)
			{
				$data = array(
					'name'				=> $v['name'],
					'parking_num'		=> $v['code'],
					'address' 			=> $v['address'],
					'parking_space'		=> $v['bwzs'],
					'empty_space'		=> $v['sybws'],	
					'baidu_longitude'	=> $v['baidu_longitude'],
					'baidu_latitude'	=> $v['baidu_latitude'],
					'GPS_x'				=> $v['x'],
					'GPS_y'				=> $v['y'],
					
					'status'			=> 2,
					'type_id'			=> $type_id,
					'create_time'		=> TIMENOW,
					'update_time'		=> TIMENOW,
					'user_name'			=> $this->user['user_name'],
				);
				$this->_add($data,$table);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	
	
	//删除
	private function _del($ids,$table)
	{
		if(!$ids || !$table)
		{
			return false;
		}
		//删除,更新站点状态为3,标为已下线
		$sql = "UPDATE " . DB_PREFIX . $table . " SET status = 3 WHERE id IN (" . $ids . ")";
		$this->db->query($sql);
	}
	
	
	//更新
	private function _upd($data=array(),$table,$where)
	{
		if(empty($data) || !$table)
		{
			return false;
		}
		
		if($table == '' or $where == '') 
		{
			return false;
		}
		
		$where = ' WHERE 1 '.$where;
		$field = '';
		
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET ' . $field . $where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	
	
	private function _add($data=array(),$table)
	{
		if(!$data || !$table)
		{
			return false;
		}
		
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		$id =  $this->db->insert_id();	
		
		
		
		$sql = "";
		$sql = " UPDATE ".DB_PREFIX . $table . " SET order_id = {$id}  WHERE id = {$id}";
		$this->db->query($sql);
		
		return $id;
	}
}
include(ROOT_PATH . 'excute.php');
