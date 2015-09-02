<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 6931 2012-05-31 07:33:56Z repheal $
***************************************************************************/
class statistics extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert_app($stat_app_arr)
	{
		$sql = "INSERT INTO ".DB_PREFIX."app SET name='" . $stat_app_arr['name']. "',uniqueid='" .$stat_app_arr['uniqueid']. "',is_module='" .$stat_app_arr['is_module']."'";
		$this->db->query($sql);
		return true;
	}
	
//	public function insert_app_set($stat_app_arr)
//	{
//		$sql = "INSERT INTO ".DB_PREFIX."app_setting SET app_uniqueid='" . $stat_app_arr['app_uniqueid']. "'," .
//						"module_uniqueid='" .$stat_app_arr['module_uniqueid']. "'," .
//						"var_name='" .$stat_app_arr['var_name']."',value='" .$stat_app_arr['value']."'";
//		$this->db->query($sql);
//		return true;
//	}
	
	public function get_apps($condition = '')
	{
		$ret = array();
		$sql = "SELECT a1.*,a2.name AS fname,a2.bundle AS fbundle FROM ".DB_PREFIX."app a1 LEFT JOIN ".DB_PREFIX."app a2 ON a1.father=a2.id ".$condition." ORDER BY a1.id ";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['bundle']] = $row;
		}
		return $ret;
	}
	
	public function get_user($condition = '',$offset = '',$count = '',$key_column = '')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."user ".$condition." ORDER BY user_name ";
		if($count)
		{
			$sql .= " limit {$offset},{$count}";
		}
		$info = $key_column?$this->db->fetch_all($sql,$key_column):$this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_appnode()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."app WHERE father=0 ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_app_set($uniqueid,$app_uniqueid='')
	{
		$sql = "SELECT * FROM ".DB_PREFIX."app_set WHERE module_uniqueid='$uniqueid' AND app_uniqueid='$app_uniqueid'";
		$info = $this->db->query_first($sql);
		return $info;
	}
	
//	public function update_appset($app_uniqueid,$module_uniqueid)
//	{
//		$st_key=$this->input['st_key'];
//		$st_type=$this->input['st_type'];
//		//先判断设置里有没有这些数据
//		$sql = "SELECT * FROM ".DB_PREFIX."app_settings WHERE module_id='$module_uniqueid' AND bundle_id='$app_uniqueid'";
//		$info = $this->db->fetch_all($sql);
//		if(empty($info))
//		{
//			//插入
//			foreach($st_key as $k=>$v)
//			{
//				$st_val = $this->input['st_val'];
//				$data = array(
//					'name' => '',
//					'bundle_id' => $app_uniqueid,
//					'module_id' => $module_uniqueid,
//					'type' => urldecode($st_type[$k]),
//					'var_name' => urldecode($st_key[$k]),
//					'value' => urldecode($st_val[$k]),
//				);
//				$sql="INSERT INTO " . DB_PREFIX . "app_settings SET";
//				$sql_extra=$space=' ';
//				foreach($data as $k => $v)
//				{
//					$sql_extra .=$space . $k . "='" . $v . "'";
//					$space=',';
//				}
//				$sql .=$sql_extra;
//				$this->db->query($sql);
//			}
//		}
//		else
//		{
//			//更新
//			foreach($st_key as $k=>$v)
//			{
//				$st_val = $this->input['st_val'];
//				$sql = "UPDATE " . DB_PREFIX . "app_settings SET value='".urldecode($st_val[$k])."' WHERE bundle_id='".$app_uniqueid."' " .
//						"AND module_id='".$module_uniqueid."' AND var_name='".urldecode($st_key[$k])."'";
//				$this->db->query($sql);
//			}
//		}
//		return true;
//	}
	
	public function update_app_set($data)
	{
		$updatedata['status'] = $data['status'];
		$sql="UPDATE " . DB_PREFIX . "app_set SET";
		$sql_extra=$space=' ';
		foreach($updatedata as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE app_uniqueid='".$data['app_uniqueid']."' AND module_uniqueid='".$data['module_uniqueid']."'";
		$this->db->query($sql);
	}
	
	public function insert_app_set($data)
	{
		$sql="INSERT INTO " . DB_PREFIX . "app_set SET";
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
	public function get_records($offset,$count,$con)
	{
		$sql = "SELECT r.*,s.value AS value FROM ". DB_PREFIX ."records r LEFT JOIN ". DB_PREFIX ."app_settings s on r.type=s.type and r.app_uniqueid=s.bundle_id and r.module_uniqueid=s.module_id WHERE 1 ".$con." ORDER BY r.id DESC LIMIT {$offset},{$count}";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_records_num_by_user($con)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."records WHERE 1 ".$con;
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$ret[$row['douser_id']][$row['type']] += 1;
			$ret[$row['douser_id']]['all'] += 1;
		}
		return $ret;
	}
	
	public function delete_by_id()
	{
		if(!$this->input['id'])
		{
			return false;
		}
		$ids = trim(urldecode($this->input['id']));
		$sql="DELETE FROM " . DB_PREFIX . "records WHERE id in ($ids)";
		$this->db->query($sql);
		return  true;
	}
	
	public function delete($con)
	{
		$sql = "DELETE FROM ".DB_PREFIX."records WHERE 1 ".$con;
		$this->db->query($sql);
	}
	
	
}

?>