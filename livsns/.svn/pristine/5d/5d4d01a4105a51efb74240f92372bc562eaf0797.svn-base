<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','patient');
define('SCRIPT_NAME', 'Patient');
class Patient extends outerReadBase
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "patient t1 WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			if($row['social_security'])
			{
				$row['social_status'] = 1;
			}
			else 
			{
				$row['social_status'] = 0;
			}
			$row['cond_status'] = $this->settings['cond_status'];
			$patient_id[] = $row['id'];
			$patient_info[$row['id']] = $row;
		}
		
		//判断就诊人是否有医院就诊卡
		$patient_ids = implode(',', $patient_id);
		if($this->input['hospital_id'] && $patient_ids)
		{
			$hospital_id = intval($this->input['hospital_id']);
			$sql = 'SELECT id,patient_id FROM ' . DB_PREFIX . "medical_card WHERE hospital_id = {$hospital_id} AND patient_id IN ({$patient_ids})";
			$q = $this->db->query($sql);
			
			while ($r = $this->db->fetch_array($q))
			{
				$card[$r['patient_id']] = $r['id'];
			}
		}
		
		if(!empty($patient_info))
		{
			foreach ($patient_info as $key => $val)
			{
				if($card[$key])
				{
					$val['card_tag'] = 1;
				}
				else 
				{
					$val['card_tag'] = 0;
				}
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$member_id = intval($this->user['user_id']);
		//$member_id = 496;
		if(!$member_id)
		{
			$this->errorOutput(NOID);
		}
		$condition = '';
		//站点名称
		$condition .= " AND t1.member_id = {$member_id}";
		
		//$condition .= " AND t1.status = 1";
		
		return $condition ;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.* FROM " . DB_PREFIX . "patient t1 WHERE 1 AND t1.id = {$id}";
		$data = $this->db->query_first($sql);
		
		$cond = " AND t1.patient_id = {$id}";
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.*,t2.name as hospital_name FROM " . DB_PREFIX . "medical_card t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2 
					ON t1.hospital_id = t2.hospital_id 
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		$card_info = array();
		while ($row = $this->db->fetch_array($q)) 
		{	
			$card_info[] = $row;
		}
		
		
		$data['card_info'] = $card_info;
		$this->addItem($data);
		$this->output();
	}
	
	//添加就诊人
	public function add_patient()
	{
		$member_id = $this->user['user_id'];
		
		if(!$member_id)
		{
			
			$this->errorOutput('请先登陆');
		}
		
		$patient_name 	= urldecode($this->input['patient_name']);
		$sex			= urldecode($this->input['sex']);
		$id_card		= $this->input['id_card'];
		$cellphone		= $this->input['cellphone'];
		$birthday		= $this->input['birthday'];
		
		if(!$patient_name)
		{
			
			$this->errorOutput('请填写姓名');
		}
		
		if(!$cellphone)
		{
			$this->errorOutput('请填写手机号码');
		}
		
		if(!$id_card)
		{
			$this->errorOutput('请填身份证号码');
		}
		$data = array(
			'member_id'		=> $member_id,
			'name'			=> $patient_name,
			'sex'			=> $sex,
			'id_card'		=> $id_card,
			'cellphone'		=> $cellphone,
			'birthday'		=> $birthday,
			'social_security'	=> $this->input['social_security'],
		);
		
		$sql="INSERT INTO " . DB_PREFIX . "patient SET ";		
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
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	//编辑就诊人
	public function update()
	{
		
		$id = intval($this->input['id']);
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$member_id = $this->user['user_id'];
		
		if(!$member_id)
		{
			$this->errorOutput('请先登陆');
		}
		
		
		$patient_name 	= urldecode($this->input['patient_name']);
		$sex			= urldecode($this->input['sex']);
		$id_card		= $this->input['id_card'];
		$cellphone		= $this->input['cellphone'];
		$birthday		= $this->input['birthday'];
		
		if(!$patient_name)
		{
			
			$this->errorOutput('请填写姓名');
		}
		
		if(!$cellphone)
		{
			$this->errorOutput('请填写手机号码');
		}
		
		if(!$id_card)
		{
			$this->errorOutput('请填身份证号码');
		}
		$data = array(
			'member_id'		=> $member_id,
			'name'			=> $patient_name,
			'sex'			=> $sex,
			'id_card'		=> $id_card,
			'cellphone'		=> $cellphone,
			'birthday'		=> $birthday,
			'social_security'	=> $this->input['social_security'],
		);
		
		
		if (is_array($data) && count($data) > 0) 
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
		
		$table = 'patient';
		$where = ' WHERE id =' . $id;
		
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET ' . $field . $where;
		$this->db->query($sql);
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	
	//删除就诊人
	public function delete()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "patient WHERE id = {$id}";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "medical_card WHERE patient_id = {$id}";
		$this->db->query($sql);
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	
	//查询医院
	public function get_hospital()
	{
		
		$sql = "SELECT hospital_id,name FROM " . DB_PREFIX . "hospital WHERE status = 1";
		$q = $this->db->query($sql);
		
		$hospital = array();
		while ($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		
		$this->output();
	}
	
	//显示就诊卡
	public function card_show()
	{
		$patient_id = intval($this->input['patient_id']);
		
		if(!$patient_id)
		{
			$this->errorOutput('就诊人id不存在');
		}
		
		$cond = " AND t1.patient_id = {$patient_id}";
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t2.name as hospital_name FROM " . DB_PREFIX . "medical_card t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2 
					ON t1.hospital_id = t2.hospital_id 
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	//添加就诊卡
	public function card_add()
	{
		$patient_id 	= intval($this->input['patient_id']);
		$hospital_id 	= intval($this->input['hospital_id']);
		$card_num		= intval($this->input['card_num']);
		if(!$patient_id)
		{
			$this->errorOutput('病人id不存在');
		}
		if(!$hospital_id)
		{
			$this->errorOutput('医院id不存在');
		}
		if(!$card_num)
		{
			$this->errorOutput('就诊卡号不存在');
		}
		
		//一个医院一个就诊人只能添加一个就诊卡
		$sql = "SELECT id FROM " . DB_PREFIX . "medical_card WHERE hospital_id = {$hospital_id} AND patient_id = {$patient_id}";
		$res = $this->db->query_first($sql);
		if($res['id'])
		{
			$this->errorOutput('就诊人已经添加医院就诊卡');
		}
		
		$data = array(
			'hospital_id'	=> $hospital_id,
			'patient_id'	=> $patient_id,
			'card_num'		=> $card_num,
		);
		
		$sql="INSERT INTO " . DB_PREFIX . "medical_card SET ";		
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
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	
	//就诊卡更新
	public function card_update()
	{
		
		$id = intval($this->input['id']);
		
		$patient_id 	= intval($this->input['patient_id']);
		$hospital_id 	= intval($this->input['hospital_id']);
		$card_num		= intval($this->input['card_num']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$patient_id)
		{
			$this->errorOutput('病人id不存在');
		}
		if(!$hospital_id)
		{
			$this->errorOutput('医院id不存在');
		}
		if(!$card_num)
		{
			$this->errorOutput('就诊卡号不存在');
		}
		//一个医院一个就诊人只能添加一个就诊卡
		$sql = "SELECT id FROM " . DB_PREFIX . "medical_card WHERE hospital_id = {$hospital_id} AND patient_id = {$patient_id} AND id != {$id}";
		$res = $this->db->query_first($sql);
		if($res['id'])
		{
			$this->errorOutput('就诊人已经添加医院就诊卡');
		}
		
		$data = array(
			'hospital_id'	=> $hospital_id,
			'patient_id'	=> $patient_id,
			'card_num'		=> $card_num,
		);
		
		
		if (is_array($data) && count($data) > 0) 
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
		
		$table = 'medical_card';
		$where = ' WHERE id =' . $id;
		
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET ' . $field . $where;
		$this->db->query($sql);
		
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	
	//就诊卡详情
	public function card_detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.card_num,t1.hospital_id,t2.name as patient_name,t2.cellphone FROM " . DB_PREFIX . "medical_card t1 
				LEFT JOIN " . DB_PREFIX . "patient t2 
					ON t1.patient_id = t2.id
				WHERE t1.id = {$id}";
		
		$data = $this->db->query_first($sql);
		
		$this->addItem($data);
		$this->output();
	}
	
	//删除就诊卡
	public function card_del()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "medical_card WHERE id = {$id}";
		$this->db->query($sql);
		
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	
}
include(ROOT_PATH . 'excute.php');
?>