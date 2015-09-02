<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','doctor');
define('SCRIPT_NAME', 'Doctor');
class Doctor extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t1.title,t1.name,t1.doctor_id,t1.department_id,t1.hospital_id,t1.speciality,t3.host,t3.dir,t3.filepath,t3.filename FROM " . DB_PREFIX . "doctor t1 
				LEFT JOIN " . DB_PREFIX . "materials t3
					ON t1.indexpic_id = t3.id
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			if($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
			{
				
				$row['indexpic'] = array(
					'host'		=> $row['host'],
					'dir'		=> $row['dir'],
					'filepath'	=> $row['filepath'],
					'filename'	=> $row['filename'],
				);
			}
			else 
			{
				
				$row['indexpic'] = array();
			}
			unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	public function get_condition()
	{
		$hospital_id = intval($this->input['hospital_id']);
		$depart_id = intval($this->input['department_id']);
		if(!$hospital_id || !$depart_id)
		{
			$this->errorOutput(NOID);
		}
		
		$condition = '';
		//站点名称
		$condition .= ' AND t1.hospital_id = ' . $hospital_id . " AND t1.department_id = {$depart_id}";
		if($this->input['name'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['name']).'%"';
		}
		
		//$condition .= " AND t1.status = 1";
		
		$condition .= ' ORDER BY t1.order_id  DESC ';
		
		return $condition ;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.id,t1.title,t1.name,t1.doctor_id,t1.department_id,t1.hospital_id,t1.speciality,t1.brief,t3.host,t3.dir,t3.filepath,t3.filename,t2.name as hospital_name,t2.address as hospital_address,t4.name as department_name FROM " . DB_PREFIX . "doctor t1 
				LEFT JOIN " . DB_PREFIX . "materials t3
					ON t1.indexpic_id = t3.id 
				LEFT JOIN " . DB_PREFIX . "hospital t2
					ON t1.hospital_id = t2.hospital_id
				LEFT JOIN " . DB_PREFIX ."departments t4 
					ON t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id 
				WHERE 1 AND t1.id = {$id}";
		$row = $this->db->query_first($sql);
		
		if($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
		{
			$row['indexpic'] = array(
				'host'		=> $row['host'],
				'dir'		=> $row['dir'],
				'filepath'	=> $row['filepath'],
				'filename'	=> $row['filename'],
			);
		}
		else 
		{
			$row['indexpic'] = array();
		}
		unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
		
		//关注处理
		$row['attention_tag'] = 0;
		$member_id = $this->user['user_id'];
		if($member_id)
		{
			$sql = "SELECT id FROM " . DB_PREFIX . "attention WHERE hospital_id = {$row['hospital_id']} AND doctor_id = {$row['doctor_id']} AND member_id = {$member_id}";
			$res = $this->db->query_first($sql);
			
			if($res['id'])
			{
				$row['attention_tag'] = 1;
				$row['attention_id'] = $res['id'];
			}
			else 
			{
				$row['attention_tag'] = 0;
			}
		}
		$this->addItem($row);
		$this->output();
	}
	
	
	//关注医生列表
	public function attention_show()
	{
		$member_id = $this->user['user_id'];
		if(!$member_id)
		{
			$this->errorOutput('请先登陆');
		}
		//查询我关注的医生id
		$sql = "SELECT * FROM " . DB_PREFIX . "attention WHERE member_id = {$member_id}";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$hospital_id[] = $r['hospital_id'];
			$doctor_id[] = $r['doctor_id'];
			$depart_id[] = $r['depart_id'];
		}
		
		$hospital_ids = implode(',', $hospital_id);
		$doctor_ids = implode(',', $doctor_id);
		$depart_ids = implode(',', $depart_id);
		
		$cond = " AND t1.hospital_id IN ({$hospital_ids}) AND t1.department_id IN ({$depart_ids}) AND t1.doctor_id IN ({$doctor_ids}) GROUP BY t1.department_id ";
		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t1.title,t1.name,t1.doctor_id,t1.department_id,t1.hospital_id,t1.speciality,t3.host,t3.dir,t3.filepath,t3.filename,t2.name as hospital_name,t4.name as department_name FROM " . DB_PREFIX . "doctor t1 
				LEFT JOIN " . DB_PREFIX . "materials t3
					ON t1.indexpic_id = t3.id
				LEFT JOIN " . DB_PREFIX . "hospital t2
					ON t1.hospital_id = t2.hospital_id
				LEFT JOIN " . DB_PREFIX . "departments t4
					ON t1.department_id = t4.department_id
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			if($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
			{
				
				$row['indexpic'] = array(
					'host'		=> $row['host'],
					'dir'		=> $row['dir'],
					'filepath'	=> $row['filepath'],
					'filename'	=> $row['filename'],
				);
			}
			else 
			{
				
				$row['indexpic'] = array();
			}
			unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
			$this->addItem($row);
		}
		$this->output();
	}	
	
	//关注医生
	public function attention_doctor()
	{
		$hospital_id	= intval($this->input['hospital_id']);
		$depart_id		= intval($this->input['department_id']);
		$doctor_id 		= intval($this->input['doctor_id']);
		$member_id		= intval($this->user['user_id']);
		
		
		if(!$member_id)
		{
			$this->errorOutput('请先登录');
		}
		
		if(!$hospital_id || !$depart_id || !$doctor_id)
		{
			$this->errorOutput(NOID);
		}
		
		
		$data = array(
			'hospital_id'	=> $hospital_id,
			'depart_id'		=> $depart_id,
			'doctor_id'		=> $doctor_id,
			'member_id'		=> $member_id,
		);
		
		$sql="INSERT INTO " . DB_PREFIX . "attention SET ";		
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
		$id = $this->db->insert_id();
		
		$data['id'] = $id;
		
		$this->addItem($data);
		$this->output();
	}
	
	//关注医生
	public function attention_cancel()
	{
		$member_id = intval($this->user['user_id']);
		
		if(!$member_id)
		{
			$this->errorOutput('请先登录');
		}
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql="DELETE FROM " . DB_PREFIX . "attention WHERE id = {$id} AND member_id = {$member_id}";		
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		$this->addItem($data);
		$this->output();
	}
	
}
include(ROOT_PATH . 'excute.php');
?>