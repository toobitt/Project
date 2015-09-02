<?php

class publish extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_plan_set_con($bundle_id,$module_id,$struct_id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set WHERE bundle_id='".$bundle_id."' AND module_id='".$module_id."' AND struct_id='".$struct_id."'";
		return $this->db->query_first($sql);
	}
	
	public function get_plan_set_by_id($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set WHERE id=".$id;
		return $this->db->query_first($sql);
	}
	
	public function get_plan_first()
	{
		$sql = "SELECT p.*,s.*,s.id as sid,p.id as pid FROM ".DB_PREFIX."plan p LEFT JOIN ".DB_PREFIX."plan_set s ON p.set_id=s.id WHERE publish_time<=".TIMENOW." ORDER BY p.id LIMIT 1";
		$plan = $this->db->query_first($sql);
		return $plan;
	}
	
	public function insert_plan($newplan)
	{
		$sql="INSERT INTO " . DB_PREFIX . "plan SET";
		
		$sql_extra=$space=' ';
		foreach($newplan as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
	public function delete_plan($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "plan WHERE id=".$id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function get_plan_set()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set ";
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_plan_by_status($con)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan WHERE 1 ".$con;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function get_child_set($id)
	{
		$sql = "SELECT * FROM ".DB_PREFIX."plan_set WHERE fid=".$id;
		$info = $this->db->fetch_all($sql);
		return $info;
	}
	
	public function update_plan_status($pubdataids,$status)
	{
		$sql = "UPDATE ".DB_PREFIX."plan SET status=".$status." WHERE id in(".$pubdataids.")";
		$this->db->query($sql);
	}
	
	public function insert_log($v)
	{
		$data = array(
			'set_id' => $v['set_id'],
			'from_id' => $v['from_id'],
			'sort_id' => $v['sort_id'],
			'column_id' => $v['column_id'],
			'title' => $v['title'],
			'action_type' => $v['action_type'],
			'publish_time' => $v['publish_time'],
			'publish_user' => $v['publish_user'],
			'ip' => $v['ip'],
			'status' => $v['status'],
		);
		$sql="INSERT INTO " . DB_PREFIX . "plan_log SET";
		
		$sql_extra=$space=' ';
		foreach($data as $k => $v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
	}
	
}
?>